<?php namespace App\Http\Controllers;

use App\Domain\FileHandling\ExcelHandler;
use App\Domain\FileHandling\FileHandler;
use App\Domain\Sheep\SheepOffService;
use App\Domain\Sheep\SheepOnService;
use App\Domain\Sheep\TagNumber;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Sheep;
use App\Models\Single;
use App\Models\Homebred;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Domain\Sheep\Sex;

class BatchController extends Controller {

    /**
     * BatchController constructor.
     * filtered by Auth
     */
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('subscribed');
    }

    /**
     * @return mixed
     */
    public static function user(){
        return Auth::user()->id;
    }
    public function getBatchops()
    {
        return View::make('batchops')->with([
            'title' => 'Batch Operations',
            'subtitle' => '- Sheep Off Holding'
        ]);
    }

    /**
     * post a batch movement OFF with a .csv or .xls file
     * @param Request $request
     * @return mixed
     */
    public function  postCsvload(Request $request)
    {
        $rules1 = Sheep::$rules['simple_dates'];
        $rules2 = Sheep::$rules['where_to'];
        $rules3 = Sheep::$rules['file_raw'];
        $validation = Validator::make($request->all(), $rules1 + $rules2 + $rules3);
        if ($validation->fails()) {dd($request->file_raw->getMimeType());
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $destination    = $request->destination;
        $owner          = Auth::user()->id;
        $move_off       = new \DateTime($request->year.'-'.$request->month.'-'.$request->day.' '.'00:00:00');
        $source_or_destination = $destination;

        $type =($request->file_raw->getMimeType());//$request->file_raw->getClientOriginalName().' '.
        //dd($type);
        /****************************************
        $file = $request->file_raw;


        $file_info = new \finfo(FILEINFO_MIME);	// object oriented approach!
        $mime_type = $file_info->buffer(file_get_contents($file));  // e.g. gives "image/jpeg"

        switch($mime_type) {
            case "image/jpeg":
                // your actions go here...
        }

        *********************************************/
        list($process_file, $ewelist) = $this->detectAndProcessMimeType($request, $type);

        $request->flash();
        if($request->check) {
            $tag_list = $process_file->extractTagNumbers();
            return view('batchcheck')->with([
                'title'         => '.csv Contents ',
                'print_title'   => Auth::user()->business.' - holding no: '.Auth::user()->holding,
                'heading'       => $request->file_raw->getClientOriginalName(),
                'source'        => $source_or_destination?'&nbsp;&nbsp;&nbsp;- &nbsp;&nbsp;Sheep to '.$source_or_destination:'',
                'tag_list'      => $tag_list,
                'date'          => date_format($move_off,'d-m-Y')
            ]);
        }

        if($request->load) {

            $added = 0;
            $processed = 0;
            foreach ($ewelist as $ewe) {
                $tag = new TagNumber($ewe);
                if($tag->getSerialNumber() != 0) {
                    $sheep_exists = Sheep::check($tag->getFlockNumber(), $tag->getSerialNumber(), $owner);
                    //dd(!$sheep_exists);
                    if ($sheep_exists)$processed ++;
                    $ewe = Sheep::firstOrNew([
                        'flock_number' => $tag->getFlockNumber(),
                        'serial_number' => $tag->getSerialNumber()]);
                    //dd($ewe->exists());
                    $ewe->setOwner($owner);
                    $ewe->setCountryCode($tag->getCountryCode());
                    $ewe->setFlockNumber($tag->getFlockNumber());
                    $ewe->setSupplementaryTagFlockNumber($tag->getFlockNumber());
                    $ewe->setSupplementarySerialNumber($tag->getSerialNumber());
                    $ewe->setSerialNumber($tag->getSerialNumber());
                    $ewe->setMoveOff($move_off);
                    $ewe->setDestination($destination);
                    $ewe->setAlive(FALSE);
                    if (!$sheep_exists){
                        $added++;
                        $ewe->setOriginalSerialNumber($tag->getSerialNumber());
                        $ewe->setOriginalFlockNumber($tag->getFlockNumber());
                    }
                    $ewe->save();
                }
            }
        }
        Session::flash('message', $processed .' Tags processed, '. $added . ' Sheep Added.' . PHP_EOL . 'Sheep now in Off List.');
        return Redirect::to('batch/batchops');
    }

    public function getBatchopson()
    {
        return View::make('batchopson')->with([
            'title' => 'Batch Operations',
            'subtitle' => '- Sheep Onto Holding'
        ]);
    }

    /**
     * post a batch movement ON with a .csv or .xls file
     * @param Request $request
     * @return mixed
     */
    public function  postCsvloadon(Request $request)
    {
        $rules1 = Sheep::$rules['dates'];
        $rules3 = Sheep::$rules['file_raw'];
        $validation = Validator::make(Input::all(), $rules1 + $rules3);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $owner          = $this->user();
        $d              = $request->day;
        $m              = $request->month;
        $y              = $request->year;
        $move_on        = $y.'-'.$m.'-'.$d.' '.'00:00:00';
        $l              = DB::table('sheep')->where('owner',$owner)->max('local_id');
        $source         = $request->source;
        $source_or_destination = $source;

        $type =($request->file_raw->getMimeType());//$request->file_raw->getClientOriginalName().' '.
        //dd($request->file_raw->getClientOriginalName());
        //f(stripos($type,'text' )==False)dd('False');
        //dd($type);

        if(stripos($type,'text' )!==False){
            $process_file = new FileHandler(file(Input::file('file_raw')),$request->file_raw->getClientOriginalName());

            $ewelist = $process_file->mappedFile();
            //dd('got to here');

        }

        elseif(stripos($type,'corrupt' )!==False || stripos($type,'excel' )!==False ||
            stripos($type,'vnd.ms-office' )!==False || stripos($type,'vnd.openxml')!==False){//dd($type);
            $process_file = new ExcelHandler((Input::file('file_raw')),$request->file_raw->getClientOriginalName());
            $ewelist = $process_file->returnTagNumbers();
            //$some_thing = $process_file->excelFile();
            //dd($ewelist);

        };
        $request->flash();
        ($type);
        if($request->check) {
            $tag_list = $process_file->extractTagNumbers();
            //dd($tag_list);
            return view('batchcheck')->with([
                'title'         => 'Csv Contents',
                'print_title'   => Auth::user()->business.' - holding no: '.Auth::user()->holding,
                'heading'       => $request->file_raw->getClientOriginalName(),
                'source'        => $source_or_destination?'&nbsp;&nbsp;&nbsp;- &nbsp;&nbsp;Sheep from '.$source_or_destination:'',
                'tag_list'      => $tag_list,
                'date'          => date('d-m-Y')
            ]);
        }
        if($request->load) {
            $added = 0;
            foreach ($ewelist as $number) {
                $tag = new TagNumber($number);
                //dd($tag->getCountryCode());
                $sheep_exists = Sheep::check($tag->getFlockNumber(), $tag->getSerialNumber(), $owner);
                if($tag->getSerialNumber() != 0) {
                    if (!$sheep_exists) {
                        $l++;
                        $added++;
                        $ewe = Sheep::firstOrNew([
                            'flock_number' => $tag->getFlockNumber(),
                            'serial_number' => $tag->getSerialNumber()
                        ]);
                        $ewe->setOwner($owner);
                        $ewe->setLocalId($l);
                        $ewe->setCountryCode($tag->getCountryCode());
                        $ewe->setFlockNumber($tag->getFlockNumber());
                        $ewe->setSupplementaryTagFlockNumber($tag->getFlockNumber());
                        $ewe->setSupplementarySerialNumber($tag->getSerialNumber());
                        $ewe->setOriginalFlockNumber($tag->getFlockNumber());
                        $ewe->setSerialNumber($tag->getSerialNumber());
                        $ewe->setOriginalSerialNumber($tag->getSerialNumber());
                        $ewe->setMoveOn($move_on);
                        $ewe->setSource($source);

                        $ewe->save();
                    }
                }
            }
        }
        Session::flash('message', $added .' Tags processed, Sheep Added.');
        return Redirect::to('batch/batchopson');
    }

    /**
     * Show home bred batch entry form
     * @param $home_bred
     * @return mixed
     */
    public function getBatch($home_bred)
    {
        return View::make('batch')->with([
            'id'=>$this->user(),
            'title' => 'Enter Batch of Tags - Movement of Sheep onto Holding',
            'alt_title' => 'Enter Batch of Tags - Home Bred Sheep Entering Flock',
            'home_bred' => $home_bred
        ]);
    }

    /**
     * Post batch entry ON
     * @param Request $request
     * @return mixed
     */
    public function postBatch(Request $request)
    {
        $rules = Sheep::$rules['batch'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $owner          = $request->id;
        $home_bred      = $request->home_bred;
        $flock_number   = $request->flock_number;
        $start_tag      = $request->start_tag;
        $end_tag        = $request->end_tag;
        $move_on        = new \DateTime($request->year . '-' . $request->month . '-' . $request->day .' '.'00:00:00');
        $colour_of_tag  = $request->colour_of_tag;
        $local_id       = DB::table('sheep')->where('owner',$owner)->max('local_id');
        $country_code   = $request->country_code?:'UK0';
        $sex            = new Sex($request->sex?:'female');
        if($home_bred != 'false'){$source = 'Home-bred';} //$home_bred is a string!
        else{$source         = $request->source?:'';}
        if ($start_tag <= $end_tag){
            $i = $start_tag;

            $home_bred_count = 0;
            $added = 0;
            while ($i <= $end_tag){
                $sheep_exists = Sheep::check($flock_number,$i,$owner);
                if($i != 0) {
                    if (!$sheep_exists) {
                        $local_id ++;
                        $home_bred_count ++;
                        $added ++;
                        $tag = new Tagnumber($country_code . $flock_number . sprintf('%05d',$i));
                        $sheep = new SheepOnService();
                        $sheep->movementOn($tag, $move_on, $colour_of_tag, $sex, $owner, $local_id, $source);
                    }
                }
                $i++;
            }
            if($home_bred != 'false'){
                $batch_of_tags = new Homebred();
                    $batch_of_tags->setFlockNumber($home_bred);
                    $batch_of_tags->setDateApplied($move_on);
                    $batch_of_tags->setUserId($owner);
                    $batch_of_tags->setCount($home_bred_count);
                    $batch_of_tags->setStart($start_tag);
                    $batch_of_tags->setFinish($end_tag);
                //dd($home_bred_count);
                $batch_of_tags->save();
            }
        }
        $local_id=NULL;
        Session::flash('message', $added .' Tags processed, Sheep Added.');
        return Redirect::back()->withInput(
            [
                'day'           => $request->day,
                'month'         => $request->month,
                'year'          => $request->year,
                'flock_number'  =>$flock_number,
                'colour_of_tag' =>$colour_of_tag
            ]);
    }
    /**
     * Load Batch entry form
     *
     * @param none
     *
     * @return view
     */
    public function getBatchoff($home_bred)
    {
        return View::make('batchoff')->with([
            'id'=>$this->user(),
            'title' => 'Enter Batch of tags',
            'alt_title' => 'Enter Batch of Home bred tags',
            'home_bred'=>$home_bred
        ]);
    }

    /**
     * Post batch entry
     * @param Request $request
     * @return mixed
     */
    public function postBatchoff(Request $request)
    {
        $rules1 = Sheep::$rules['batch'];
        $rules2 = Sheep::$rules['where_to'];
        $validation = Validator::make(Input::all(), $rules1 + $rules2);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }

        $owner          = $request->id;
        $home_bred      = $request->home_bred;
        $flock_number   = $request->flock_number;
        $start_tag      = $request->start_tag;
        $end_tag        = $request->end_tag;
        $dateOfMovement = new \DateTime($request->year . '-' . $request->month . '-' . $request->day);
        $colour_of_tag  = $request->colour_of_tag;
        $destination    = $request->destination;
        $sex            = new Sex('Female');
        $country_code   = $request->country_code?:'UK0';
        if ($start_tag <= $end_tag){
            $i = $start_tag;
            $home_bred_count = 0;
            while ($i <= $end_tag){
                $tagNumber = new TagNumber($country_code . $request->flock_number . sprintf('%05d',$i));
                $sheep = new SheepOffService();
                $sheep->recordMovement($tagNumber,$dateOfMovement,$destination,$sex,$owner,$colour_of_tag);
                $home_bred_count ++;
                $i++;

            }
        }
        if($home_bred !== 'false' && $home_bred_count >= 1){
            $batch_of_tags = new Homebred();
            $batch_of_tags->setFlockNumber($tagNumber->getFlockNumber());
            $batch_of_tags->setDateApplied($dateOfMovement);
            $batch_of_tags->setUserId($owner);
            $batch_of_tags->setCount($home_bred_count);
            //dd($home_bred_count);
            $batch_of_tags->save();
        }

        return Redirect::back()->withInput(
            [
                'day'           => $request->day,
                'month'         => $request->month,
                'year'          => $request->year,
                'flock_number'  =>$flock_number,
                'colour_of_tag' =>$colour_of_tag
            ]);
    }

    /**
     * Load Batch entry form
     * @return mixed
     */
    public function getSingleoff()
    {
        return View::make('sheepsingleoff')->with([
            'owner' => $this->user(),
            'title' => 'Enter Movement to Slaughter',
            'start_tag' => $this->start_tag()
        ]);
    }

    private function start_tag(){
        $start_tag = Single::where('user_id',$this->user())->max('finish') + 1;
        return($start_tag);
    }
    /**
     * Post batch tag entry
     * @param request $request
     * @return mixed
     */
    public function postSingleoff(Request $request)
    {
        $rules1 = Sheep::$rules['single'];
        $rules2 = Sheep::$rules['where_to'];
        $rules3 = Sheep::$rules['simple_dates'];
        $rules4 = Sheep::$rules['single-start'];
        $validation = Validator::make(Input::all(), $rules1 + $rules2 + $rules3 + $rules4);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $owner          = $request->owner;
        $flock_number   = $request->flock_number;
        $destination    = $request->destination;
        $count          = $request->count;
        $start          = $request->start;
        $date_applied   = new \DateTime($request->year . '-' . $request->month . '-' . $request->day);

                $tags = New Single;
                $tags->setUserId($owner);
                $tags->setFlockNumber($flock_number);
                $tags->setCount($count);
                $tags->setDestination($destination);
                $tags->setDateApplied($date_applied);
                $tags->setStart($start);
                $tags->setFinish($start + $count - 1);
                $tags->save();

        Session::flash('message','Application of ' . $count . ' tags recorded');
        return Redirect::back()->withInput(
            [
                'day'           => $request->day,
                'month'         => $request->month,
                'year'          => $request->year,
                'flock_number'  =>$flock_number,
                'destination'   =>$destination
            ]);
    }

    /**
     * @return mixed
     */
    public function getSinglelist()
    {
        $batches = Single::view($this->user());

        return View::make('sheepsinglelist')->with([
            'id'=>$this->user(),
            'batches' => $batches,
            'title' => 'Batch tag Applications'
        ]);
    }

    /**
     * @return mixed
     */
    public function getHomebredlist()
    {
        $count  = Homebred::howmany($this->user());
        $tags   = Homebred::numbers($this->user());
        if (!is_numeric($count)) { $count = 0;
            Session::flash('message', 'There were no home-bred tag applications in this time period, Reset the date range?');
        }
        return view('homebredlist')->with([
            'title'=>'Home Bred Sheep, EID Tags Applied (total = '.$count.')',
            'tags'  => $tags
        ]);
    }

    /**
     * @param Request $request
     * @param $type
     * @return array
     */
    public function detectAndProcessMimeType(Request $request, $type)
    {
        //dd($type);
        if (stripos($type, 'text') !== False) {
            $process_file = new FileHandler(file(Input::file('file_raw')), $request->file_raw->getClientOriginalName());

            $ewelist = $process_file->mappedFile();

        }
        if (stripos($type, 'corrupt') !== False || stripos($type, 'excel') !== False ||
            stripos($type, 'vnd.ms-office') !== False || stripos($type,'vnd.openxmlformats-officedocument.spreadsheetml')!==False) {
            $process_file = new ExcelHandler((Input::file('file_raw')), $request->file_raw->getClientOriginalName());
            $ewelist = $process_file->returnTagNumbers();

        };

        return array($process_file, $ewelist);
    }

    /**
     * @param $ewelist
     * @return array
     */
    private function batchcheck($ewelist)
    {
        $i = 0;
        $tag_list = [];
        foreach ($ewelist[2] as $ewe) {
            $tag = new TagNumber($ewe);
            if ($tag->getSerialNumber() != 0) {
                $i++;
                $tag_list[$i][0] = $i;
                $tag_list[$i][1] = $tag->getShortTagNumber();
            }
        }

        return $tag_list;
    }
}
