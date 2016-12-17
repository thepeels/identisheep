<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 16/12/2016
 * Time: 18:46
 */

namespace App\Http\Controllers;


use App\Domain\TagNumber;

class TestController extends Controller
{
    public function getSheep($tagNumberString)
    {
        echo $tagNumberString . "<br>";

        $tagNumber = new TagNumber($tagNumberString);

        echo $tagNumber->getCountryCode() . "<br>";
        echo $tagNumber->getFlockNumber() . "<br>";
        echo $tagNumber->getSerialNumber() . "<br>";

        echo $tagNumber . "<br>";

        $tagNumber = new TagNumber((string) $tagNumber);

        echo $tagNumber . "<br>";

    }
}