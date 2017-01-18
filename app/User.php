<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model as Dumbo;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Psy\Util\String;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Cashier;

class User extends Dumbo implements AuthenticatableContract, CanResetPasswordContract {

	use Billable;
    use Authenticatable;
    use CanResetPassword;

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
        'business',
        'holding',
        'super_user',
        'trial_ends_at'

    ];
    /**
     * @var 
     */
    protected $email;
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
     * @var string 
     */
    protected $holding;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];
    /**
     * @var array
     */
    protected $dates = ['trial_ends_at', 'subscription_ends_at'];
    
    /**
     * @var 
     */
    protected $superuser;

    /**
     * @var 
     */
    protected $cardUpFront = false;

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
     * @return string 
     */
    public function getHolding(){
        return $this->attributes['holding'];
    }
    /**
     * @param string $holding
     */
    public function setHolding($holding)
    {
        $this->attributes['holding'] = $holding;
    }
    /**
     * @return int 
     */
    public function getSuperuser(){
        return $this->attributes['superuser'];
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->attributes['email'] = $email;
    }
    /**
     * @return string 
     */
    public function getEmail(){
        return $this->attributes['email'];
    }

    /**
     * @param \DateTime $trial_ends_at
     */
    public function setTrialEndsAt($trial_ends_at)
    {
        $this->attributes['trial_ends_at'] = $trial_ends_at;
    }
    /**
     * @return \DateTime
     */
    public function getTrialEndsAt(){
        return $this->attributes['trial_ends_at'];
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sheep()
    {
        return $this->hasMany('Sheep');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function listdates()
    {
        return $this->hasOne('ListDates');
    }

    /**
     * @return int
     */
    public function taxPercentage()
    {
        return 20;
    }
}
