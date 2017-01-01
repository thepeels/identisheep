<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 01/01/2017
 * Time: 08:50
 */

namespace App\Domain\Sheep;


use App\Models\Homebred;

class HomeBredService
{
    /**
     * @param $home_bred
     * @param $move_on
     * @param $owner
     */
    public function addHomeBred($home_bred,$move_on,$owner)
    {
        $tag = new Homebred($home_bred,$move_on,$owner);
        $tag->setFlockNumber($home_bred);
        $tag->setDateApplied($move_on);
        $tag->setUserId($owner);
        $tag->setCount(1);
        $tag->save();
    }
}