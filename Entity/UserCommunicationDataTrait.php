<?php

namespace ProjetNormandie\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait UserCommunicationDataTrait
{
    /**
     * @var string
     * @ORM\Column(name="personalWebsite", type="string", length=255, nullable=true)
     */
    protected $personalWebSite;
    /**
     * @var string
     * @ORM\Column(name="facebook", type="string", length=255, nullable=true)
     */
    protected $facebook;
    /**
     * @var string
     * @ORM\Column(name="twitter", type="string", length=255, nullable=true)
     */
    protected $twitter;
    /**
     * @var string
     * @ORM\Column(name="googleplus", type="string", length=255, nullable=true)
     */
    protected $googlePlus;
    /**
     * @var string
     * @ORM\Column(name="youtube", type="string", length=255, nullable=true)
     */
    protected $youtube;
    /**
     * @var string
     * @ORM\Column(name="dailymotion", type="string", length=255, nullable=true)
     */
    protected $dailymotion;
    /**
     * @var string
     * @ORM\Column(name="twitch", type="string", length=255, nullable=true)
     */
    protected $twitch;
    /**
     * @var string
     * @ORM\Column(name="skype", type="string", length=255, nullable=true)
     */
    protected $skype;
    /**
     * @var string
     * @ORM\Column(name="snapchat", type="string", length=255, nullable=true)
     */
    protected $snapChat;
    /**
     * @var string
     * @ORM\Column(name="pinterest", type="string", length=255, nullable=true)
     */
    protected $pinterest;
    /**
     * @var string
     * @ORM\Column(name="trumblr", type="string", length=255, nullable=true)
     */
    protected $trumblr;
    /**
     * @var string
     * @ORM\Column(name="blogger", type="string", length=255, nullable=true)
     */
    protected $blogger;
    /**
     * @var string
     * @ORM\Column(name="reddit", type="string", length=255, nullable=true)
     */
    protected $reddit;
    /**
     * @var string
     * @ORM\Column(name="deviantart", type="string", length=255, nullable=true)
     */
    protected $deviantArt;

    /**
     * @return string
     */
    public function getPersonalWebSite()
    {
        return $this->personalWebSite;
    }

    /**
     * @param string $personalWebSite
     * @return UserCommunicationDataTrait
     */
    public function setPersonalWebSite($personalWebSite)
    {
        $this->personalWebSite = $personalWebSite;
        return $this;
    }

    /**
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param string $facebook
     * @return UserCommunicationDataTrait
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
        return $this;
    }

    /**
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param string $twitter
     * @return UserCommunicationDataTrait
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
        return $this;
    }

    /**
     * @return string
     */
    public function getGooglePlus()
    {
        return $this->googlePlus;
    }

    /**
     * @param string $googlePlus
     * @return UserCommunicationDataTrait
     */
    public function setGooglePlus($googlePlus)
    {
        $this->googlePlus = $googlePlus;
        return $this;
    }

    /**
     * @return string
     */
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * @param string $youtube
     * @return UserCommunicationDataTrait
     */
    public function setYoutube($youtube)
    {
        $this->youtube = $youtube;
        return $this;
    }

    /**
     * @return string
     */
    public function getDailymotion()
    {
        return $this->dailymotion;
    }

    /**
     * @param string $dailymotion
     * @return UserCommunicationDataTrait
     */
    public function setDailymotion($dailymotion)
    {
        $this->dailymotion = $dailymotion;
        return $this;
    }

    /**
     * @return string
     */
    public function getTwitch()
    {
        return $this->twitch;
    }

    /**
     * @param string $twitch
     * @return UserCommunicationDataTrait
     */
    public function setTwitch($twitch)
    {
        $this->twitch = $twitch;
        return $this;
    }

    /**
     * @return string
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * @param string $skype
     * @return UserCommunicationDataTrait
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
        return $this;
    }

    /**
     * @return string
     */
    public function getSnapChat()
    {
        return $this->snapChat;
    }

    /**
     * @param string $snapChat
     * @return UserCommunicationDataTrait
     */
    public function setSnapChat($snapChat)
    {
        $this->snapChat = $snapChat;
        return $this;
    }

    /**
     * @return string
     */
    public function getPinterest()
    {
        return $this->pinterest;
    }

    /**
     * @param string $pinterest
     * @return UserCommunicationDataTrait
     */
    public function setPinterest($pinterest)
    {
        $this->pinterest = $pinterest;
        return $this;
    }

    /**
     * @return string
     */
    public function getTrumblr()
    {
        return $this->trumblr;
    }

    /**
     * @param string $trumblr
     * @return UserCommunicationDataTrait
     */
    public function setTrumblr($trumblr)
    {
        $this->trumblr = $trumblr;
        return $this;
    }

    /**
     * @return string
     */
    public function getBlogger()
    {
        return $this->blogger;
    }

    /**
     * @param string $blogger
     * @return UserCommunicationDataTrait
     */
    public function setBlogger($blogger)
    {
        $this->blogger = $blogger;
        return $this;
    }

    /**
     * @return string
     */
    public function getReddit()
    {
        return $this->reddit;
    }

    /**
     * @param string $reddit
     * @return UserCommunicationDataTrait
     */
    public function setReddit($reddit)
    {
        $this->reddit = $reddit;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeviantArt()
    {
        return $this->deviantArt;
    }

    /**
     * @param string $deviantArt
     * @return UserCommunicationDataTrait
     */
    public function setDeviantArt($deviantArt)
    {
        $this->deviantArt = $deviantArt;
        return $this;
    }
}
