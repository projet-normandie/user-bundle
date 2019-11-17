<?php

namespace ProjetNormandie\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait UserPersonalDataTrait
{
    /**
     * @var string
     * @ORM\Column(name="firstName", type="string", length=255, nullable=true)
     */
    protected $firstName;
    /**
     * @var string
     * @ORM\Column(name="lastName", type="string", length=255, nullable=true)
     */
    protected $lastName;
    /**
     * @var string
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    protected $address;
    /**
     * @var \DateTime
     * @ORM\Column(name="birthDate", type="date", nullable=true)
     */
    protected $birthDate;
    /**
     * @var string
     * @ORM\Column(name="gender", type="string", length=1, nullable=false)
     */
    protected $gender;
    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="ProjetNormandie\UserBundle\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idPays", referencedColumnName="id")
     * })
     */
    protected $country;
    /**
     * @var int
     * @ORM\Column(name="timeZone", type="integer", nullable=true)
     */
    protected $timeZone;

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return UserPersonalDataTrait
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return UserPersonalDataTrait
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return UserPersonalDataTrait
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTime $birthDate
     * @return UserPersonalDataTrait
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return UserPersonalDataTrait
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Country $country
     * @return UserPersonalDataTrait
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * @param int $timeZone
     * @return UserPersonalDataTrait
     */
    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;
        return $this;
    }
}
