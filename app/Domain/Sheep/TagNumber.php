<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 16/12/2016
 * Time: 18:29
 */

namespace App\Domain\Sheep;

use App\Domain\DomainException;

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
        $tagNumberString    = preg_replace('/\s/', '', strtoupper($tagNumberString));
        $tagNumberString    = str_replace(array(",\r\n","\r\n","\n\r", ",\n\r", ",\n", ",\r", ", ",",¶"), "", $tagNumberString);
        $tagNumberString    = str_replace(array("L","I"), "1", $tagNumberString);
        $tagNumberString    = str_replace(array("O"), "0", $tagNumberString);

        if (preg_match("/^[A-Z]{2}0[0-9]{11}$/", $tagNumberString) === false) {
            throw new DomainException('Tag number supplied must be of the format UK0*********** where * are digits. e.g. UK012345600001');
        }

        $this->countryCode = substr($tagNumberString, 0, 3);
        $this->flockNumber = (int) substr($tagNumberString, 3, 6);
        $this->serialNumber = (int) substr($tagNumberString, 9, 5);
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
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
        return sprintf('%s%06d%05d', $this->countryCode, $this->flockNumber, $this->serialNumber);
    }
}