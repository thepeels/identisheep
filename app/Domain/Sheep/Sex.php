<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 27/12/2016
 * Time: 23:45
 */

namespace App\Domain\Sheep;


use App\Domain\DomainException;

class Sex
{
    /**
     * @var string
     */
    private $sex;

    /**
     * Sex constructor.
     * @param string $sex
     * @throws DomainException
     */
    public function __construct($sex)
    {
        if (!in_array($sex, ['male', 'female','Male','Female'])) {
            throw new DomainException('Sex must be either male or female');
        }
        $this->sex = $sex;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->sex;
    }

    /**
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }
}