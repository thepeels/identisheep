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
     * @var int
     */
    protected $user_id;
    /**
     * @var int
     */
    protected $e_flock;
    /**
     * @var int
     */
    protected $original_e_flock;
    /**
     * @var int
     */
    protected $local_id;
    /**
     * @var string
     */
    protected $colour_of_tag;
    /**
     * @var string
     */
    protected $move_on;
    /**
     * @var string
     */
    protected $move_off;
    /**
     * @var string
     */
    protected $off_how;
    /**
     * @var int
     */
    protected $e_tag;
    /**
     * @var int
     */
    protected $e_tag_1;
    /**
     * @var int
     */
    protected $e_tag_2;
    /**
     * @var string
     */
    protected $colour_flock;
    /**
     * @var string
     */
    protected $sex;
    
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
    public function getLocalId()
    {
        return $this->local_id;
    }

    /**
     * @param int $local_id
     */
    public function setLocalId($local_id)
    {
        $this->attributes['local_id'] = $local_id;
    }

    /**
     * @return int
     */
    public function getElectronicFlockNumber()
    {
        return $this->e_flock;
    }

    /**
     * @param int $flock_number
     */
    public function setElectronicFlockNumber($flock_number)
    {
        $this->attributes['e_flock'] = $flock_number;

    }

    /**
     * @param int $flock_number
     */
    public function setOriginalElectronicFlockNumber($flock_number)
    {
        $this->attributes['original_e_flock'] = $flock_number;
    }


    /**
     * @return int
     */
    public function getOriginalElectronicFlockNumber()
    {
        return $this->original_e_flock;
    }
    /**
     * @return int
     */
    public function getTagNumber()
    {
        return $this->e_tag;
    }

    /**
     * @param int $tag_number
     */
    public function setTagNumber($tag_number)
    {
        $this->attributes['e_tag'] = $tag_number;
    }

    /**
     * @return int
     */
    public function getOriginalTagNumber()
    {
        return $this->original_e_tag;
    }

    /**
     * @param int $tag_number
     */
    public function setOriginalTagNumber($tag_number)
    {
        $this->attributes['original_e_tag'] = $tag_number;
    }

    /**
     * @return string
     */
    public function getMoveOn()
    {
        return $this->move_on;
    }

    /**
     * @param string $move_on
     */
    public function setMoveOn($move_on)
    {
        $this->attributes['move_on'] = $move_on;
    }
    /**
     * @return string
     */
    public function getMoveOff()
    {
        return $this->move_off;
    }
    /**
     * @param string $move_off
     */
    public function setMoveOff($move_off)
    {
        $this->attributes['move_off'] = $move_off;
    }
    /**
     * @return string
     */
    public function getOffHow()
    {
        return $this->off_how;
    }
    /**
     * @param string $off_how
     */
    public function setOffHow($off_how)
    {
        $this->attributes['off_how'] = $off_how;
    }
    /**
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }
    /**
     * @param string $sex
     */
    public function setSex($sex)
    {
        $this->attributes['sex'] = $sex;
    }
    /**
     * @return string
     */
    public function getColourOfTag()
    {
        return $this->colour_of_tag;
    }
    /**
     * @param string $colour_of_tag
     */
    public function setColourOfTag($colour_of_tag)
    {
        $this->attributes['colour_of_tag'] = $colour_of_tag;
    }
    /**
     * @return int
     */
    public function getColourTagNumber()
    {
        return $this->colour_tag;
    }
    /**
     * @param int $colour_tag
     */
    public function setColourTagNumber($colour_tag)
    {
        $this->attributes['colour_tag'] = $colour_tag;
    }
    /**
     * @return int
     */
    public function getColourTagFlockNumber()
    {
        return $this->colour_flock;
    }
    /**
     * @param int $colour_flock
     */
    public function setColourTagFlockNumber($colour_flock)
    {
        $this->attributes['colour_flock'] = $colour_flock;
    }
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

    public function details($id)
    {
        $details = $this->where('id', $id);
        return $details;
    }

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

    /**
     * @var array
     */
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
    public function scopeReplaced($query,$id)
    {
        $ewes = $query->where('user_id',$id)->whereRaw('`e_tag`!=`original_e_tag`')
            ->orderBy('updated_at')->paginate(20);
        $count = $query->where('user_id',$id)->whereRaw('`e_tag`!=`original_e_tag`')->count();
        return [$ewes,$count];
    }
    public function scopeSearchByTag($query,$id,$tag)
    {
        return $query->withTrashed('user_id',$id)->where('e_tag',$tag )
            ->orWhere('e_tag_1',$tag )->orWhere('e_tag_2',$tag )->paginate(20);
    }
    public function scopeStock($query,$id,$sex)
    {
        $ewes = $query->where('user_id',$id)->where('sex',$sex)->orderBy('move_on')->paginate(20);
        $count = $query->where('user_id',$id)->where('sex',$sex)->count();
        return [$ewes,$count];
    }
    public function scopeOffList($query,$id)
    {
        return $query->onlyTrashed()->where('user_id',$id)
            ->where('off_how','not like','died'.'%')->orderBy('move_off','DESC')->paginate(20);
    }
    public function scopeDead($query,$id)
    {
        return $query->onlyTrashed()->where('user_id',$id)
            ->where('off_how','like','died'.'%')->orderBy('e_flock')->paginate(20);
    }
    public function scopeTotal($query,$id)
    {
        $ewes = $query->where('user_id',$id)->paginate(20);
        $count = $query->where('user_id',$id)->count();
        return [$ewes,$count];
    }
}