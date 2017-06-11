<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 10/06/2017
 * Time: 21:49
 */

namespace app\Models;


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


}