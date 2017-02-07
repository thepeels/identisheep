<?php

namespace App\Http\Controllers;

use App\Domain\Sheep\ListByDates;
use Illuminate\Http\Request;
use App\Models\ListDates;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Sheep;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;

class ListController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        /*if (Auth::guest()) {
            return Redirect::to('../login');
        }*/
        $this->middleware('subscribed');
    }
    /**
     * @return mixed
     */
    private function owner()
    {
        $owner = Auth::user()->id;
        return $owner;
    }

    /**
     * @return mixed
     */
    public function getCustomisation()
    {   $dates = $this->datePair();
        $check = ($dates[2] == TRUE)?TRUE:FALSE;
        return View::make('customise_list')->with([
            'title'         => 'Customise a List',
            'date_start'    => $dates[0],
            'date_end'      => $dates[1],
            'keep_date'     => $dates[2],
            'check'         => $check
        ]);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getCustomised(Request $request)
    {
        $request->flashExcept('make_group');
        $input_start = new \DateTime($request->get('year') . $request->get('month') . $request->get('day'));
        $input_end   = new \DateTime($request->get('end_year') . $request->get('end_month') . $request->get('end_day'));
        $keep_date  = $request->get('keep_date');

        $rules = Sheep::$rules['dates'];
        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation->messages());
        }

        if($keep_date == TRUE){
            $date_pair = ListDates::where(['owner' => $this->owner()])->first();
            $date_pair->setDateStart($input_start);
            $date_pair->setDateEnd($input_end);
            $date_pair->setKeepDate(TRUE);
            $date_pair->save();
        } else {
            $date_start = $input_start;
            $date_end = $input_end;
            $date_pair = ListDates::where(['owner' => $this->owner()])->first();
            $date_pair->setDateStart('2007-01-01 00:00:00');
            $date_pair->setDateEnd(date('Y-m-d H:i:s'));
            $date_pair->setkeepDate(FALSE);
            $date_pair->save();
        }
        $date_start = $input_start;
        $date_end = $input_end;
        $sex        = $request->get('sex');
        $move       = $request->get('move');
        $both_sexes = $sex;
        $include_dead      = $request->get('include_dead');

        if ($sex == 'both') {$sex = '%male';$both_sexes = 'All Females and Male';}
        $tags       = new ListByDates($date_start,$date_end,$keep_date,$sex,$move,$include_dead,'sex','like',$sex);
        $list = $tags->makeList();
        if(!$request->make_group == TRUE) {
            return View::make('custom_list')->with([
                'title' => ucfirst($both_sexes) . 's moved ' . strtoupper($move) . ' - ',
                'list' => $list->appends($request->except('page')),
                'date_start' => $date_start,
                'date_end' => $date_end,
                'keep_date' => $keep_date
            ]);
        }
        /** ToDo: introduce route rout to generate name description etc this works with fixed group name*/
        if($request->make_group == TRUE) {
            $group_list = $tags->makeGroup('Test Group');

            return View::make('custom_list')->with([
                'title' => ucfirst($both_sexes) . 's moved ' . strtoupper($move) . ' - ',
                'list' => $list->appends($request->except('page')),
                'date_start' => $date_start,
                'date_end' => $date_end,
                'keep_date' => $keep_date
            ]);
        }

    }

    private function datePair()
    {
        $date_pair  = ListDates::firstOrNew(['owner' => $this->owner()]);
        $date_pair  ->save();
        $date_start = NULL!=($date_pair->getDateStart())?$date_pair->getDateStart():'2007-01-01 00:00:00';
        $date_end   = NULL!=($date_pair->getDateEnd())?$date_pair->getDateEnd():date('Y-m-d H:i:s');
        $keep_date  = NULL!=($date_pair->getKeepDate())?$date_pair->getKeepDate():FALSE;

        return [$date_start,$date_end,$keep_date];
    }
}
