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
     * @param string|null $personalWebSite
     * @return $this
     */
    public function setPersonalWebSite(string $personalWebSite = null)
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
     * @param string|null $facebook
     * @return $this
     */
    public function setFacebook(string $facebook = null)
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
     * @param string|null $twitter
     * @return $this
     */
    public function setTwitter(string $twitter = null)
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
     * @param string|null $googlePlus
     * @return $this
     */
    public function setGooglePlus(string $googlePlus = null)
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
     * @param string|null $youtube
     * @return $this
     */
    public function setYoutube(string $youtube = null)
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
     * @param string|null $dailymotion
     * @return $this
     */
    public function setDailymotion(string $dailymotion = null)
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
     * @param string|null $twitch
     * @return $this
     */
    public function setTwitch(string $twitch = null)
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
     * @param string|null $skype
     * @return $this
     */
    public function setSkype(string $skype = null)
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
     * @param string|null $snapChat
     * @return $this
     */
    public function setSnapChat(string $snapChat = null)
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
     * @param string|null $pinterest
     * @return $this
     */
    public function setPinterest(string $pinterest = null)
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
     * @param string|null $trumblr
     * @return $this
     */
    public function setTrumblr(string $trumblr = null)
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
     * @param string|null $blogger
     * @return $this
     */
    public function setBlogger(string $blogger = null)
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
     * @param string|null $reddit
     * @return $this
     */
    public function setReddit(string $reddit = null)
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
     * @param string|null $deviantArt
     * @return $this
     */
    public function setDeviantArt(string $deviantArt = null)
    {
        $this->deviantArt = $deviantArt;
        return $this;
    }
}
