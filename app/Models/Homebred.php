<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    return $this->user_id;
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
 * @return string
 */
public function getDateApplied()
{
    return $this->date_applied;
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
    return $this->e_flock;
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
        ->where('date_applied','<',$date_to)
        ->orderBy('date_applied')
        ->get();
}
public function scopeNumbers($query,$id)
{
    return $query->where('user_id',$id)->orderBy('date_applied')->get();
}
public function scopeHowmany($query, $id)
{
    return $query->where('user_id',$id)->sum('count');
}
}
