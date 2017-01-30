<?php namespace App\Services;

use App\Domain\Sheep\EmailService;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;
use Carbon\Carbon;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
            'flock' => 'digits:6|required',
            'holding'=> 'required',
			'password' => 'required|confirmed|min:6',
            'address'   => 'required',
            'business'  => 'required'
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
		$free = Carbon::now()->addMinutes(5);
        return User::create([
			'name' => 'name',
			'email' => $data['email'],
            'flock' => $data['flock'],
            'holding'=> $data['holding'],
			'password' => bcrypt($data['password']),
            'address' => $data['address'],
            'business' => $data['business'],
            'trial_ends_at' => $free
		]);
	}

    private function createListDate()
    {

	}

}
