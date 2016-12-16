<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Single extends Model {

    /**
     * @var int
     */
    protected $user_id;
    /**
     * @var int
     */
    protected $flock_number;
    /**
     * @var int
     */
    protected $count;
    /**
     * @var string
     */
    protected $destination;

    /**
     * @var \DateTime
     */
    protected $date_applied;
    /**
     * @var string
     */
    protected $table = 'singles';
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'count',
        'flock_number',
        'destination',
        'date_applied'
    ];
    /**
     * @return int
     */
    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->attributes['user_id']= $user_id;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->attributes['count'] = $count;
    }

    /**
     * @return int
     */
    public function getFlockNumber()
    {
        return $this->flock_number;
    }

    /**
     * @param int $flock_number
     */
    public function setFlockNumber($flock_number)
    {
        $this->attributes['flock_number'] = $flock_number;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param string $destination
     */
    public function setDestination($destination)
    {
        $this->attributes['destination'] = $destination;
    }

    /**
     * @return string
     */
    public function getDateApplied()
    {
        return $this->date_applied;
    }

    /**
     * @param \DateTime $date_applied
     */
    public function setDateApplied($date_applied)
    {
        $this->attributes['date_applied'] = $date_applied;
    }
    /**
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeView($query,$id)
    {
        return $query->where('user_id',$id)->paginate(20);
    }

}
