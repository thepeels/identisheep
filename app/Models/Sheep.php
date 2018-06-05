<?php
/**
 * Sheep Class
 * User: John
 * Date: 23/11/2016
 * Time: 12:59
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use App\Models\Group;


class Sheep extends Model
{
    use SoftDeletes;

    /**
     * @var int
     */
    protected $id;
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
     * @var bool
     */
    protected $alive;
    /**
     * @var string
     */
    protected $move_off;
    /**
     * @var string
     */
    protected $destination;
    /**
     * @var string
     */
    protected $source;
    /**
     * @var string
     */
    protected $country_code;
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
     * @var 
     */
    protected $disposal;
    
    
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
        'alive',
        'move_off',
        'destination',
        'source',
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
        'tag_colour',
        'deleted_at',
        'inventory',
        'disposal'
    ];
    protected $dates = ['deleted_at'];

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->attributes['id'] = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->attributes['id'];
    }
    /**
     * @return int
     */
    public function getOwner()
    {
        return $this->attributes['owner'];
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
        return $this->attributes['local_id'];
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
        return $this->attributes['original_flock_number']?:NULL;
    }
    /**
     * @return int
     */
    public function getSerialNumber()
    {
        return $this->attributes['serial_number'];
    }

    /**
     * @param int $serial_number
     */
    public function setSerialNumber($serial_number)
    {
        $this->attributes['serial_number'] = $serial_number;
    }
    /**
     * @return int
     */public function getOldSerialNumber()
    {
        return $this->attributes['old_serial_number'];
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
        return $this->attributes['older_serial_number'];
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
        return $this->attributes['original_serial_number'];
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
        return $this->attributes['supplementary_serial_number'];
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
        return $this->attributes['original_serial_number'];
    }

    /**
     * @param int $tag_number
     */
    public function setOriginalTagNumber($tag_number)
    {
        $this->attributes['original_serial_number'] = $tag_number;
    }

    /**
     * @param int $tag_number
     */
    public function setTagNumber($tag_number)
    {
        $this->attributes['tag_number'] = $tag_number;
    }
    /**
     * @return string
     */
    public function getMoveOn()
    {
        return $this->attributes['move_on'];
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
        return $this->attributes['move_off'];
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
        return $this->attributes['destination'];
    }

    /**
     * @param string $destination
     */public function setDestination($destination)
    {
        $this->attributes['destination'] = $destination;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->attributes['source'] = $source;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->attributes['source'];
    }

    /**
     * @return string
     */
    public function getSex()
    {
        return $this->attributes['sex'];
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
        return $this->attributes['tag_colour'];
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
        return $this->attributes['colour_tag'];
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
        return $this->attributes['supplementary_tag_flock_number'];
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
     * @return bool 
     */
    public function getAlive(){
        return $this->attributes['alive'];
    }

    /**
     * @param bool $alive
     */
    public function setAlive($alive)
    {
        $this->attributes['alive'] = $alive;
    }

    /**
     * @param string $country_code
     */
    public function setCountryCode($country_code)
    {
        $this->attributes['country_code'] = $country_code;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->attributes['country_code'];
    }

    /**
     * @param \DateTime $deleted_at
     */
    public function setDeletedAt($deleted_at)
    {
        $this->attributes['deleted_at'] = $deleted_at;
    }

    /**
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->attributes['deleted_at'];
    }

    /**
     * @param bool $inventory
     */
    public function setInventory($inventory)
    {
        $this->attributes['inventory'] = $inventory;
    }

    /**
     * @return bool
     */
    public function getInventory()
    {
        return $this->attributes['inventory'];
    }

    /**
     * @param string $colour_tag_1
     */
    public function setColourTag1($colour_tag_1)
    {
        $this->attributes['colour_tag_1'] = $colour_tag_1;
    }

    /**
     * @return string
     */
    public function getColourTag1()
    {
        return $this->attributes['colour_tag_1'];
    }

    /**
     * @param string $colour_tag_2
     */
    public function setColourTag2($colour_tag_2)
    {
        $this->attributes['colour_tag_2'] = $colour_tag_2;
    }

    /**
     * @return string
     */
    public function getColourTag2()
    {
        return $this->attributes['colour_tag_2'];
    }

    /**
     * @param string $disposal
     */
    public function setDisposal($disposal)
    {
        $this->attributes['disposal'] = $disposal;
    }

    /**
     * @return string
     */
    public function getDisposal()
    {
        return $this->attributes['disposal'];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('app\User');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function details($id)
    {
        $details = $this->where('id', $id);
        return $details;
    }

    /**
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeGetById($query,$id)
    {
        $ewe = $query->where('id',$id)->first();
        return $ewe;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany('App\Models\Group','group_sheep');
    }

    /**
     * @param $flockNumber
     * @param $serialNumber
     * @return null
     */
    public static function getByTag($flockNumber, $serialNumber)
    {
        try {
            $ewe = Sheep::where('flock_number', $flockNumber)
                ->where('serial_number', $serialNumber)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return (NULL);
        }

        return $ewe;
    }

    /**
     * @param $flockNumber
     * @param $serialNumber
     * @param $owner
     * @return bool
     */
    public static function check($flockNumber, $serialNumber, $owner)
    {
        $ewe = Sheep::where('owner',$owner)//withTrashed()-> removed here
            ->where('flock_number',$flockNumber)
            ->where('serial_number',$serialNumber)
            ->first();
        return (NULL !== $ewe?TRUE:FALSE);
    }

    /**
     * @param $original_flockNumber
     * @param $original_serialNumber
     * @param $flockNumber
     * @param $serialNumber
     * @param $owner
     * @return bool
     */
    public static function doubleCheck($original_flockNumber, $original_serialNumber,$flockNumber, $serialNumber, $owner)
    {
        $ewe = Sheep::where('owner',$owner)//withTrashed()-> removed here
            ->where('flock_number',$flockNumber)
            ->where('serial_number',$serialNumber)
            ->where('original_flock_number',$original_flockNumber)
            ->where('original_serial_number',$original_serialNumber)
            ->first();
        return (NULL !== $ewe?TRUE:FALSE);
    }

    /**
     * @param $original_flockNumber
     * @param $original_serialNumber
     * @param $owner
     * @return bool
     */
    public static function originalCheck($original_flockNumber, $original_serialNumber, $owner)
    {
        $ewe = Sheep::where('owner',$owner)//withTrashed()-> removed here
            ->where('original_flock_number',$original_flockNumber)
            ->where('original_serial_number',$original_serialNumber)
            ->first();
        return (NULL !== $ewe?TRUE:FALSE);
    }

    /**
     * @var array
     */
    public static $rules = [
        'dates_and_tags' => [
            'day'       => 'numeric|required|between:01,31',
            'month'     => 'numeric|required|between:01,12',
            'year'      => 'integer|required|min:2006|max:2025',
            'e_flock'   => 'digits:6|required',
            'e_tag'     => 'numeric|required|between:1,99999',
        ],
        'batch'=> [
            'day'       => 'numeric|required|between:01,31',
            'month'     => 'numeric|required|between:01,12',
            'year'      => 'integer|required|min:2006|max:2025',
            'flock_number'=> 'digits:6|required',
            'start_tag' => 'digits_between:1,5|required',
            'end_tag'   => 'digits_between:1,5|required',
            'colour_of_tag'=>'required',
        ],
        'dates'=>[
            'day'       => 'numeric|required|between:01,31',
            'month'     => 'numeric|required|between:01,12',
            'year'      => 'integer|required|between:2006,2025',
            'end_day'   => 'numeric|required|between:01,31',
            'end_month' => 'numeric|required|between:01,12',
            'end_year'  => 'integer|required|between:2006,2025'
        ],
        'old_dates'=>[
            'year'      => 'integer|required|between:2006,2025'
        ],

        'where_to'=>[
            'destination'=>'required'
        ],
        'single'=>[
            'count'     => 'integer|required',
            'flock_number'   => 'digits:6|required',
        ],
        'death'=>[
            'e_flock'   => 'digits:6|required',
            'e_tag'     => 'numeric|required|between:1,99999',
        ],
        'search'=>[
            'tag'       => 'numeric|required|between:1,99999'
        ],
        'replacement'   =>[
            'original_flock'=>'sometimes|required_with:original_tag|digits:6',
            'original_tag' => 'sometimes|required_with:original_flock|sometimes:numeric|between:1,99999'
        ],
        'file_raw'=>[
        'file_raw'      =>  'required'
        ],
        'single-start'=>[
            'start'     =>  'numeric|required|between:1,99999'
        ],



    ];


    public function scopeReplaced($query,$id)
    {
        $dates = $this->dateRange();

        $ewes = $query->where('owner','=',$id)->where('updated_at' ,'>=',$dates[0])->where('updated_at' ,'<=',$dates[1])
            ->whereRaw('`serial_number`!=`original_serial_number`')
            ->orderBy('updated_at','DESC')->paginate(20);
        //$count = $query->where('owner',$id)->whereRaw('`serial_number`!=`original_serial_number`')->count();
        //return [$ewes,$count];
        return $ewes;
    }


    public function scopeSearchByTag($query,$id,$tag)
    {
        $query = (DB::select(DB::raw("select * FROM `sheep` WHERE `owner`= $id 
                and (`serial_number`= $tag or `original_serial_number`= $tag 
                or `old_serial_number`= $tag or`older_serial_number` = $tag 
                or `colour_tag_1`= $tag or`colour_tag_2` = $tag 
                or`supplementary_serial_number` = $tag)")));
        $ewes = collect($query);
        return $ewes;
    }


    public function scopeStock($query,$id,$sex)
    {
        $dates = $this->dateRange();
        $ewes = $query->where('owner',$id)->where('alive',TRUE)->where('sex',$sex)
            ->where('move_on','>=',$dates[0])->where('move_on','<=',$dates[1])
            ->orderBy('move_on')->paginate(20);

        return $ewes;
    }
    public function scopeStockPrint($query,$id,$sex)
    {
        $dates = $this->dateRange();
        $ewes = $query->where('owner',$id)->where('alive',TRUE)->where('sex',$sex)
            ->where('move_on','>=',$dates[0])->where('move_on','<=',$dates[1])
            ->orderBy('move_on')->get();

        return $ewes;
    }
    public function scopeOffList($query,$id)
    {
        $dates = $this->dateRange();
        return $query->where('owner',$id)->where('alive',FALSE)
            ->where('move_off','>=',$dates[0])->where('move_off','<=',$dates[1])
            ->where('destination','not like','died'.'%')->orderBy('move_off','DESC')->paginate(20);
    }
    public function scopeOffListPrint($query,$id)
    {
        $dates = $this->dateRange();
        return $query->where('owner',$id)->where('alive',FALSE)
            ->where('move_off','>=',$dates[0])->where('move_off','<=',$dates[1])
            ->where('destination','not like','died'.'%')->orderBy('move_off','DESC')->get();
    }
    public function scopeDead($query,$id)
    {
        $dates = $this->dateRange();
        return $query->where('owner',$id)->where('alive',FALSE)
            ->where('move_off','>=',$dates[0])
            ->where('move_off','<=',$dates[1])
            ->where('destination','like','died'.'%')->orderBy('move_off','DESC')->paginate(20);
    }
    public function scopeDeadPrint($query,$id)
    {
        $dates = $this->dateRange();
        return $query->where('owner',$id)->where('alive',FALSE)
            ->where('move_off','>=',$dates[0])
            ->where('move_off','<=',$dates[1])
            ->where('destination','like','died'.'%')->orderBy('move_off','DESC')->get();
    }
    public function scopeTotal($query,$id)
    {
        $ewes = $query->where('owner',$id)->where('alive',TRUE)->paginate(20);
        $count = $query->where('owner',$id)->where('alive',TRUE)->count();
        return [$ewes,$count];
    }
    public function scopeGetByEarNumbers($query,$flock_number,$serial_number)
    {
        return $query->where('alive',TRUE)->where('flock_number',$flock_number)
            ->where('serial_number',$serial_number)->first();
    }
    public function scopeRemoveOld($query,$id,$date)
    {
        $query = Sheep::where('owner',$id)->where('move_on','<=',$date)->get();
        foreach($query as $sheep) {
            //$sheep->setDeletedAt(Carbon::now());
            //$sheep->save();
            $sheep->delete();
        }
    }
    private function dateRange()
    {
        $date_from = Null !=(Session::get('date_from'))?Session::get('date_from'):Carbon::now()->subYears(10)->toDateString();
        $date_to = Null !=  (Session::get('date_to'))?Session::get('date_to'):Carbon::now()->toDateString();
        return [$date_from,$date_to];
    }

    public function scopeTrashOrNew($query,$flock_number,$serial_number,$id)
    {
        $ewe = $query->withTrashed()->where('owner',$id)
            ->where('flock_number',$flock_number)
            ->where('serial_number',$serial_number)
            ->firstOrNew();
        return $ewe;

    }
}