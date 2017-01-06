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
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;


class ListByDates
{
    /**
     * @var \DateTime $date_start
     */
    private $date_start;
    /**
     * @var \DateTime $date_end
     */
    private $date_end;
    /**
     * @var Boolen $keep_date
     */
    private $keep_date;
    /**
     * @var string $sex
     */
    private $sex;
    /**
     * @var string $move
     */
    private $move;
    /**
     * @var mixed
     */
    public $list;

    /*/**
     * ListByDates constructor.
     * @param \DateTime $date_start
     * @param \DateTime $date_end
     * @param $keep_date
     * @param $sex
     * @param $move
     *
     */
    /*public function __construct(\DateTime $date_start, \DateTime $date_end, $keep_date, $sex, $move)
    {
        $move = 'move_'.$move;
        $both_sexes = $sex;
        if ($sex == 'both') {$sex = '%male';$both_sexes = 'All Females and Male';}
        $query = Sheep::where('owner',$this->owner())
            ->whereDate($move,'>=',$date_start)
            ->whereDate($move,'<=',$date_end)
            ->where('sex','like',$sex);

        if ($keep_date == TRUE) {$this->list = $query->paginate(20);}
        else $this->list = $query->get();
        ($this->list);
    }*/

    /**
     * @return mixed
     */
    public function getList(){
        return $this->list;
    }


    /**
     * @param \DateTime $date_start
     * @param \DateTime $date_end
     * @param $keep_date
     * @param $sex
     * @param $move
     * @return array
     */
    public function generateList(\DateTime $date_start, \DateTime $date_end, $keep_date, $sex, $move)
    {
        $move_date = 'move_'.$move;
        $both_sexes = $sex;
        if ($sex == 'both') {$sex = '%male';$both_sexes = 'All Females and Male';}
        $query = Sheep::where('owner',$this->owner())
            ->whereDate($move_date,'>=',$date_start)
            ->whereDate($move_date,'<=',$date_end)
            ->where('sex','like',$sex);

        if ($keep_date == TRUE) {$list = $query->paginate(2000);}
        else $list = $query->paginate(2000);
        return View::make('custom_list')->with([
            'title'         => ucfirst($both_sexes).'s moved '.strtoupper($move).' - ',
            'list'          => $list,
            'date_start'    => $date_start,
            'date_end'      => $date_end,
            'keep_date'     => $keep_date

        ]);

    }

    /**
     * @param $key
     * @param $comparison
     * @param $value
     * @param $start_date
     * @param $end_date
     * @return mixed
     */
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

    /**
     * @return mixed
     */
    private function owner()
    {
        $owner = Auth::user()->id;
        return $owner;
    }

}