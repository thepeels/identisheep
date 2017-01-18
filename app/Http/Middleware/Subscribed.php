<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 15/01/2017
 * Time: 12:52
 */

namespace App\Http\Middleware;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Support\Facades\Redirect;
use Laravel\Cashier\Cashier;

class Subscribed

{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $owner = Auth::user();
        /**ToDo: change here and controller post store method to Annual */
        if(!$owner->subscribed('Annual')&&!$owner->subscribed('Test')) {
            if (!$owner->onGenericTrial()) {
                return Redirect::to('subs/details');
            }
        }
        return $next($request);
    }
}
