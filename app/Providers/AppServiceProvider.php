<?php namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Request;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
     * In case here passing global variables to (selected) views
	 *
	 * @return void
	 */
	public function boot()
	{
        view()->composer('*', function($view)
        {


            if (Auth::check()) {
                    $home_flock = Auth::user()->getFlock();
                } else {
                    $home_flock = 'false';
                }
                /* *********** logic from app.blade *********** */
                $url = Request::path();
                $elements = explode('/', $url);
                if (Request::path() === ('home')
                    || Request::path() === ('get-started')
                    || Request::path() === ('homeabout')
                    || Request::path() === ('contact')
                    || Request::path() === ('about')) {
                    $help_page = $elements[0];
                } else {
                    $help_page = $elements[1];
                }
                /* ************ */

                /* ******** logic from list pages ********** */
                $string = array_slice($elements, 0, -1);
                $print = implode("/", $string) . '/print';
                $filtered_pages = array('ewes', 'tups', 'replaced', 'deadlist', 'offlist');
                $second_filter = array('deadlist', 'offlist', 'eweslistbydate');
                /* ********* */

                $view->with([
                    'home_flock' => $home_flock,
                    'help_page' => $help_page,
                    'filtered_pages' => $filtered_pages,
                    'second_filter' => $second_filter,
                    'print' => $print,
                    'elements' => $elements,
                    'styled_logo'=>'<span class="red">Identi</span><span class="blue">Sheep</span>',
                    'base_date' => env('BASE_DATE')
                ]);

            });
        Cashier::useCurrency('gbp', '£');
        Session::put('date_to', date('Y-m-d H:i:s', strtotime(Carbon::now())));
        Session::put('date_from', date('Y-m-d H:i:s', strtotime(Carbon::now()->subYears(10))));
	}
	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'App\Services\Registrar'
		);
        if ($this->app->environment() == ('local')) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
	}

}
