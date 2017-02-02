<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 31/01/2017
 * Time: 06:27
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\Sheep;

class Group extends Model
{
    protected $fillable =
        [
            'name',
            'description',
            'info',
            'owner'
        ];
    /**
     * @var
     */
    protected $name;
    /**
     * @var
     */
    protected $description;
    /**
     * @var
     */
    protected $info;
    /**
     * @var 
     */
    protected $owner;

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->attributes['name'] = $name;
    }
    /**
     * @return string
     */
    public function getName(){
        return $this->attributes['name'];
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->attributes['description'] = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->attributes['description'];
    }

    /**
     * @param string $info
     */
    public function setInfo($info)
    {
        $this->attributes['info'] = $info;
    }

    /**
     * @return string
     */
    public function getInfo()
    {
        return $this->attributes['info'];
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
    public function getOwner()
    {
        return $this->attributes['owner'];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->attributes['id'];
    }




    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sheep()
    {
        return $this->belongsToMany('App\Models\Sheep','group_sheep');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsTo('app\User');
    }
}