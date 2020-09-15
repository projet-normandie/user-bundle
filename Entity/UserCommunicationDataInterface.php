<?php

namespace ProjetNormandie\UserBundle\Entity;

interface UserCommunicationDataInterface
{
    /**
     * @return string
     */
    public function getPersonalWebSite();

    /**
     * @param string $personalWebSite
     * @return UserCommunicationDataTrait
     */
    public function setPersonalWebSite(string $personalWebSite);

    /**
     * @return string
     */
    public function getFacebook();

    /**
     * @param string $facebook
     * @return UserCommunicationDataTrait
     */
    public function setFacebook(string $facebook);

    /**
     * @return string
     */
    public function getTwitter();

    /**
     * @param string $twitter
     * @return UserCommunicationDataTrait
     */
    public function setTwitter(string $twitter);

    /**
     * @return string
     */
    public function getGooglePlus();

    /**
     * @param string $googlePlus
     * @return UserCommunicationDataTrait
     */
    public function setGooglePlus(string $googlePlus);

    /**
     * @return string
     */
    public function getYoutube();

    /**
     * @param string $youtube
     * @return UserCommunicationDataTrait
     */
    public function setYoutube(string $youtube);

    /**
     * @return string
     */
    public function getDailymotion();

    /**
     * @param string $dailymotion
     * @return UserCommunicationDataTrait
     */
    public function setDailymotion(string $dailymotion);

    /**
     * @return string
     */
    public function getTwitch();

    /**
     * @param string $twitch
     * @return UserCommunicationDataTrait
     */
    public function setTwitch(string $twitch);

    /**
     * @return string
     */
    public function getSkype();

    /**
     * @param string $skype
     * @return UserCommunicationDataTrait
     */
    public function setSkype(string $skype);

    /**
     * @return string
     */
    public function getSnapChat();

    /**
     * @param string $snapChat
     * @return UserCommunicationDataTrait
     */
    public function setSnapChat(string $snapChat);

    /**
     * @return string
     */
    public function getPinterest();

    /**
     * @param string $pinterest
     * @return UserCommunicationDataTrait
     */
    public function setPinterest(string $pinterest);

    /**
     * @return string
     */
    public function getTrumblr();

    /**
     * @param string $trumblr
     * @return UserCommunicationDataTrait
     */
    public function setTrumblr(string $trumblr);

    /**
     * @return string
     */
    public function getBlogger();

    /**
     * @param string $blogger
     * @return UserCommunicationDataTrait
     */
    public function setBlogger(string $blogger);

    /**
     * @return string
     */
    public function getReddit();

    /**
     * @param string $reddit
     * @return UserCommunicationDataTrait
     */
    public function setReddit(string $reddit);

    /**
     * @return string
     */
    public function getDeviantArt();

    /**
     * @param string $deviantArt
     * @return UserCommunicationDataTrait
     */
    public function setDeviantArt(string $deviantArt);
}
