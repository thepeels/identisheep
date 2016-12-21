<?php namespace App;

use bar\baz\source_with_namespace;
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
        'flock'
    ];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];
    /**
     * @var string
     */
    protected $name;
    protected $email;
    protected $password;
    /**
     * @var int
     */
    protected $flock;

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
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->attributes['password'] = $password;
    }

    /**
     * @param int $flock
     */
    public function setFlock($flock)
    {
        $this->attributes['flock'] = $flock;
    }
    /**
     * @return int
     */
    public function getFlock(){
        return $this->attributes['flock'];
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function sheep()
    {
        return $this->hasMany('Sheep');
    }

}
