<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

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
     * @var 
     */
    protected $start;
    /**
     * @var 
     */
    protected $finish;
    
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'count',
        'flock_number',
        'destination',
        'date_applied',
        'start',
        'finish'
    ];
    /**
     * @return int
     */
    /**
     * @return int
     */
    public function getUserId(){
        return $this->attributes['user_id'];
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
        return $this->attributes['count'];
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
        return $this->attributes['flock_number'];
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
        return $this->attributes['destination'];
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
        return $this->attributes['date_applied'];
    }

    /**
     * @param \DateTime $date_applied
     */
    public function setDateApplied($date_applied)
    {
        $this->attributes['date_applied'] = $date_applied;
    }

    /**
     * @param int $start
     */
    public function setStart($start)
    {
        $this->attributes['start'] = $start;
    }

    /**
     * @return int
     */
    public function getStart()
    {
        return $this->attributes['start'];
    }

    /**
     * @param int $finish
     */
    public function setFinish($finish)
    {
        $this->attributes['finish'] = $finish;
    }

    /**
     * @return int
     */
    public function getFinish()
    {
        return $this->attributes['finish'];
    }

    /**
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeView($query,$id)
    {
        $dates = $this->dateRange();
        return $query->where('user_id',$id)
            ->where('date_applied','>=',$dates[0])
            ->where('date_applied','<=',$dates[1])
            ->orderBy('date_applied','DESC')
            ->paginate(20);
    }
    private function dateRange()
    {
        $date_from = Null !=(Session::get('date_from'))?Session::get('date_from'):Carbon::now()->subYears(10);
        $date_to = Null !=  (Session::get('date_to'))?Session::get('date_to'):Carbon::now();
        return [$date_from,$date_to];
    }
}
