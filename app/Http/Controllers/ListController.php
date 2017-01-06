<?php

namespace App\Http\Controllers;

use App\Domain\Sheep\ListByDates;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ListController extends Controller
{

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
    {
        return View::make('customise_list')->with([
            'title' => 'Customise a List'
        ]);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getCustomised(Request $request)
    {
        $input_start = new \DateTime($request->get('year') . $request->get('month') . $request->get('day'));

        $input_end   = new \DateTime($request->get('end_year') . $request->get('end_month') . $request->get('end_day'));
        $keep_date  = $request->get('keep_date');
        /*if(NULL != $keep_date){
            Session::keep('custom_date_start');
            Session::keep('custom_date_end');
            $date_start = Session::has('custom_date_start')?Session::get('custom_date_start'):$input_start;
            $date_end = Session::has('custom_date_end')?Session::get('custom_date_end'):$input_end;
            Session::put('custom_date_start',$input_start);
            Session::put('custom_date_end'  ,$input_end);
        } else {
            Session::pull('custom_date_start');
            Session::pull('custom_date_end');*/
            $date_start = $input_start;
            $date_end = $input_end;
        //}
        if(NULL == $keep_date){$keep_date = 0;}
        $sex        = $request->get('sex');
        $move       = $request->get('move');
        $both_sexes = $sex;
        $include_dead      = $request->get('include_dead');
        //dd($alive);
        if ($sex == 'both') {$sex = '%male';$both_sexes = 'All Females and Male';}
        $tags       = new ListByDates($date_start,$date_end,$keep_date,$sex,$move,$include_dead,'sex','like',$sex);
        $list = $tags->makeList();
        return View::make('custom_list')->with([
            'title'         => ucfirst($both_sexes).'s moved '.strtoupper($move).' - ',
            'list'          => $list->appends($request->except('page')),
            'date_start'    => $date_start,
            'date_end'      => $date_end,
            'keep_date'     => $keep_date

        ]);

    }

}
