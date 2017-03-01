<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 27/12/2016
 * Time: 23:43
 */

namespace App\Domain\Sheep;

use App\Domain\Sheep\Sex;
use App\Domain\Sheep\TagNumber;
use App\Models\Sheep;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Homebred;

class SheepOnService
{
    /**
     * @param TagNumber $tagNumber
     * @param \DateTime $move_on
     * @param string $colour_of_tag
     * @param Sex $sex
     * @param int $owner
     * @param int $local_index
     * @param string $source
     */
    public function movementOn(TagNumber $tagNumber, \DateTime $move_on, $colour_of_tag, Sex $sex, $owner, $local_index, $source)
    {
        $ewe = Sheep::firstOrNew([
            'flock_number' => $tagNumber->getFlockNumber(),
            'serial_number' => $tagNumber->getSerialNumber(),
            'owner' => $owner,
        ]);
        $ewe->setOwner($owner);
        $ewe->setLocalId($local_index);
        $ewe->setCountryCode($tagNumber->getCountryCode());
        $ewe->setFlockNumber($tagNumber->getFlockNumber());
        $ewe->setOriginalFlockNumber($tagNumber->getFlockNumber());
        $ewe->setSupplementaryTagFlockNumber($tagNumber->getFlockNumber());
        $ewe->setSerialNumber($tagNumber->getSerialNumber());
        $ewe->setOriginalSerialNumber($tagNumber->getSerialNumber());
        $ewe->setSupplementarySerialNumber($tagNumber->getSerialNumber());
        $ewe->setAlive(TRUE);
        $ewe->setMoveOn($move_on);
        $ewe->setTagColour($colour_of_tag);
        $ewe->setSex($sex);
        $ewe->setSource($source);
        $ewe->save();

    }

    /**
     * Home bred addition for postAddEwe() in SheepController
     * @param \App\Domain\Sheep\TagNumber $tagNumber
     * @param \DateTime $move_on
     * @param int $count
     * @param $owner
     */
    public function homeBredOn(TagNumber $tagNumber, \DateTime $move_on, $count, $owner)
    {
        $ewe = new Homebred();
        $ewe->setFlockNumber($tagNumber->getFlockNumber());
        $ewe->setCount($count);
        $ewe->setUserId($owner);
        $ewe->setDateApplied($move_on);
        $ewe->setUpdatedAt($move_on);
        $ewe->save();
    }

}