<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 23/02/2017
 * Time: 17:43
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class CookieExpired
{
    public function handle($request, Closure $next)
    {

        echo('Route passed through Middleware - CookieExpired');

        return $next($request);
    }
}