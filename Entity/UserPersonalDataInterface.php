<?php

namespace ProjetNormandie\UserBundle\Entity;

use DateTime;

interface UserPersonalDataInterface
{
    /**
     * @return string
     */
    public function getFirstName();

    /**
     * @param string $firstName
     * @return mixed
     */
    public function setFirstName(string $firstName);

    /**
     * @return string
     */
    public function getLastName();

    /**
     * @param string $lastName
     * @return mixed
     */
    public function setLastName(string $lastName);

    /**
     * @return string
     */
    public function getAddress();

    /**
     * @param string $address
     * @return mixed
     */
    public function setAddress(string $address);

    /**
     * @return DateTime
     */
    public function getBirthDate();

    /**
     * @param DateTime|null $birthDate
     * @return mixed
     */
    public function setBirthDate(DateTime $birthDate = null);

    /**
     * @return string
     */
    public function getGender();

    /**
     * @param string $gender
     * @return mixed
     */
    public function setGender(string $gender);

    /**
     * @return int
     */
    public function getTimeZone();

    /**
     * @param int $timeZone
     * @return UserPersonalDataTrait
     */
    public function setTimeZone(int $timeZone);
}
