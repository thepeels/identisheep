<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Homebred;
use App\Models\Sheep;
use App\user;
use Auth,View,Input,Redirect,Validator,Session,Carbon\Carbon,DB;
use Illuminate\Pagination\Paginator;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class SheepController extends Controller {
    /**
     * SheepController constructor.
     *
     * cause to be Auth filtered before use
     */
    public function __construct(){
        $this->middleware('auth');
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$ewes = Sheep::females($this->user());

        return view('sheeplist')->with([
            'ewes'=>$ewes[0],
            'title'=>'All Female Sheep',
            'count'=>$ewes[1]
            ]);
	}
    private static function user()
    {
        return Auth::user()->id;
    }
    public function getEwes($print)
    {
        if ($print == NULL)$print = 'screen';
        if ($print == 'print'){
            $ewes = Sheep::stockPrint($this->user(),'female');
        }
        else {
            $ewes = Sheep::stock($this->user(), 'female');
        }
        return view('sheeplist')->with([
            'ewes'=>$ewes[0],
            'title'=>'All Female Sheep',
            'count'=>$ewes[1]
        ]);
    }
    public function getTups($print)
	{
        if ($print == 'print'){
            $ewes = Sheep::stockPrint($this->user(),'male');
        }
        else {
            $ewes = Sheep::stock($this->user(), 'male');
        }
        return view('sheeplist')->with([
            'ewes'=>$ewes[0],
            'title'=>'All Tups ',
            'count'=>$ewes[1]
        ]);
	}
    public function getOfflist($print)
    {
        if ($print == 'print'){
            $ewes = Sheep::offListPrint($this->user());
        }
        else {
            $ewes = Sheep::offList($this->user());
        }

        return view('sheeplist')->with([
            'ewes'  =>  $ewes,
            'title' =>  'Sheep Moved Off'
        ]);
    }
    public function getDeadlist($print)
    {
        if ($print == 'print'){
            $ewes = Sheep::deadPrint($this->user());
        }
        else {
            $ewes = Sheep::dead($this->user());
        }
        //$ewes = Sheep::dead($this->user());

        return view('sheeplist')->with([
            'ewes'=>$ewes,
            'title'=>'Dead List'
        ]);
    }
	/**
	 * Show the form for deleting records.
	 *
	 * @return Response
	 */
	public function getDelete()
	{
        $year = date('Y', strtotime('-10 years'));
        return View::make('delete_old')->with([
            'title'         => 'Delete old Records',
            'year'          => $year

        ]);
	}
	public function postDelete()
    {
        $rules = Sheep::$rules['old_dates'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $year = Input::get('year');
        $date = Carbon::create($year,11,30,23,59,59,'UTC');
        $date = $date->toDateTimeString();
        Sheep::permanentDelete($this->user(),$date);
        return Redirect::to('sheep/ewes/screen');

    }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  none
	 * @return Response
	 */
	public function postChangetags()
	{
        $rules = Sheep::$rules['death'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $id = Input::get('id');
        $old_e_flock = Input::get('old_e_flock');
		$ewe = Sheep::where('id',$id)->first();
        if ($ewe->serial_number != Input::get('e_tag')) {
            $ewe->older_serial_number = $ewe->old_serial_number;
            $ewe->old_serial_number = $ewe->serial_number;
            $ewe->serial_number = Input::get('e_tag');
        }
        $ewe->flock_number = Input::get('e_flock');
        $ewe->save();
        return View::make('sheepfinder')->with([
            'title'     => 'Find another sheep',
            'e_flock'   => $old_e_flock]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        //
	}
    /**
     * Update Tag numbers
     *
     * @param int $id
     *
     * @return Response
     */
    public function getEdit($id)
    {
        $ewe = Sheep::getById($id);

        return View::make('sheepedit')->with([
            'id'        =>$id,
            'title'     => 'Change Tags',
            'e_flock'   =>$ewe->getFlockNumber(),
            'e_tag'     =>$ewe->getSerialNumber()
        ]);
    }

    /**
     * @param $flock_old
     * @param $flock_new
     * @return mixed
     */
    public function getEditmore($flock_old,$flock_new)
    {
        $ewe = Sheep::getById($id);
        //return $ewe->id;
        return View::make('sheepeditmore')->with([
            'id'        =>$id,
            'title'     => 'Change more Tags',
            'e_flock'   =>$ewe->getFlockNumber(),
            'e_tag'     =>$ewe->getSerialNumber()
        ]);
    }

    /**
     * @return mixed
     */
    public function postSeek()
    {
        $rules = Sheep::$rules['death'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $flock_number = Input::get('e_flock');
        $serial_number    =Input::get('e_tag');
        /**@var $ewe Sheep*/
        $ewe = $this->getByEarNumbers($flock_number, $serial_number);
        //$ewe = Sheep::getByEarNumbers($flock_number,$serial_number);
        //above as 'Sheep::getByEarNumbers()'returns empty model instead of NULL ???
        if ($ewe == NULL){Session::put('find_error','Sheep not found, check numbers and re-try.');
            return Redirect::to('sheep/seek')->withInput();
        }
        if (Input::get('find')){
            return View::make('sheepedit')->with([
                'id'            =>$ewe->getId(),
                'title'         => 'Change Tags',
                'e_flock'       =>$flock_number,
                'e_tag'         =>$ewe->getSerialNumber(),
                'original_e_flock'=>$ewe->getOriginalFlockNumber(),
                'colour_flock'  => $ewe->getSupplementaryTagFlockNumber(),
            ]);
        }
        //**@var $ewe Sheep*/
        if (Input::get('view')){
            return View::make('sheepview')->with([
                'id'                =>$ewe->getId(),
                'title'             =>'Details for Sheep number ',
                'e_flock'           =>$flock_number,
                'e_tag'             =>$ewe->getSerialNumber(),
                'e_tag_1'           =>$ewe->getOldSerialNumber(),
                'e_tag_2'           =>$ewe->getOlderSerialNumber(),
                'original_e_flock'  =>$ewe->getOriginalFlockNumber(),
                'original_e_tag'    =>$ewe->getOriginalSerialNumber(),
                'colour_of_tag'     =>$ewe->getTagColour(),
                'move_on'           =>$ewe->getMoveOn(),
                'sex'               =>$ewe->getSex()
            ]);
        }
    }
    /**
     * Find sheep page
     *
     * @param none
     * @return view
     */
    public function getSeek()
    {
        return View::make('sheepfinder')->with([
            'title'     => 'Find a sheep'
        ]);
    }

    /**
     * @return mixed
     */
    public function getBatch()
    {
        return View::make('sheepbatch')->with([
            'id'=>$this->user(),
            'title' => 'Enter Batch of tags'
        ]);
    }

    /**
     * @param $home_bred
     * @return mixed
     */
    public function getAddewe($home_bred)
    {
        return View::make('sheepaddewe')
            ->with([
                'title'     => 'Add a Ewe',
                'alt_title' => 'Add a Home Bred Ewe',
                'id'        =>  $this->user(),
                'sex'       => 'female',
                'home_bred' => $home_bred
            ]);
    }

    /**
     * @param $home_bred
     * @return mixed
     */
    public function getAddtup($home_bred)
    {
        return View::make('sheepaddewe')
            ->with([
                'title'     => 'Add a Tup',
                'alt_title'     => 'Add a Home Bred Tup',
                'id'        =>  $this->user(),
                'sex'       => 'male',
                'home_bred'  => $home_bred
            ]);
    }

    /**
     * @return mixed
     */
    public function postAddewe()
    {
        $rules = Sheep::$rules['dates_and_tags'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $user_id        = $this->user();
        $home_bred      = Input::get('home_bred');
        $e_flock        = Input::get('e_flock');
        $e_flock_number = $e_flock;
        $e_tag          = Input::get('e_tag');
        $d              = Input::get('day');
        $m              = Input::get('month');
        $y              = Input::get('year');
        $colour_of_tag  = Input::get('colour_of_tag');
        $move_on        = $y.'-'.$m.'-'.$d.' '.'00:00:00';
        $sex            = Input::get('sex');
        $l              = DB::table('sheep')->where('owner',$user_id)->max('local_id');
        $sheep_exists   = Sheep::check($e_flock_number,$e_tag,$user_id);
        if (NULL === $sheep_exists) {
            $l++;
            $ewe = new Sheep();
            $ewe->setOwner($user_id);
            $ewe->setLocalId($l);
            $ewe->setFlockNumber($e_flock_number);
            $ewe->setOriginalFlockNumber($e_flock_number);
            $ewe->setSupplementaryTagFlockNumber($e_flock_number);
            $ewe->setSerialNumber($e_tag);
            $ewe->setOriginalSerialNumber($e_tag);
            $ewe->setSupplementarySerialNumber($e_tag);
            $ewe->setMoveOn($move_on);
            $ewe->setTagColour($colour_of_tag);
            $ewe->setSex($sex);
            $ewe->save();

            if($home_bred !== FALSE){
                $tag = new Homebred();
                $tag->setFlockNumber($home_bred);
                $tag->setDateApplied($move_on);
                $tag->setUserId($user_id);
                $tag->setCount(1);
                $tag->save();
            }
        }

        return Redirect::back()->withInput(Input::except('e_tag'));
    }

    /**
     * @param $sex
     * @return mixed
     */
    public function getSheepoff($sex)
    {
        return View::make('sheepoff')->with([
            'title'     => 'Single '.ucwords($sex).' Sheep Off',
            'id'        => $this->user(),
            'sex'       => $sex,
            'e_flock'   => NULL,
            'e_tag'     => NULL
        ]);
    }

    /**
     * @return mixed
     */
    public function postSheepoff()
    {
        $rules = Sheep::$rules['dates_and_tags'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $e_flock        = Input::get('e_flock');
        $e_flock_number = $e_flock;
        $e_tag          = Input::get('e_tag');
        $d              = Input::get('day');
        $m              = Input::get('month');
        $y              = Input::get('year');
        $move_off       = $y.'-'.$m.'-'.$d.' '.'00:00:00';
        $destination    = Input::get('destination');
        $sex            = Input::get('sex');

        $ewe = Sheep::firstOrNew([
            'flock_number'           =>  $e_flock_number,
            'serial_number'             =>  $e_tag,
            'owner'           =>  $this->user()
        ]);
        $ewe->setOriginalFlockNumber($e_flock_number);
        $ewe->setSupplementaryTagFlockNumber($e_flock_number);
        $ewe->setSerialNumber($e_tag);
        $ewe->setMoveOff($move_off);
        $ewe->setDestination($destination);
        $ewe->setSex($sex);
        $ewe->save();
        $ewe->delete();

        return Redirect::back()->with([
            'title'     => 'Single '.ucwords($ewe->getSex()).' Sheep Off',
            ])
            ->withInput(Input::except('e_tag'));

    }

    /**
     * @return mixed
     */
    public function getDeath()
    {
        return View::make('sheepdeath')->with([
            'title'     => 'Record a Death',
            'id'        => $this->user(),
            'sex'       =>'female',
            'e_flock'   => NULL,
            'e_tag'     => NULL
        ]);
    }

    /**
     * @param $flock_number
     * @param $serial_number
     * @param $sex
     * @return mixed
     */
    public function getDeathsearch($flock_number,$serial_number,$sex)
    {
        return View::make('sheepdeath')->with([
            'title'     => 'Record this Sheep Death (correct date?)',
            'id'        => $this->user(),
            'sex'       => $sex,
            'e_flock'   => $flock_number,
            'e_tag'     => $serial_number
        ]);
    }

    /**
     * @return mixed
     */
    public function postDeath()
    {
        $rules = Sheep::$rules['dates_and_tags'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $e_flock        = Input::get('e_flock');
        $e_tag          = Input::get('e_tag');
        $d              = Input::get('day');
        $m              = Input::get('month');
        $y              = Input::get('year');
        $move_off       = $y.'-'.$m.'-'.$d.' '.'00:00:00';
        $how_died       = ' - '.Input::get('how_died');
        $sex            = Input::get('sex');

        $ewe = Sheep::firstOrNew([
            'flock_number'    =>  $e_flock,
            'serial_number'   =>  $e_tag,
            'owner'           =>  $this->user()
        ]);
        //dd($ewe->getSerialNumber());
        $ewe->setOriginalFlockNumber($e_flock);
        $ewe->setSupplementaryTagFlockNumber($e_flock);
        $ewe->setSerialNumber($e_tag);
        $ewe->setMoveOff($move_off);
        $ewe->setDestination('died'.$how_died);
        $ewe->setSex($sex);
        $ewe->save();
        $ewe->delete();

        return View::make('sheepdeath')->with([
            'title'     => 'Record a Death',
            'id'        => $this->user(),
            'e_flock'   => NULL,
            'e_tag'     => NULL,
            'sex'       => $sex
        ]);
    }

    /**
     * @param $id
     * @param $e_flock
     * @param $e_tag
     * @param $sex
     * @return mixed
     */
    public function getEnterdeath($id,$e_flock,$e_tag,$sex)
    {
        return View::make('sheepdeath')->with([
            'title'     => 'Record a Death',
            'id'        => $id,
            'e_flock'   => $e_flock,
            'e_tag'     => $e_tag,
            'sex'       => $sex
        ]);
    }

    /**
     * @return mixed
     */
    public function getSearch()
    {
         return View::make('search')->with([
             'title'=>'Search for a Tag'
         ]);
    }

    /**
     * @return mixed
     */
    public function getReplaced()
    {
        $ewes = Sheep::replaced($this->user());

        return View::make('replacement_tags')->with([
            'ewes'=>$ewes[0],
            'count'=>$ewes[1],
            'title'=>'Tag Replacements List'
        ]);
    }

    /**
     * @return mixed
     */
    public function postSearch()
    {
        $tag = Input::get('tag');

        //$ewes = Sheep::searchByTag($this->user(),$tag);
        $ewes = Sheep::withTrashed('owner',$this->user())
            ->where('serial_number',$tag)
            ->orWhere('old_serial_number',$tag)
            ->orWhere('older_serial_number',$tag)
            ->get();
        //dd($ewes);
        if ($ewes->isEmpty()){Session::put('find_error','Sheep not found, check number and re-try.');
            return Redirect::to('sheep/search')->withInput();
        }

        return View::make('searchresult')->with([
            'ewes'=>$ewes,
            'title'=>'Search Results for Tag '.$tag
        ]);
    }

    /**
     * @return mixed
     */
    public function getNoneid()
    {
        $ewes = Sheep::total($this->user());

        return view('sheeplist')->with([
            'ewes'=>$ewes[0],
            'title'=>'All Tagged Sheep',
            'count'=>$ewes[1]
        ]);
    }

    /**
     * @return mixed
     */
    public function postDatesetter()
    {
        if (Input::get('oneyear') == "on") {
            Session::put('date_to', date('Y-m-d H:i:s', strtotime('1 december this year')));
            Session::put('date_from', date('Y-m-d H:i:s', strtotime('1 december last year')));
        } else {

        $year_from = Input::get('year');
        $month_from = Input::get('month');
        $day_from = Input::get('day');
        $year_to = Input::get('year_to');
        $month_to = Input::get('month_to');
        $day_to = Input::get('day_to');

        Session::put('date_from', Carbon::createFromDate($year_from, $month_from, $day_from, 'UTC'));
        Session::put('date_to', Carbon::createFromDate($year_to, $month_to, $day_to, 'UTC'));
        }
        return Redirect::to(URL::current())->withErrors(['date_set'=>'The date range has been set at<br> 
            between '.date('d-m-Y',strtotime(Session::get('date_from'))).'
            and '.date('d-m-Y',strtotime(Session::get('date_to'))).'<br>  click \'Finished\' to go continue']);
    }

    /**
     * @return mixed
     */
    public function getDatesetter()
    {
        return View('date_setter')->with([

            'title' => 'Select Date Range for Movement \'OFF\' Lists',
        ]);
    }

    /**
     * @param $flock_number
     * @param $serial_number
     * @return mixed
     */
    public function getByEarNumbers($flock_number, $serial_number)
    {
        $ewe = Sheep::where('owner', $this->user())
            ->where('flock_number', $flock_number)
            ->where('serial_number', $serial_number)->first();
        return $ewe;
    }
}
