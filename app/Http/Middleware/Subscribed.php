<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 15/01/2017
 * Time: 12:52
 */

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Support\Facades\Redirect;

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
        if(!$owner->subscribed('Annual')) {
            if (!$owner->onTrial()) {
                return Redirect::to('subs/details');
            }
        }
        return $next($request);
    }
}
