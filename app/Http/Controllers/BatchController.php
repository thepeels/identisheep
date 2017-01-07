<?php namespace App\Http\Controllers;

use App\Domain\Sheep\SheepOffService;
use App\Domain\Sheep\TagNumber;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Sheep;
use App\Models\Single;
use App\Models\Homebred;
use View,Session,DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Domain\Sheep\Sex;

class BatchController extends Controller {

    /**
     * BatchController constructor.
     *
     * Filtered by Auth() before usage.
     */
    public function __construct(){
        $this->middleware('auth');
    }
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
    public function  postCsvload()
    {
        $rules1 = Sheep::$rules['dates'];
        $rules2 = Sheep::$rules['where_to'];
        $validation = Validator::make(Input::all(), $rules1 + $rules2);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $destination    = Input::get('destination');
        $owner           = Auth::user()->id;
        $move_off       = new \DateTime(Input::get('year').'-'.Input::get('month').'-'.Input::get('day').' '.'00:00:00');
        $process_file   = file(Input::file('file_raw'));
            $ewelist    = array_map('str_getcsv', ($process_file));

        if(Input::get('check')) {
            $i = 0;

            echo($ewelist[0][0]."<br>");
            echo($ewelist[1][0]."<br><br>");

            foreach ($ewelist[2] as $ewe) {
                $tag = new TagNumber($ewe);
                if($tag->getSerialNumber() != 0) {
                    $i++;
                    echo("{$i} {$tag->getShortTagNumber()}<br>");
                }
            }
            echo("<br>".$i.' Tags.');
            exit();
        }
        if(Input::get('load')) {

            $added = -0;
            foreach ($ewelist[2] as $ewe) {
                $tag = new TagNumber($ewe);
                if($tag->getSerialNumber() != 0) {
                    $sheep_exists = Sheep::check($tag->getFlockNumber(), $tag->getSerialNumber(), $owner);
                    if (NULL !== $sheep_exists) $added++;

                    $ewe = Sheep::firstOrNew([
                        'flock_number' => $tag->getFlockNumber(),
                        'serial_number' => $tag->getSerialNumber()]);
                    $ewe->setOwner($owner);
                    $ewe->setFlockNumber($tag->getFlockNumber());
                    $ewe->setSupplementaryTagFlockNumber($tag->getFlockNumber());
                    $ewe->setSerialNumber($tag->getSerialNumber());
                    $ewe->setMoveOff($move_off);
                    $ewe->setDestination($destination);
                    $ewe->setAlive(FALSE);
                    $ewe->save();
                }
            }
        }
        Session::flash('message', $added .' Tags processed, Sheep moved to Off List.');
        return Redirect::to('batch/batchops');
    }
    public function getBatchopson()
    {
        return View::make('batchopson')->with([
            'title' => 'Batch Operations',
            'subtitle' => '- Sheep Onto Holding'
        ]);
    }
    public function  postCsvloadon()
    {
        $rules = Sheep::$rules['dates'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $owner          = Auth::user()->id;
        $d              = Input::get('day');
        $m              = Input::get('month');
        $y              = Input::get('year');
        $move_on       = $y.'-'.$m.'-'.$d.' '.'00:00:00';
        $l              = DB::table('sheep')->where('owner',$owner)->max('local_id');
        $process_file   = file(Input::file('file_raw'));
        $ewelist = array_map('str_getcsv', ($process_file));

        if(Input::get('check')) {
            $i = 0;

            foreach ($ewelist[2] as $ewe) {
                $tag = new TagNumber($ewe);
                if($tag->getSerialNumber() != 0) {
                    $i++;
                    echo("{$i} {$tag->getShortTagNumber()}<br>");
                }
            }
            echo("<br>".$i.' Tags.');
            exit();
        }
        if(Input::get('load')) {

            $added = 0;
            foreach ($ewelist[2] as $ewe) {
                $tag = new TagNumber($ewe);
                $sheep_exists = Sheep::check($tag->getFlockNumber(), $tag->getSerialNumber(), $owner);
                if($tag->getSerialNumber() != 0) {
                    if (NULL === $sheep_exists) {
                        $l++;
                        $added++;
                        $ewe = Sheep::firstOrNew([
                            'flock_number' => $tag->getFlockNumber(),
                            'serial_number' => $tag->getSerialNumber()
                        ]);
                        $ewe->setOwner($owner);
                        $ewe->setLocalId($l);
                        $ewe->setFlockNumber($tag->getFlockNumber());
                        $ewe->setSupplementaryTagFlockNumber($tag->getFlockNumber());
                        $ewe->setOriginalFlockNumber($tag->getFlockNumber());
                        $ewe->setSerialNumber($tag->getSerialNumber());
                        $ewe->setOriginalSerialNumber($tag->getSerialNumber());
                        $ewe->setMoveOn($move_on);

                        $ewe->save();
                    }
                }
            }
        }
        Session::flash('message', $added .' Tags processed, Sheep Added.');
        return Redirect::to('batch/batchopson');
    }
    /**
     * Load Batch entry form
     *
     * @param none
     *
     * @return view
     */
    public function getBatch($home_bred)
    {
        return View::make('sheepbatch')->with([
            'id'=>$this->user(),
            'title' => 'Enter Batch of Tags - Movement of Sheep onto Holding',
            'alt_title' => 'Enter Batch of Tags - Home Bred Sheep Entering Flock',
            'home_bred' => $home_bred
        ]);
    }

    /**
     * Post batch entry
     *
     *
     */
    public function postBatch()
    {
        $rules = Sheep::$rules['batch'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $owner          = Input::get('id');
        $home_bred      = Input::get('home_bred');
        $flock_number   = Input::get('flock_number');
        $start_tag      = Input::get('start_tag');
        $end_tag        = Input::get('end_tag');
        $move_on        = new \DateTime(Input::get('year') . '-' . Input::get('month') . '-' . Input::get('day') .' '.'00:00:00');
        $colour_of_tag  = Input::get('colour_of_tag');
        $local_id              = DB::table('sheep')->where('owner',$owner)->max('local_id');

        if ($start_tag <= $end_tag){
            $i = $start_tag;

            $home_bred_count = 0;
            $added = 0;
            while ($i <= $end_tag){
                $sheep_exists = Sheep::check($flock_number,$i,$owner);
                if($i != 0) {
                    if (NULL === $sheep_exists) {
                        $local_id ++;
                        $home_bred_count ++;
                        $added ++;

                        $ewe = new Sheep();

                        $ewe->setLocalId($local_id);
                        $ewe->setOwner($owner);
                        $ewe->setFlockNumber($flock_number);
                        $ewe->setOriginalFlockNumber($flock_number);
                        $ewe->setSupplementaryTagFlockNumber($flock_number);
                        $ewe->setSerialNumber($i);
                        $ewe->setOriginalSerialNumber($i);
                        $ewe->setSupplementarySerialNumber($i);
                        $ewe->setMoveOn($move_on);
                        $ewe->setTagColour($colour_of_tag);
                        $ewe->setSex('female');

                        $ewe->save();

                    }
                }
                $i++;
            }
            if($home_bred !== 'false'){
                $batch_of_tags = new Homebred();
                    $batch_of_tags->setFlockNumber($home_bred);
                    $batch_of_tags->setDateApplied($move_on);
                    $batch_of_tags->setUserId($owner);
                    $batch_of_tags->setCount($home_bred_count);
                //dd($home_bred_count);
                $batch_of_tags->save();
            }
        }
        $local_id=NULL;
        Session::flash('message', $added .' Tags processed, Sheep Added.');
        return Redirect::back()->withInput(
            [
                'day'           => Input::get('day'),
                'month'         => Input::get('month'),
                'year'          => Input::get('year'),
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
        return View::make('sheepbatchoff')->with([
            'id'=>$this->user(),
            'title' => 'Enter Batch of tags',
            'alt_title' => 'Enter Batch of Home bred tags',
            'home_bred'=>$home_bred
        ]);
    }
    /**
     * Post batch entry
     *
     *
     */
    public function postBatchoff()
    {
        $rules1 = Sheep::$rules['batch'];
        $rules2 = Sheep::$rules['where_to'];
        $validation = Validator::make(Input::all(), $rules1 + $rules2);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }

        $owner        = Input::get('id');
        $home_bred      = Input::get('home_bred');
        $flock_number   = Input::get('flock_number');
        $start_tag      = Input::get('start_tag');
        $end_tag        = Input::get('end_tag');
        $dateOfMovement = new \DateTime(Input::get('year') . '-' . Input::get('month') . '-' . Input::get('day'));
        $colour_of_tag  = Input::get('colour_of_tag');
        $destination    = Input::get('destination');
        $sex            = new Sex('Female');
        if ($start_tag <= $end_tag){
            $i = $start_tag;
            $home_bred_count = 0;
            while ($i <= $end_tag){
                $tagNumber = new TagNumber('UK0' . Input::get('flock_number') . sprintf('%05d',$i));
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
                'day'           => Input::get('day'),
                'month'         => Input::get('month'),
                'year'          => Input::get('year'),
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
    public function getSingleoff()
    {
        return View::make('sheepsingleoff')->with([
            'id'=>$this->user(),
            'title' => 'Enter Movement to Slaughter'
        ]);
    }
    /**
     * Post batch entry
     *
     *
     */
    public function postSingleoff()
    {
        $rules1 = Sheep::$rules['single'];
        $rules2 = Sheep::$rules['where_to'];
        $rules3 = Sheep::$rules['dates'];
        $validation = Validator::make(Input::all(), $rules1 + $rules2 + $rules3);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $id             = Input::get('id');
        $flock_number   = Input::get('flock_number');
        $destination    = Input::get('destination');
        $count          = Input::get('count');
        $date_applied   = new \DateTime(Input::get('year') . '-' . Input::get('month') . '-' . Input::get('day'));

                $tags = New Single;
                $tags->setUserId($id);
                $tags->setFlockNumber($flock_number);
                $tags->setCount($count);
                $tags->setDestination($destination);
                $tags->setDateApplied($date_applied);
                $tags->save();

        return Redirect::back()->withInput(
            [
                'day'           => Input::get('day'),
                'month'         => Input::get('month'),
                'year'          => Input::get('year'),
                'flock_number'  =>$flock_number,
                'destination'   =>$destination
            ]);
    }

    public function getSinglelist()
    {
        $batches = Single::view($this->user());

        return View::make('sheepsinglelist')->with([
            'id'=>$this->user(),
            'batches' => $batches,
            'title' => 'Batch tag Applications'
        ]);
    }
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
}
