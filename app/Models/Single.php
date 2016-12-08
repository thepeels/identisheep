<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Single extends Model {

	protected $table = 'singles';

    protected $fillable = [
        'user_id',
        'count',
        'flock_number',
        'destination',
        'date-applied'
    ];
    public static function view()
    {
        $batches = self::where('user_id' ,self::user())->paginate(20);
        return $batches;
    }
    public static function user()
    {
        $user = Auth::user()->id;
        return $user;
    }
}
