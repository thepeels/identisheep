<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 10/06/2017
 * Time: 21:49
 */

namespace app\Models;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Subscriptions
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var
     */
    protected $user_id;
    /**
     * @var
     */
    protected $name;
    /**
     * @var 
     */
    protected $stripe_id;
    /**
     * @var 
     */
    protected $stripe_plan;
    /**
     * @var
     */
    protected $ends_at;

    public static function stripe_plan()
    {
        $plan = DB::table('subscriptions')->where('user_id',Auth::user()->id)->first();
        return $plan->stripe_plan;

}

    /**
     * @param string $stripe_id
     */
    public function setStripeId($stripe_id)
    {
        $this->attributes['stripe_id'] = $stripe_id;
    }

    /**
     * @return string
     */
    public function getStripeId()
    {
        return $this->attributes['stripe_id'];
    }

}