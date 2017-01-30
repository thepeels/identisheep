<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 25/01/2017
 * Time: 07:20
 */

namespace App\Domain\Sheep;
use App\Models\Sheep;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TagReplacementService
{
    /**
     * Tag replacement handler
     * @param string $e_flock
     * @param string $e_tag
     * @param string $original_flock
     * @param string $original_tag
     * @param string $year
     * @param string $month
     * @param string $day
     * @param string $sex
     */
    public function handler($e_flock,$e_tag,$original_flock,$original_tag,$year,$month,$day,$sex)
    {
        $tagNumber = new TagNumber('UK0' . $e_flock . $e_tag);
        $originalTagNumber = new TagNumber('UK0' . $original_flock . $original_tag);
        $date_of_replacement = new \DateTime($year . '-' . $month . '-' . $day);
        $double_check = Sheep::doubleCheck($originalTagNumber->getFlockNumber(), $originalTagNumber->getSerialNumber(),
            $tagNumber->getFlockNumber(), $tagNumber->getSerialNumber(), $this->owner());
        $original_check = Sheep::originalCheck($tagNumber->getFlockNumber(), $tagNumber->getSerialNumber(),$this->owner());
        if (!$double_check) {
            if ($original_flock) { // original fields are not blank
                $ewe = Sheep::firstOrNew(['original_flock_number' => $originalTagNumber->getFlockNumber(),
                    'Original_serial_number' => $originalTagNumber->getSerialNumber(),
                    'owner' => $this->owner()]);
                if($ewe->exists) {
                    $ewe->setOlderSerialNumber($ewe->getOldSerialNumber());
                    $ewe->setOldSerialNumber($ewe->getSerialNumber());
                    $ewe->setFlockNumber($tagNumber->getFlockNumber());
                    $ewe->setSerialNumber($tagNumber->getSerialNumber());
                    $ewe->setSupplementaryTagFlockNumber($tagNumber->getFlockNumber());
                    $ewe->setSupplementarySerialNumber($tagNumber->getSerialNumber());

                    $ewe->save();
                    Session::flash('message', 'Sheep UK0 ' . $tagNumber->getFlockNumber() . ' '
                        . $tagNumber->getSerialNumber() . ' updated.');
                } else {
                    Session::flash('message','Tags Input Error - no Action.');
                }
            } else { // original fields are blank....
                if(!$original_check) { //new tag numbers are not present as originals
                    $ewe = Sheep::firstOrNew(['flock_number' => $tagNumber->getFlockNumber(),
                        'serial_number' => $tagNumber->getSerialNumber(),
                        'owner' => $this->owner()]);
                    $ewe->setFlockNumber($tagNumber->getFlockNumber());
                    $ewe->setSerialNumber($tagNumber->getSerialNumber());
                    $ewe->setSupplementaryTagFlockNumber($tagNumber->getFlockNumber());
                    $ewe->setSupplementarySerialNumber($tagNumber->getSerialNumber());
                    if ($ewe->exists) { //ie double entry of form -> modify existing
                        $ewe->setOlderSerialNumber($ewe->getOldSerialNumber());
                        $ewe->setOldSerialNumber($ewe->getSerialNumber());
                        $ewe->save();
                        Session::flash('message', 'Replacement UK0 '
                            . $tagNumber->getFlockNumber() . ' '
                            . $tagNumber->getSerialNumber() . ' updated.');
                    } else { //is a new entry
                        $ewe->setOriginalFlockNumber($tagNumber->getFlockNumber());
                        $ewe->setOriginalSerialNumber($tagNumber->getSerialNumber());
                        $ewe->setMoveOn($date_of_replacement);
                        $ewe->setAlive(TRUE);
                        $ewe->setSex($sex);
                        $ewe->save();
                        Session::flash('message', 'Replacement UK0 '
                            . $tagNumber->getFlockNumber() . ' '
                            . $tagNumber->getSerialNumber() . ' entered.');
                    }
                } else {  //original tags same as new entry therefore do nothing
                    Session::flash('message','Tags already deployed therefore no Action.');
                }
            }
        }
        else{
            Session::flash('message','These sheep details were already changed.');
        }

    }
    private function owner()
    {
        return Auth::user()->id;
    }
}