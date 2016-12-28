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

class SheepService
{
    /**
     * @param TagNumber $tagNumber
     * @param \DateTime $dateOfDeath
     * @param $reasonForDeath
     * @param Sex $sex
     * @param Owner $owner
     */
    public function recordDeath(TagNumber $tagNumber, \DateTime $dateOfDeath, $reasonForDeath, Sex $sex, $owner)
    {
        $ewe = Sheep::firstOrNew([
            'flock_number'    =>  $tagNumber->getFlockNumber(),
            'serial_number'   =>  $tagNumber->getSerialNumber(),
            'owner'           =>  $owner,
            //'alive'           =>  TRUE,
        ]);
        $ewe->setOriginalFlockNumber($tagNumber->getFlockNumber());
        $ewe->setSupplementaryTagFlockNumber($tagNumber->getFlockNumber());
        $ewe->setSerialNumber($tagNumber->getSerialNumber());
        $ewe->setAlive(FALSE);
        $ewe->setMoveOff($dateOfDeath->format('Y-m-d'));
        $ewe->setDestination('died' . $reasonForDeath);
        $ewe->setSex($sex);
        $ewe->save();
        // TODO: refactor delete() to recordDeath() or something

    }
}