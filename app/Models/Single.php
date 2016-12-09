<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Single extends Model {

	protected $table = 'singles';

    protected $fillable = [
        'user_id',
        'count',
        'flock_number',
        'destination',
        'date-applied'
    ];
    public function scopeView($query,$id)
    {
        return $query->where('user_id',$id)->paginate(20);
    }
}
