<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 04/01/2017
 * Time: 19:36
 */

namespace App\Domain\Sheep;

use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use App\Models\Sheep;
use phpDocumentor\Reflection\Types\Boolean;


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
     * @var Boolean $keep_date
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
     * @var Boolean
     */
    private $alive;
    /**
     * @var Boolean
     */
    private $include_dead;
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $comparison;
    /**
     * @var
     */
    private $value;

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
     * @param int $alive
     */
    public function setAlive($alive)
    {
        $this->alive = $alive;
    }
    /**
     * @return int
     */
    public function getAlive(){
        return $this->alive;
    }

    /**
     * @param Boolean $include_dead
     */
    public function setIncludeDead($include_dead)
    {
        $this->include_dead = $include_dead;
    }
    /**
     * @return Boolean
     */
    public function getIncludeDead(){
        return $this->include_dead;
    }

    /**
     * @param int $keep_date
     */
    public function setKeepDate($keep_date)
    {
        $this->keep_date = $keep_date;
    }
    /**
     * @return int
     */
    public function getKeepDate(){
        return $this->keep_date;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }
/**
 * @return string
 */
public function getKey(){
    return $this->key;
}

    /**
     * @param string $comparison
     */
    public function setComparison($comparison)
    {
        $this->comparison = $comparison;
    }
    /**
     * @return string
     */
    public function getComparison(){
        return $this->comparison;
    }

/**
 * @return string
 */
public function getValue(){
    return $this->value;
}

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }


    /**
     * ListByDates constructor.
     * @param \DateTime $date_start
     * @param \DateTime $date_end
     * @param $keep_date
     * @param $sex
     * @param $move
     * @param $include_dead
     * @param $key
     * @param $comparison
     * @param $value
     */
    public function __construct(\DateTime $date_start, \DateTime $date_end,
                                $keep_date, $sex, $move,$include_dead,$key,$comparison,$value)
    {
        global $KEEP;
        global $END;
        global $START;
       //dd($KEEP);

        $this->setMove($move);
        $this->setSex($sex);
        $this->setKeepDate($keep_date*$this->getKeepDate());
        if($keep_date == 0)
        {
            $END = $date_end;
            $START = $date_start;
            $KEEP = 0;
        }
        else
        {
            $KEEP ++;
            $END = NULL != $END?$END:$date_end;
            $START = NULL != $START?$START:$date_start;
        }
        $this->setDateStart($START);
        $this->setDateEnd($END);
        $this->setIncludeDead($include_dead);
        $this->setKey($key);
        $this->setComparison($comparison);
        $this->setValue($value);

    }

    /**
     * @return mixed
     */
    public function makeList()
    {
        $this->setAlive(1);
        if($this->getIncludeDead() == TRUE){$this->setAlive(-1);}
        $move_type = 'move_'.$this->getMove();
        $ewes = Sheep::where('owner',$this->owner())
            ->whereDate($move_type,'>=',$this->getDateStart())
            ->whereDate($move_type,'<=',$this->getDateEnd())
            ->where($this->getKey(),$this->getComparison(),$this->getValue())
            ->where('alive','>=',$this->getAlive())
            ->paginate(20);

        return $ewes;
    }

    public function makeGroup($group_name)
    {
        $this->setAlive(1);
        if($this->getIncludeDead() == TRUE){$this->setAlive(-1);}
        $move_type = 'move_'.$this->getMove();
        $ewes = Sheep::where('owner',$this->owner())
            ->whereDate($move_type,'>=',$this->getDateStart())
            ->whereDate($move_type,'<=',$this->getDateEnd())
            ->where($this->getKey(),$this->getComparison(),$this->getValue())
            ->where('alive','>=',$this->getAlive())
            ->get();
        $group = Group::firstOrCreate(['name' => $group_name,'owner'=>$this->owner()]);
        foreach ($ewes as $ewe){
            $group->sheep()->attach($ewe->getId());
        }
        $group->save();

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