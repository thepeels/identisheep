<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 04/01/2017
 * Time: 19:36
 */

namespace App\Domain\Sheep;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Sheep;
use Illuminate\Support\Facades\Session;


class ListByDates
{
    public function moveOn($key,$comparison,$value,$start_date,$end_date)
    {//dd(Session::get('date_from'));
        $ewes = Sheep::where('owner',$this->owner())
            ->whereDate('move_on','>=',$start_date)
            ->whereDate('move_on','<=',$end_date)
            ->where($key,$comparison,$value)
            ->paginate(20);
        //dd($end_date);
        return $ewes;
    }

    private function owner()
    {
        $owner = Auth::user()->id;
        return $owner;
    }
}