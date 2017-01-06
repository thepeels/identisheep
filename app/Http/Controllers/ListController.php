<?php

namespace App\Http\Controllers;

use App\Domain\Sheep\ListByDates;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
        $date_start = new \DateTime($request->get('year') . $request->get('month') . $request->get('day'));

        $date_end   = new \DateTime($request->get('end_year') . $request->get('end_month') . $request->get('end_day'));
        $keep_date  = $request->get('keep_date');
        $sex        = $request->get('sex');
        $move       = $request->get('move');
        $both_sexes = $sex;
        if ($sex == 'both') {$sex = '%male';$both_sexes = 'All Females and Male';}
        $tags       = new ListByDates($date_start,$date_end,$keep_date,$sex,$move);
        $list = $tags->moveOn('sex','like',$sex,$date_start,$date_end,$move);
        return View::make('custom_list')->with([
            'title'         => ucfirst($both_sexes).'s moved '.strtoupper($move).' - ',
            'list'          => $list->appends($request->except('page')),
            'date_start'    => $date_start,
            'date_end'      => $date_end,
            'keep_date'     => $keep_date

        ]);

    }

}
