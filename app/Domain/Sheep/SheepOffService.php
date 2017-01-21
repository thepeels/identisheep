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

class SheepOffService
{
    /**
     * @param TagNumber $tagNumber
     * @param \DateTime $dateOfDeath
     * @param $reasonForDeath
     * @param Sex $sex
     * @param $owner
     * @param $colour_of_tag
     */
    public function recordDeath(TagNumber $tagNumber, \DateTime $dateOfDeath, $reasonForDeath, Sex $sex, $owner,$colour_of_tag = "")
    {
        $sheep_exists = Sheep::check($tagNumber->getFlockNumber(), $tagNumber->getSerialNumber(), $owner);
        $ewe = $this->sheepOffAction($tagNumber, $dateOfDeath, 'died' . $reasonForDeath, $owner);
        if(!$sheep_exists){
            $this->isNewSheepOffAction($tagNumber, $sex, $colour_of_tag, $ewe);
        }
        $ewe->save();
    }

    /**
     * @param TagNumber $tagNumber
     * @param \DateTime $dateOfMovement
     * @param $destination
     * @param Sex $sex
     * @param $owner
     * @param $colour_of_tag
     */
    public function recordMovement(TagNumber $tagNumber, \DateTime $dateOfMovement, $destination, Sex $sex, $owner, $colour_of_tag = "")
    {
        $sheep_exists = Sheep::check($tagNumber->getFlockNumber(), $tagNumber->getSerialNumber(), $owner);
        $ewe = $this->sheepOffAction($tagNumber, $dateOfMovement, $destination, $owner);
        if(!$sheep_exists) {
            $this->isNewSheepOffAction($tagNumber, $sex, $colour_of_tag, $ewe);
        }
        $ewe->save();
    }

    /**
     * @param \App\Domain\Sheep\TagNumber $tagNumber
     * @param \App\Domain\Sheep\Sex $sex
     * @param $colour_of_tag
     * @param \App\Models\Sheep $ewe
     */
    private function isNewSheepOffAction(TagNumber $tagNumber, Sex $sex, $colour_of_tag, $ewe)
    {
        $ewe->setOriginalFlockNumber($tagNumber->getFlockNumber());
        $ewe->setOriginalSerialNumber($tagNumber->getSerialNumber());
        $ewe->setFlockNumber($tagNumber->getFlockNumber());
        $ewe->setSerialNumber($tagNumber->getSerialNumber());
        $ewe->setSupplementaryTagFlockNumber($tagNumber->getFlockNumber());
        $ewe->setSupplementarySerialNumber($tagNumber->getSerialNumber());
        $ewe->setTagColour($colour_of_tag);
        $ewe->setSex($sex);
    }

    /**
     * @param \App\Domain\Sheep\TagNumber $tagNumber
     * @param \DateTime $dateOfMovement
     * @param $destination
     * @param $owner
     * @return static
     */
    private function sheepOffAction(TagNumber $tagNumber, \DateTime $dateOfMovement, $destination, $owner)
    {
        $ewe = Sheep::firstOrNew([
            'flock_number' => $tagNumber->getFlockNumber(),
            'serial_number' => $tagNumber->getSerialNumber(),
            'owner' => $owner,
        ]);
        $ewe->setAlive(FALSE);
        $ewe->setMoveOff($dateOfMovement->format('Y-m-d'));
        $ewe->setDestination($destination);
        return $ewe;
    }

}