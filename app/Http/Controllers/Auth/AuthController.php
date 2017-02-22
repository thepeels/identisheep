<?php namespace App\Http\Controllers\Auth;

use App\Domain\Sheep\EmailService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/
	/**
	 * @var
	 */
	protected $auth;
	/**
	 * @var
	 */
	protected $registrar;

	use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     * AuthController constructor.
     * @param Guard $auth
     * @param Registrar $registrar
     */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;
		$this->middleware('guest', ['except' => 'getLogout']);
        //$this->middleware('subscribed');
	}


    public function Index()
    {
        return view('auth.login');
    }
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
            'flock' => 'digits:6|required|numeric',
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
        $then = Carbon::now()->addDays(183);
        //dd($now);
        Session::flash('message','You are now signed up for a 6 month free trial membership.');
        $welcome = new EmailService($data['email']);
        $welcome->sendWelcome();
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'flock' => $data['flock'],
            'holding'=> $data['holding'],
            'password' => bcrypt($data['password']),
            'address' => $data['address'],
            'business' => $data['business'],
            'trial_ends_at' => $then
        ]);
        /**ToDo: send welcome e-mail*/

    }
    public function getLogout(){
        Auth::logout();
        Session::flush();
        return Redirect::to('/');
    }
}
