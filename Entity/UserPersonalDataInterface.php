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
     * @return UserPersonalDataTrait
     */
    public function setFirstName(string $firstName);

    /**
     * @return string
     */
    public function getLastName();

    /**
     * @param string $lastName
     * @return UserPersonalDataTrait
     */
    public function setLastName(string $lastName);

    /**
     * @return string
     */
    public function getAddress();

    /**
     * @param string $address
     * @return UserPersonalDataTrait
     */
    public function setAddress(string $address);

    /**
     * @return DateTime
     */
    public function getBirthDate();

    /**
     * @param DateTime $birthDate
     * @return UserPersonalDataTrait
     */
    public function setBirthDate(DateTime $birthDate);

    /**
     * @return string
     */
    public function getGender();

    /**
     * @param string $gender
     * @return UserPersonalDataTrait
     */
    public function setGender(string $gender);

    /**
     * @return CountryInterface
     */
    public function getCountry();

    /**
     * @param $country
     * @return UserPersonalDataTrait
     */
    public function setCountry($country);

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
