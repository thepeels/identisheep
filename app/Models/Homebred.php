<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class Homebred extends Model {

   /**
 * @var int
 */
protected $user_id;

/**
 * @var int
 */
protected $count;

/**
 * @var string
 */
protected $date_applied;

/**
 * @var int
 */
protected $e_flock;
/**
 * The database table used by the model.
 *
 * @var string
 */
protected $table = 'homebreds';

/**
 * The database table used by the model.
 *
 * @var array
 */
protected $fillable = [
    'user_id',
    'count',
    'date_applied',
    'e_flock'
];

/**
 * @return int
 */
public function getUserId()
{
    return $this->attributes['user_id'];
}

/**
 * @param int $user_id
 */
public function setUserId($user_id)
{
    $this->attributes['user_id'] = $user_id;
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
 * @return string
 */
public function getDateApplied()
{
    return $this->attributes['date_applied'];
}

/**
 * @param string $date_applied
 */
public function setDateApplied($date_applied)
{
    $this->attributes['date_applied'] = $date_applied;
}

/**
 * @return int
 */
public function getFlockNumber()
{
    return $this->attributes['e_flock'];
}

/**
 * @param int $e_flock
 */
public function setFlockNumber($e_flock)
{
    $this->attributes['e_flock'] = $e_flock;
}
public function scopeCountByDate($query,$id,$date_from,$date_to)
{
    return $query->where('user_id',$id)
        ->where('date_applied','>=',$date_from)
        ->where('date_applied','<',$date_to)
        ->orderBy('date_applied')
        ->sum('count');
}
public function scopeListByDate($query,$id,$date_from,$date_to)
{
    return $query->where('user_id',$id)
        ->where('date_applied','>=',$date_from)
        ->where('date_applied','<=',$date_to)
        ->orderBy('date_applied')
        ->get();
}
public function scopeNumbers($query,$id)
{
    $dates = $this->dateRange();
        return $query->where('user_id',$id)
        ->where('date_applied','>=',$dates[0])
        ->where('date_applied','<=',$dates[1])
        ->orderBy('date_applied')->get();
}
public function scopeHowmany($query, $id)
{
    $dates = $this->dateRange();
    return $query->where('user_id',$id)
        ->where('date_applied','>=',$dates[0])
        ->where('date_applied','<=',$dates[1])
        ->sum('count');
}
    private function dateRange()
    {
        $date_from = Null !=(Session::get('date_from'))?Session::get('date_from'):Carbon::now()->subYears(10);
        $date_to = Null !=  (Session::get('date_to'))?Session::get('date_to'):Carbon::now();
        return [$date_from,$date_to];
    }
}
