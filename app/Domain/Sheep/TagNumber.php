<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 16/12/2016
 * Time: 18:29
 */

namespace App\Domain\Sheep;

use App\Domain\DomainException;
use App\Domain\Sheep\CountryCode;

class TagNumber
{
    /**
     * @var string
     */
    private $countryCode;
    /**
     * @var int
     */
    private $flockNumber;
    /**
     * @var int
     */
    private $serialNumber;


    /**
     * TagNumber constructor.
     * @param string $tagNumberString e.g. UK012345600001
     * @throws DomainException
     */
    public function __construct($tagNumberString)
    {
        $tagNumberString = $this->typoCorrections($tagNumberString);

        //dd($tagNumberString);

        switch (true) {
            case (preg_match("/^[A-Z]{2}0[0-9]{11}$/", $tagNumberString) == 1):
                $this->countryCode = substr($tagNumberString, 0, 2);
                $this->replacedCode = substr($tagNumberString, 2, 1);
                $this->flockNumber = (int)substr($tagNumberString, 3, 6);
                $this->serialNumber = (int)substr($tagNumberString, 9, 5);
                break;
            case (preg_match("/[0-9]{3}[0-9]{1}[0-9]{11}/", $tagNumberString) == 1) :
                $country_id = mb_substr($tagNumberString, 0, 3);
                $code = new CountryCode($country_id);
                $this->countryCode = $code->convert($country_id);
                $this->replacedCode = substr($tagNumberString, 3, 1);
                $this->flockNumber = mb_substr($tagNumberString, 4, 6);
                $this->serialNumber = mb_substr($tagNumberString, 10, 5);
                break;
            case ((preg_match("/[0-9]{3}[0-9]{1}[0-9]{11}/", $tagNumberString) == 0) &&
                (preg_match("/^[A-Z]{2}0[0-9]{11}$/", $tagNumberString) == 0)) :
                throw new DomainException('Tag number supplied must be of the format UK0*********** where * are digits. 
                e.g. UK012345600001 or all digits with 3 digit country code e.g. 826 012345600001
                You need to edit your input file. 
                CSV file must have no headings - tag numbers on separate lines, 
                Excel file - tag numbers in "EID" column.');
        }



    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode.$this->replacedCode;
    }

    /**
     * @return int
     */
    public function getFlockNumber()
    {
        return $this->flockNumber;
    }

    /**
     * @return int
     */
    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    /**
     * @return string  returns short tag number, e.g. 123456 00001
     */
    public function getShortTagNumber()
    {
        return sprintf('%06d %05d', $this->flockNumber, $this->serialNumber);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s%01d %06d %05d', $this->countryCode, $this->replacedCode, $this->flockNumber, $this->serialNumber);
    }

    /**
     * @param $tagNumberString
     * @return mixed
     */
    public function typoCorrections($tagNumberString)
    {
        $tagNumberString = preg_replace('/\s+/', '',$tagNumberString);
        $tagNumberString = mb_strtoupper($tagNumberString);
        $tagNumberString = str_replace(array(",\r\n", "\r\n", "\n\r", ",\n\r", ",\n", ",\r", ", ", ",¶"), "", $tagNumberString);
        /** if too international will need to split off country code section first*/
        $tagNumberString = str_replace(array("L", "I"), "1", $tagNumberString);
        $tagNumberString = str_replace(array("A", "S"), ["4","5"], $tagNumberString);
        $tagNumberString = str_replace(array("O"), "0", $tagNumberString);
        /** ***********/
        return $tagNumberString;
    }
}