<?php
/**
 * Sheep Class
 * User: John
 * Date: 23/11/2016
 * Time: 12:59
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Redirect,Auth,Collection,DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\Paginator;


class Sheep extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sheep';

    /**
     * The database table used by the model.
     *
     * @var array
     */
    protected $fillable = [
        'local_id',
        'user_id',
        'move_on',
        'move_off',
        'off_how',
        'e_flock',
        'original_e_flock',
        'colour_flock',
        'e_tag',
        'e_tag_1',
        'e_tag_2',
        'original_e_tag',
        'colour_tag',
        'colour_tag_1',
        'colour_tag_2',
        'sex',
        'colour_of_tag'
    ];
    protected $dates = ['deleted_at'];

    /**
     * @param integer
     *
     * @return array
     *
     */
    public function user()
    {
        return $this->belongsTo('User');
    }
    private function userId()
    {
        return Auth::user()->id;
    }

    public function details($id)
    {
        $details = $this->where('id', $id);
        return $details;
    }

    /*public static function getById($id)
    {
        $ewe = Sheep::where('id', $id)->first();
        return $ewe;
    }*/
    /*public function scopeGetById($ewe,$id)
    {
        $ewe = Sheep::withTrashed('id', $id)->first();
        return $ewe;
    }*/
    public function scopeGetById($query,$id)
    {
        return  $query->where('id',$id)->first();
    }

    public static function getByTag($flock, $tag)
    {
        try {
            $ewe = Sheep::where('e_flock', $flock)
                ->where('e_tag', $tag)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return (NULL);
        }

        return $ewe;
    }
    public static function check($flock,$tag,$id)
    {
        $ewe = Sheep::withTrashed()
            ->where('user_id',$id)
            ->where('e_flock',$flock)
            ->where('e_tag',$tag)
            ->first();
        return (NULL !== $ewe?:NULL);
    }

    public static $rules = [
        'dates_and_tags' => [
            'day'       => 'digits:2|required|min:1|max:31',
            'month'     => 'digits:2|required|min:1|max:12',
            'year'      => 'integer|required|min:2006|max:2025',
            'e_flock'   => 'digits:6|required',
            'e_tag'     => 'numeric|required|between:1,99999',
        ],
        'batch'=> [
            'day'       => 'digits:2|required|min:1|max:31',
            'month'     => 'digits:2|required|min:1|max:12',
            'year'      => 'integer|required|min:2006|max:2025',
            'flock_number' => 'digits:6|required',
            'start_tag' => 'digits_between:1,5|required',
            'end_tag'   => 'digits_between:1,5|required'
        ],
        'dates'=>[
            'day'       => 'digits:2|required|min:1|max:31',
            'month'     => 'digits:2|required|min:1|max:12',
            'year'      => 'integer|required|between:2006,2025'
        ],
        'old_dates'=>[
            'year'      => 'integer|required|between:2006,2025'
        ],

        'where_to'=>[
            'destination'=>'required'
        ],
        'single'=>[
            'count'=>'integer|required',
            'flock_number'=>'digits:6|required',
        ]

    ];
    public static function replaced()
    {
        $user = Auth::user()->id;
        $ewes = self::where('user_id',$user)
            ->whereRaw('`e_tag`!=`original_e_tag`')
            ->orderBy('updated_at')
            ->paginate(20);
        $count = self::where('user_id',$user)->whereRaw('`e_tag`!=`original_e_tag`')->count();

        return [$ewes,$count];
    }
    public function scopeSearchByTag($query,$id,$tag)
    {
        return $query->withTrashed('user_id',$id)
            ->where('e_tag',$tag )
            ->orWhere('e_tag_1',$tag )
            ->orWhere('e_tag_2',$tag )
            ->paginate(20);
    }
}