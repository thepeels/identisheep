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
    protected $owner;
    /**
     * @var int
     */
    protected $flock_number;
    /**
     * @var int
     */
    protected $original_flock_number;
    /**
     * @var int
     */
    protected $local_id;
    /**
     * @var string
     */
    protected $tag_colour;
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
    protected $destination;
    /**
     * @var int
     */
    protected $serial_number;
    /**
     * @var int
     */
    protected $original_serial_number;
        /**
     * @var int
     */
    protected $old_serial_number;
    /**
     * @var int
     */
    protected $older_serial_number;
    /**
     * @var int
     */
    protected $supplementary_tag_flock_number;
    /**
     * @var int
     */
    protected $supplementary_serial_number;
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
        'owner',
        'move_on',
        'move_off',
        'destination',
        'flock_number',
        'original_flock_number',
        'supplementary_tag_flock_number',
        'serial_number',
        'old_serial_number\'',
        'older_serial_number',
        'original_serial_number',
        'supplementary_serial_number',
        'colour_tag_1',
        'colour_tag_2',
        'sex',
        'tag_colour'
    ];
    protected $dates = ['deleted_at'];

    /**
     * @return int
     */
    public function getOwner()
    {
        return $this->owner;
    }

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
     * @param int $flock_number
     */
    public function setOriginalFlockNumber($flock_number)
    {
        $this->attributes['original_flock_number'] = $flock_number;
    }


    /**
     * @return int
     */
    public function getOriginalFlockNumber()
    {
        return $this->original_flock_number;
    }
    /**
     * @return int
     */
    public function getSerialNumber()
    {
        return $this->serial_number;
    }

    /**
     * @param int $tag_number
     */
    public function setSerialNumber($tag_number)
    {
        $this->attributes['serial_number'] = $tag_number;
    }
    /**
     * @return int
     */public function getOldSerialNumber()
    {
        return $this->old_serial_number;
    }
    /**
     * @param int $old_serial_number
     */public function setOldSerialNumber($old_serial_number)
    {
        $this->attributes['old_serial_number'] = $old_serial_number;
    }
    /**
     * @return int
     */public function getOlderSerialNumber()
    {
        return $this->older_serial_number;
    }
    /**
     * @param int $older_serial_number
     */public function setOlderSerialNumber($older_serial_number)
    {
        $this->attributes['older_serial_number'] = $older_serial_number;
    }
    /**
     * @return int
     */public function getOriginalSerialNumber()
    {
        return $this->original_serial_number;
    }
    /**
     * @param int $original_serial_number
     */public function setOriginalSerialNumber($original_serial_number)
    {
        $this->attributes['original_serial_number'] = $original_serial_number;
    }
    /**
     * @return int
     */public function getSupplementarySerialNumber()
    {
        return $this->supplementary_serial_number;
    }
    /**
     * @param int $supplementary_serial_number
     */public function setSupplementarySerialNumber($supplementary_serial_number)
    {
        $this->attributes['supplementary_serial_number'] = $supplementary_serial_number;
    }
    /**
     * @return int
     */
    public function getOriginalTagNumber()
    {
        return $this->original_serial_number;
    }

    /**
     * @param int $tag_number
     */
    public function setOriginalTagNumber($tag_number)
    {
        $this->attributes['original_serial_number'] = $tag_number;
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
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param string $destination
     */public function setDestination($destination)
{
    $this->attributes['destination'] = $destination;
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
    public function getTagColour()
    {
        return $this->tag_colour;
    }
    /**
     * @param string $tag_colour
     */
    public function setTagColour($tag_colour)
    {
        $this->attributes['tag_colour'] = $tag_colour;
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
    public function getSupplementaryTagFlockNumber()
    {
        return $this->supplementary_tag_flock_number;
    }
    /**
     * @param int $flock_number
     *
     */
    public function setSupplementaryTagFlockNumber($flock_number)
    {
        $this->attributes['supplementary_tag_flock_number'] = $flock_number;
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
            $ewe = Sheep::where('flock_number', $flock)
                ->where('serial_number', $tag)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return (NULL);
        }

        return $ewe;
    }
    public static function check($flock,$tag,$id)
    {
        $ewe = Sheep::withTrashed()
            ->where('owner',$id)
            ->where('flock_number',$flock)
            ->where('serial_number',$tag)
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
            'flock_number'   => 'digits:6|required',
            'serial_number'     => 'numeric|required|between:1,99999',
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
        $ewes = $query->where('owner',$id)->whereRaw('`serial_number`!=`original_serial_number`')
            ->orderBy('updated_at')->paginate(20);
        $count = $query->where('owner',$id)->whereRaw('`serial_number`!=`original_serial_number`')->count();
        return [$ewes,$count];
    }
    public function scopeSearchByTag($query,$id,$tag)
    {
        return $query->withTrashed('owner',$id)->where('serial_number',$tag )
            ->orWhere('old_serial_number',$tag )->orWhere('older_serial_number',$tag )->paginate(20);
    }
    public function scopeStock($query,$id,$sex)
    {
        $ewes = $query->where('owner',$id)->where('sex',$sex)->orderBy('move_on')->paginate(20);
        $count = $query->where('owner',$id)->where('sex',$sex)->count();
        return [$ewes,$count];
    }
    public function scopeOffList($query,$id)
    {
        return $query->onlyTrashed()->where('owner',$id)
            ->where('destination','not like','died'.'%')->orderBy('move_off','DESC')->paginate(20);
    }
    public function scopeDead($query,$id)
    {
        return $query->onlyTrashed()->where('owner',$id)
            ->where('destination','like','died'.'%')->orderBy('flock_number')->paginate(20);
    }
    public function scopeTotal($query,$id)
    {
        $ewes = $query->where('owner',$id)->paginate(20);
        $count = $query->where('owner',$id)->count();
        return [$ewes,$count];
    }
}