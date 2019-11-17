<?php

namespace ProjetNormandie\UserBundle\Entity;

interface UserCommunicationDataInterface
{
    /**
     * @return mixed
     */
    public function getPersonalWebSite();

    /**
     * @param mixed $personalWebSite
     * @return UserCommunicationDataTrait
     */
    public function setPersonalWebSite($personalWebSite);

    /**
     * @return mixed
     */
    public function getFacebook();

    /**
     * @param mixed $facebook
     * @return UserCommunicationDataTrait
     */
    public function setFacebook($facebook);

    /**
     * @return mixed
     */
    public function getTwitter();

    /**
     * @param mixed $twitter
     * @return UserCommunicationDataTrait
     */
    public function setTwitter($twitter);

    /**
     * @return mixed
     */
    public function getGooglePlus();

    /**
     * @param mixed $googlePlus
     * @return UserCommunicationDataTrait
     */
    public function setGooglePlus($googlePlus);

    /**
     * @return mixed
     */
    public function getYoutube();

    /**
     * @param mixed $youtube
     * @return UserCommunicationDataTrait
     */
    public function setYoutube($youtube);

    /**
     * @return mixed
     */
    public function getDailymotion();

    /**
     * @param mixed $dailymotion
     * @return UserCommunicationDataTrait
     */
    public function setDailymotion($dailymotion);

    /**
     * @return mixed
     */
    public function getTwitch();

    /**
     * @param mixed $twitch
     * @return UserCommunicationDataTrait
     */
    public function setTwitch($twitch);

    /**
     * @return mixed
     */
    public function getSkype();

    /**
     * @param mixed $skype
     * @return UserCommunicationDataTrait
     */
    public function setSkype($skype);

    /**
     * @return mixed
     */
    public function getSnapChat();

    /**
     * @param mixed $snapChat
     * @return UserCommunicationDataTrait
     */
    public function setSnapChat($snapChat);

    /**
     * @return mixed
     */
    public function getPinterest();

    /**
     * @param mixed $pinterest
     * @return UserCommunicationDataTrait
     */
    public function setPinterest($pinterest);

    /**
     * @return mixed
     */
    public function getTrumblr();

    /**
     * @param mixed $trumblr
     * @return UserCommunicationDataTrait
     */
    public function setTrumblr($trumblr);

    /**
     * @return mixed
     */
    public function getBlogger();

    /**
     * @param mixed $blogger
     * @return UserCommunicationDataTrait
     */
    public function setBlogger($blogger);

    /**
     * @return mixed
     */
    public function getReddit();

    /**
     * @param mixed $reddit
     * @return UserCommunicationDataTrait
     */
    public function setReddit($reddit);

    /**
     * @return mixed
     */
    public function getDeviantArt();

    /**
     * @param mixed $deviantArt
     * @return UserCommunicationDataTrait
     */
    public function setDeviantArt($deviantArt);
}
