<?php

namespace ProjetNormandie\UserBundle\Entity;

use DateTime;
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
     * @var DateTime
     * @ORM\Column(name="birthDate", type="date", nullable=true)
     */
    protected $birthDate;
    /**
     * @var string
     * @ORM\Column(name="gender", type="string", length=1, nullable=false)
     */
    protected $gender;
    /**
     * @var CountryInterface
     *
     * @ORM\ManyToOne(targetEntity="ProjetNormandie\UserBundle\Entity\CountryInterface")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCountry", referencedColumnName="id")
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
     * @return $this
     */
    public function setFirstName(string $firstName)
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
     * @return $this
     */
    public function setLastName(string $lastName)
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
     * @return $this
     */
    public function setAddress(string $address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param DateTime|null $birthDate
     * @return $this
     */
    public function setBirthDate(DateTime $birthDate = null)
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
     * @return $this
     */
    public function setGender(string $gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return CountryInterface
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param $country
     * @return $this
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
     * @return $this
     */
    public function setTimeZone(int $timeZone)
    {
        $this->timeZone = $timeZone;
        return $this;
    }
}
