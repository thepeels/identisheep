<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 04/01/2017
 * Time: 19:36
 */

namespace App\Domain\Sheep;

use Illuminate\Support\Facades\Auth;
use App\Models\Sheep;


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
     * @param \DateTime $date_start
     */
    public function setDateStart($date_start)
    {
        $this->date_start = $date_start;
    }
    /**
     * @return \DateTime
     */
    public function getDateStart(){
        return $this->date_start;
    }
    /**
     * @param \DateTime $date_end
     */
    public function setDateEnd($date_end)
    {
        $this->date_end = $date_end;
    }
    /**
     * @return \DateTime
     */
    public function getDateEnd(){
        return $this->date_end;
    }

    /**
     * @param string $sex
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }
    /**
     * @return string
     */
    public function getSex(){
        return $this->sex;
    }
    /**
     * @return string
     */
    public function getMove(){
        return $this->move;
    }

    /**
     * @param string $move
     */
    public function setMove($move)
    {
        $this->move = $move;
    }



    /**
     * ListByDates constructor.
     * @param \DateTime $date_start
     * @param \DateTime $date_end
     * @param $keep_date
     * @param $sex
     * @param $move
     *
     */
    public function __construct(\DateTime $date_start, \DateTime $date_end, $keep_date, $sex, $move)
    {
        $this->move = $this->setMove($move);
        $this->sex = $this->setSex($sex);
        $this->date_end = $this->setDateEnd($date_end);
        $this->date_start = $this->setDateStart($date_start);

    }

    /**
     * @param $key
     * @param $comparison
     * @param $value
     * @param $start_date
     * @param $end_date
     * @param $move
     * @return mixed
     */
    public function moveOn($key,$comparison,$value,$start_date,$end_date,$move)
    {//dd(Session::get('date_from'));
        $move_type = 'move_'.$move;
        $ewes = Sheep::where('owner',$this->owner())
            ->whereDate($move_type,'>=',$start_date)
            ->whereDate($move_type,'<=',$end_date)
            ->where($key,$comparison,$value)
            ->paginate(20);

        return $ewes;
    }

    /**
     * @return int
     */
    private function owner()
    {
        $owner = Auth::user()->id;

        return $owner;
    }

}