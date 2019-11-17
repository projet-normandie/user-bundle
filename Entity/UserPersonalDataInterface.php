<?php

namespace ProjetNormandie\UserBundle\Entity;

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
    public function setFirstName($firstName);

    /**
     * @return string
     */
    public function getLastName();

    /**
     * @param string $lastName
     * @return UserPersonalDataTrait
     */
    public function setLastName($lastName);

    /**
     * @return string
     */
    public function getAddress();

    /**
     * @param string $address
     * @return UserPersonalDataTrait
     */
    public function setAddress($address);

    /**
     * @return \DateTime
     */
    public function getBirthDate();

    /**
     * @param \DateTime $birthDate
     * @return UserPersonalDataTrait
     */
    public function setBirthDate($birthDate);

    /**
     * @return string
     */
    public function getGender();

    /**
     * @param string $gender
     * @return UserPersonalDataTrait
     */
    public function setGender($gender);

    /**
     * @return Country
     */
    public function getCountry();

    /**
     * @param Country $country
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
    public function setTimeZone($timeZone);
}
