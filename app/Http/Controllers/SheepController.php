<?php

namespace App\Http\Controllers;

use App\Domain\Sheep\Sex;
use App\Domain\Sheep\TagNumber;
use App\Domain\Sheep\TagReplacementService;
use App\Models\Homebred;
use App\Models\Sheep;
use App\Domain\Sheep\SheepOffService;
use App\Domain\Sheep\SheepOnService;
use App\Domain\Sheep\HomeBredService;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Domain\Sheep\ListByDates;
use Illuminate\Http\Request;

class SheepController extends Controller
{
    /**
     * SheepController constructor.
     *
     * cause to be Auth filtered before use
     */
    public function __construct()
    {
        $this->middleware('auth');
        if (Auth::guest()) {
            return Redirect::to('../login');
        }
        $this->middleware('subscribed');
        $this->home_flock = Auth::user()->getFlock();
    }
    /**todo: secondary tag needs to be complete in editing options or probably excluded */
    /**
     * Index
     * @return mixed
     */
    public function getIndex()
    {
        return Redirect::to('sheep/ewes/screen');
    }

    /**
     * User
     * @return mixed
     */
    private static function user()
    {
        return Auth::user()->id;
    }

    /**
     * Owner
     * @return mixed
     */
    private static function owner()
    {
        return Auth::user()->id;
    }

    /**
     * get Ewes
     * @param string $print
     * @return mixed
     */
    public function getEwes($print = 'screen')
    {
        if ($print == 'print') {
            $ewes = Sheep::stockPrint($this->user(), 'female');
        } else {
            $ewes = Sheep::stock($this->user(), 'female');
        }
        $ewes = $this->decodeDates($ewes);
        return view('sheeplist')->with([
            'ewes' => $ewes,
            'title' => 'All Female Sheep',

        ]);
    }

    /**
     * get Tups
     * @param string $print
     * @return mixed
     */
    public function getTups($print = 'screen')
    {
        if ($print == 'print') {
            $ewes = Sheep::stockPrint($this->user(), 'male');
        } else {
            $ewes = Sheep::stock($this->user(), 'male');
        }
        $ewes = $this->decodeDates($ewes);
        return view('sheeplist')->with([
            'ewes' => $ewes,
            'title' => 'All Tups ',
        ]);
    }

    /**
     * get Off List
     * @param $print
     * @return mixed
     */
    public function getOfflist($print)
    {
        if ($print == 'print') {
            $ewes = Sheep::offListPrint($this->user());
        } else {
            $ewes = Sheep::offList($this->user());
        }
        $ewes = $this->decodeDates($ewes);
        return view('sheeplist')->with([
            'ewes' => $ewes,
            'title' => 'Sheep Moved Off'
        ]);
    }

    /**
     * get Dead List
     * @param $print
     * @return mixed
     */
    public function getDeadlist($print)
    {
        if ($print == 'print') {
            $ewes = Sheep::deadPrint($this->user());
        } else {
            $ewes = Sheep::dead($this->user());
        }
        $ewes = $this->decodeDates($ewes);
        return view('sheeplist')->with([
            'ewes' => $ewes,
            'title' => 'Dead List'
        ]);
    }

    /**
     * show Form for delete Old records
     * @return mixed
     */
    public function getDelete()
    {
        $year = date('Y', strtotime('-10 years'));

        return View::make('delete_old')->with([
            'title' => 'Delete old Records',
            'year' => $year

        ]);
    }

    /**
     * Delete Old Records
     * @return mixed
     */
    public function postDelete()
    {
        $rules = Sheep::$rules['old_dates'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $year = Input::get('year');
        $date = Carbon::create($year, 11, 30, 23, 59, 59, 'UTC');
        $date = $date->toDateTimeString();
        /*$to_delete = Sheep::where('owner',$this->user())->where('move_on','<=',$date)->get();
        foreach($to_delete as $sheep) {
            $sheep->setDeletedAt(Carbon::now());
            $sheep->save();
        }*/
        Sheep::removeOld($this->user(), $date);

        return Redirect::to('sheep/ewes/screen');
    }

    /**
     * post Change Tags
     * @return mixed
     */
    public function postChangetags()
    {
        $rules = Sheep::$rules['death'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $tagNumber = new TagNumber('UK0' . Input::get('e_flock') . sprintf('%05d',Input::get('e_tag')));
        $new_number_exists = Sheep::check($tagNumber->getFlockNumber(), $tagNumber->getSerialNumber(), $this->owner());
        if (Null != $new_number_exists) {
            Session::flash('alert-class', 'alert-danger');
            Session::flash('message', 'The New Tag Number is already in use on another sheep,
                                                        You cannot have duplicates');
            return Redirect::back()->withErrors('This is a duplicate!')->withInput();
        }

        $id = Input::get('id');
        $old_e_flock = Input::get('old_e_flock');
        $ewe = Sheep::where('id', $id)->first();

        if ($ewe->serial_number != Input::get('e_tag')) {
            $ewe->older_serial_number = $ewe->old_serial_number;
            $ewe->old_serial_number = $ewe->serial_number;
            $ewe->serial_number = Input::get('e_tag');
        }
        $ewe->flock_number = Input::get('e_flock');
        $ewe->save();
        Session::flash('message', 'Tags sucessfully changed to ' . $tagNumber->getShortTagNumber());
        return View::make('sheepfinder')->with([
            'title' => 'Find another sheep to edit?',
            'e_flock' => $old_e_flock]);
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
        //dd($id);

        return View::make('sheepedit')->with([
            'id' => $id,
            'title' => 'Change Tags',
            'e_flock' => $ewe->getFlockNumber(),
            'e_tag' => $ewe->getSerialNumber()
        ]);
    }

    /**
     * possible Complete edit facility - Admin only?
     * @param $flock_old
     * @param $flock_new
     * @return mixed
     */
    public function getEditmore($flock_old, $flock_new)
    {
        /*$ewe = Sheep::getById($id);
        //return $ewe->id;
        return View::make('sheepeditmore')->with([
            'id'        =>$id,
            'title'     => 'Change more Tags',
            'e_flock'   =>$ewe->getFlockNumber(),
            'e_tag'     =>$ewe->getSerialNumber()
        ]);*/
    }

    /**
     * Seek by Full Tag number
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
        $serial_number = Input::get('e_tag');
        /**@var $ewe Sheep */
        $ewe = $this->getByEarNumbers($flock_number, $serial_number);
        if ($ewe == NULL) {
            Session::put('find_error', 'Sheep not found, check numbers and re-try.');
            return Redirect::to('sheep/seek')->withInput();
        }
        if (Input::get('find')) {
            return View::make('sheepedit')->with([
                'id' => $ewe->getId(),
                'title' => 'Change Tags',
                'e_flock' => $flock_number,
                'e_tag' => $ewe->getSerialNumber(),
                'original_e_flock' => $ewe->getOriginalFlockNumber(),
                'colour_flock' => $ewe->getSupplementaryTagFlockNumber(),
            ]);
        }
        if (Input::get('view')) {
            return View::make('sheepview')->with([
                'id' => $ewe->getId(),
                'title' => 'Details for Sheep number ',
                'e_flock' => $flock_number,
                'e_tag' => $ewe->getSerialNumber(),
                'e_tag_1' => $ewe->getOldSerialNumber(),
                'e_tag_2' => $ewe->getOlderSerialNumber(),
                'original_e_flock' => $ewe->getOriginalFlockNumber(),
                'original_e_tag' => $ewe->getOriginalSerialNumber(),
                'colour_of_tag' => $ewe->getTagColour(),
                'move_on' => $ewe->getMoveOn(),
                'sex' => $ewe->getSex()
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
            'title' => 'Find a sheep'
        ]);
    }

    /**
     * @return mixed
     */
    public function getBatch()
    {
        return View::make('sheepbatch')->with([
            'id' => $this->user(),
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
                'title' => 'Add a Ewe',
                'alt_title' => 'Add a Home Bred Ewe',
                'id' => $this->user(),
                'sex' => 'female',
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
                'title' => 'Add a Tup',
                'alt_title' => 'Add a Home Bred Tup',
                'id' => $this->user(),
                'sex' => 'male',
                'home_bred' => $home_bred
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
        $owner = $this->user();
        $home_bred      = Input::get('home_bred');
        $colour_of_tag  = Input::get('colour_of_tag');
        $sex            = new Sex(Input::get('sex'));
        $tagNumber      = new TagNumber('UK0' . Input::get('e_flock') . sprintf('%05d',Input::get('e_tag')));
        $move_on        = new \DateTime(Input::get('year') . '-' . Input::get('month') . '-' . Input::get('day'));
        $local_index    = DB::table('sheep')->where('owner', $owner)->max('local_id');
        $count          = 0;
        $start          = Input::get('e_tag');
        //dd($home_bred);
        if($home_bred != ""){$source = 'Home-bred';} //$home_bred is boolean false => "" ??!
        else{$source         = Input::get('source')?:'';}
        //dd($source);
        $sheep_exists   = Sheep::check($tagNumber->getFlockNumber(), $tagNumber->getSerialNumber(), $owner);
        //dd($sheep_exists);
        if (!$sheep_exists) {
            $local_index++;
            $count = 1;
            $sheep_service = new SheepOnService();
            $sheep_service->movementOn($tagNumber, $move_on, $colour_of_tag, $sex, $owner, $local_index, $source);

            if ($home_bred != FALSE) {
                $home_bred_number = $tagNumber;
                $sheep_service->homeBredOn($home_bred_number, $move_on, $count, $owner,$start);
            }
        }
        if ($count != 1) {
            Session::flash('message', 'Sheep already exists ' . $count . ' sheep added.');
        } else {
            Session::flash('message', 'Success ' . $count . ' sheep ' . $tagNumber->getShortTagNumber() . ' added.');
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
            'title' => 'Single ' . ucwords($sex) . ' Sheep Off',
            'id' => $this->user(),
            'sex' => $sex,
            'e_flock' => NULL,
            'e_tag' => NULL
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
        $tagNumber = new TagNumber('UK0' . Input::get('e_flock') . sprintf('%05d',Input::get('e_tag')));
        $dateOfMovement = new \DateTime(Input::get('year') . '-' . Input::get('month') . '-' . Input::get('day'));
        $destination = Input::get('destination');
        $sex = new Sex(Input::get('sex'));
        $owner = $this->user();

        $sheepService = new SheepOffService();
        $sheepService->recordMovement($tagNumber, $dateOfMovement, $destination, $sex, $owner);

        Session::flash('message', 'Sheep - Tag No. ' . $tagNumber->getCountryCode() . ' ' .
            $tagNumber->getFlockNumber() . ' ' . sprintf('%05d', $tagNumber->getSerialNumber()) . ' moved off.');

        return Redirect::to('sheep/sheepoff/' . $sex);
    }

    /**
     * @return mixed
     */
    public function getDeath()
    {
        return View::make('sheepdeath')->with([
            'title'     => 'Record a Death',
            'id'        => $this->user(),
            'sex'       => 'female',
            'f'        => 1,
            'm'        => 0,
            'e_flock'   => NULL,
            'e_tag'     => NULL,
            'disposal'  => Auth::user()->getDisposal(),
        ]);
    }

    /**
     * @param $flock_number
     * @param $serial_number
     * @param $sex
     * @return mixed
     */
    public function getDeathsearch($flock_number, $serial_number, $sex)
    { //dd($sex);
        return View::make('sheepdeath')->with([
            'title' => 'Record this Sheep Death (correct date?)',
            'id' => $this->user(),
            'sex' => $sex,
            'e_flock' => $flock_number,
            'e_tag' => $serial_number,
            'disposal' => Auth::user()->disposal
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
        $tagNumber      = new TagNumber('UK0' . Input::get('e_flock') . sprintf('%05d',Input::get('e_tag')));
        $dateOfDeath    = new \DateTime(Input::get('year') . '-' . Input::get('month') . '-' . Input::get('day'));
        $reason         = ' - ' . Input::get('how_died');
        $disposal       = Input::get('disposal');
        $sex            = new Sex(Input::get('sex'));
        $owner          = $this->user();


        $sheepService = new SheepOffService();
        $sheepService->recordDeath($tagNumber, $dateOfDeath, $reason, $sex, $owner, $disposal);

        Session::flash('message', 'Death of ' . $tagNumber->__toString() . ' recorded');

        return Redirect::to('sheep/death');
    }

    /**
     * @param $id
     * @param $e_flock
     * @param $e_tag
     * @param $sex
     * @return mixed
     */
    public function getEnterdeath($id, $e_flock, $e_tag, $sex)
    {
        return View::make('sheepdeath')->with([
            'title' => 'Record the Death of this Sheep',
            'id' => $id,
            'e_flock' => $e_flock,
            'e_tag' => $e_tag,
            'sex' => $sex
        ]);
    }

    /**
     * @return mixed
     */
    public function getReplaced()
    {
        $ewes = Sheep::replaced($this->user());

        return View::make('replacement_tags')->with([
            'ewes' => $ewes,
            //'count' => $ewes[1],
            'title' => 'Tag Replacements List'
        ]);
    }

    /**
     * @return mixed
     */
    public function getReplaceATag()
    {
        return View::make('replace_a_tag')->with([
            'title'     =>  'Replace a tag on a New or an already recorded Sheep',
            'sex'       =>  'Female'
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function postReplaceTag(Request $request)
    {
        $rules1= Sheep::$rules['dates_and_tags'];
        $rules2= Sheep::$rules['replacement'];
        $validation = Validator::make($request->all(),$rules1 + $rules2);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }
        $replacement = New TagReplacementService;
        $replacement->handler($request->e_flock,sprintf('%05d',$request->e_tag),$request->original_flock,
            sprintf('%05d',$request->original_tag),$request->year,$request->month,$request->day,$request->sex);

        return View::make('replace_a_tag')->with([
            'title' => 'Replace  another tag on a New or an already recorded Sheep',
            'sex' => $request->sex
        ]);
    }

    /**
     * @return mixed
     */
    public function getSearch()
    {
        return View::make('search')->with([
            'title' => 'Search for a Tag'
        ]);
    }
    /**
     * @return mixed
     */
    public function postSearch()
    {
        $tag = Input::get('tag');
        $rules = Sheep::$rules['search'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }

        $ewes = Sheep::searchByTag($this->user(), $tag);
        if ($ewes->isEmpty()) {
            Session::put('find_error', 'Sheep not found, check number and re-try.');
        return Redirect::to('sheep/search')->withInput();
        }
        $full_edit = 'no';
        if (Auth::user()->id == 1)$full_edit = 'yes';
        $ewes = $this->decodeDates($ewes);

        return View::make('searchresult',compact('ewes'))->with([
            'title'     => 'Search Results for Tag ' . $tag,
            'full_edit' => $full_edit
        ]);
    }

    public function getSelect($id)
    {
        $ewe = (Sheep::where('id', $id)->first());
        $date_on = (date('Y',strtotime($ewe->move_on))==config('app.base_date')?"":date('Y-m',strtotime($ewe->move_on)));
        $date_off = (date('Y',strtotime($ewe->move_off))==config('app.base_date')?"":date('Y-m-d',strtotime($ewe->move_off)));
        return View::make('selectedsheep')->with([
            'ewe'       => $ewe,
            'title'     => 'Sheep selected for Editing',
            'date_on'   => $date_on,
            'date_off'  => $date_off
        ]);

    }

    /**
     * @return mixed
     */
    public function getNoneid()
    {
        $ewes = Sheep::total($this->user());

        return View::make('sheeplist')->with([
            'ewes' => $ewes[0],
            'title' => 'All Tagged Sheep',
            'count' => $ewes[1]
        ]);
    }

    /**
     * @return mixed
     */
    public function postDateSetter()
    {
        if (Input::get('thisyear') == "on") {
            Session::put('date_to', date('Y-m-d H:i:s', strtotime('1 december this year')));
            Session::put('date_from', date('Y-m-d H:i:s', strtotime('1 december last year')));
        } elseif (Input::get('lastyear') == "on") {
            Session::put('date_to', date('Y-m-d H:i:s', strtotime('1 december last year')));
            Session::put('date_from', date('Y-m-d H:i:s', strtotime('1 year ago', strtotime('1 december last year'))));
        } else {

            $year_from = Input::get('year');
            $month_from = Input::get('month');
            $day_from = Input::get('day');
            $year_to = Input::get('year_to');
            $month_to = Input::get('month_to');
            $day_to = Input::get('day_to');

            Session::put('date_from', date('Y-m-d H:i:s', strtotime(Carbon::createFromDate($year_from, $month_from, $day_from, 'UTC'))));
            Session::put('date_to', date('Y-m-d H:i:s', strtotime(Carbon::createFromDate($year_to, $month_to, $day_to, 'UTC')->addDay())));
        }
        /*Session::flash('message','The date range between ' . date('d-m-Y', strtotime(Session::get('date_from'))) . '
            and ' . date('d-m-Y', strtotime(Session::get('date_to'))).'. Click \'Finished\' to continue.');*/
        Session::flash('message', 'The date range between ' . date('d-m-Y', strtotime(Session::get('date_from'))) . '
            and ' . date('d-m-Y', strtotime(Session::get('date_to'))) . '');

        return Redirect::to('sheep/date-setter');
    }

    /**
     * @return mixed
     */
    public function getDateSetter()
    {
        return View::make('date_setter')->with([

            'title' => 'Change the Date Range for many of the standard lists',
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

    /**
     * @param $id
     * @return string
     */
    public function getFlock($id)
    {
        $flock_number = 0 != User::where('id', $id)->getFlock() ? User::where('id', $id)->getFlock() : 'false';
        return $flock_number;
    }

    /**
     * @return mixed
     */
    /** ToDo:: maybe make this work probably not necessary, removed from menu...
     * actually operates through the customise list route */
    public function getEwesListByDate()
    {
        $key = 'sex';
        $value = '%Male';
        $comparison = 'like';
        $list = new ListByDates($key, $comparison, $value, Session::get('date_from'), Session::get('date_to'));
        $ewes = $list->moveOn();
        return View::make('sheeplist')->with([
            'ewes' => $ewes,
            'title' => 'Ewes list by Dates - ',
            'count' => 'count'
        ]);
    }

    public function getCustomiseList()
    {
        return View::make('customise_list')->with([
            'title' => 'Customise a List'
        ]);
    }
    public function decodeDates($ewes)
    {
        foreach ($ewes as $ewe) {
            $ewe->date_on = (date('Y', strtotime($ewe->move_on)) == config('app.base_date') ? "" : date('Y-m', strtotime($ewe->move_on)));
            $ewe->date_off = (date('Y', strtotime($ewe->move_off)) == config('app.base_date') ? "" : date('Y-m-d', strtotime($ewe->move_off)));
        }
        return $ewes;
    }
}
