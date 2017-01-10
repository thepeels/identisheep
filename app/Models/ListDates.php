<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListDates extends Model
{
    /**
     * @var 
     */
    protected $id;

    protected $table = 'listdates';

    protected $fillable = [
        'owner',
        'date_start',
        'date_end'
    ];
    /**
     * @var 
     */
    protected $date_start;
    /**
     * @var 
     */
    protected $date_end;
    /**
     * @var 
     */
    protected $owner;
    /**
     * @var
     */
    protected $keep_date;


    
    
    /**
     * @param int $owner
     */
    public function setOwner($owner)
    {
        $this->attributes['owner'] = $owner;
    }
    /**
     * @return int
     */
    public function getOwner(){
        return $this->attributes['owner'];
    }

    /**
     * @param \DateTime $date_start
     */
    public function setDateStart($date_start)
    {
        $this->attributes['date_start'] = $date_start;
    }

    /**
     * @param \DateTime $date_end
     */
    public function setDateEnd($date_end)
    {
        $this->attributes['date_end'] = $date_end;
    }
    /**
     * @return \DateTime
     */
    public function getDateStart(){
        $date = isset($this->attributes['date_start'])?$this->attributes['date_start']:NULL;
        //return $this->attributes['date_start'];
        return $date;
    }
    /**
     * @return \DateTime
     */
    public function getDateEnd(){
        $date = isset($this->attributes['date_end'])?$this->attributes['date_end']:NULL;
        //return $this->attributes['date_end'];
        return $date;
    }

    /**
     * @param boolean $keep_date
     */
    public function setKeepDate($keep_date)
    {
        $this->attributes['keep_date'] = $keep_date;
    }
/**
 * @return boolean
 */
public function getKeepDate(){
    $flag = isset($this->attributes['keep_date'])?$this->attributes['keep_date']:NULL;
    //return $this->attributes['keep_date'];
    return $flag;
}


    public function getCreate($owner)
    {
        $this->setDateStart(NULL);
        $this->setDateEnd(NULL);
        $this->setOwner($owner);
        $this->save();
    }

}
