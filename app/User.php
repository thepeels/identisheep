<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
	    'name',
        'email',
        'password',
        'flock',
        'address',
        'business'
    ];
    /**
     * @var int
     */
    protected $flock;
    /**
     * @var string
     */
    protected $address;
    /**
     * @var string
     */
    protected $business;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];
    /**
     * @return int 
     */
    public function getFlock(){
        return $this->attributes['flock'];
    }

    /**
     * @param int $flock
     */
    public function setFlock($flock)
    {
        $this->attributes['flock'] = $flock;
    }
    /**
     * @return string 
     */
    public function getAddress(){
        return $this->attributes['address'];
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->attributes['address'] = $address;
    }
    /**
     * @return string
     */
    public function getBusiness(){
        return $this->attributes['business'];
    }

    /**
     * @param string $business
     */
    public function setBusiness($business)
    {
        $this->attributes['business'] = $business;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function sheep()
    {
        return $this->hasMany('Sheep');
    }

}
