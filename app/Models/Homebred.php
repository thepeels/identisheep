<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homebred extends Model {

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
    ];
    public function scopeCountByDate($query,$id,$date_from,$date_to)
    {
        return $query->where('user_id',$id)
            ->where('date_applied','>=',$date_from)
            ->where('date_applied','<',$date_to)
            ->sum('count');
    }
    public function scopeListByDate($query,$id,$date_from,$date_to)
    {
        return $query->where('user_id',$id)
            ->where('date_applied','>=',$date_from)
            ->where('date_applied','<',$date_to)
            ->get();
    }
    public function scopeNumbers($query,$id)
    {
        return $query->where('user_id',$id)->get();
    }
    public function scopeHowmany($query, $id)
    {
        return $query->where('user_id',$id)->sum('count');
    }
}
