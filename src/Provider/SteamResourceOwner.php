<?php

namespace OAuth2Steam\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class SteamResourceOwner implements ResourceOwnerInterface
{
    /**
     * @var string
     */
    private $steamid;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $avatarHash;

    public function __construct(string $steamid, string $username, string $avatarHash)
    {
        $this->steamid = $steamid;
        $this->username = $username;
        $this->avatarHash = $avatarHash;
    }

    public function getId()
    {
        return $this->steamid;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getAvatarHash(): string
    {
        return $this->avatarHash;
    }

    public function getBaseAvatarUrl(): string
    {
        $hash = $this->getAvatarHash();

        return "https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/d3/{$hash}";
    }

    public function getAvatarUrl(): string
    {
        return $this->getBaseAvatarUrl() . '.jpg';
    }

    public function getMediumAvatarUrl(): string
    {
        return $this->getBaseAvatarUrl() . '_medium.jpg';
    }

    public function getFullAvatarUrl(): string
    {
        return $this->getBaseAvatarUrl() . '_full.jpg';
    }

    public function toArray()
    {
        return [];
    }
}
