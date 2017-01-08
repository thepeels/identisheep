<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listdates extends Model
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
        return $this->attributes['date_start'];
    }
    /**
     * @return \DateTime
     */
    public function getDateEnd(){
        return $this->attributes['date_end'];
    }


}
