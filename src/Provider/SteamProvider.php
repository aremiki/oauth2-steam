<?php

namespace OAuth2Steam\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class SteamProvider extends AbstractProvider
{

    protected function checkResponse(array $response)
    {
        if (
            $response['openid_claimed_id'] !== $response['openid_identity']
            || $response[ 'openid_op_endpoint' ] !== 'https://steamcommunity.com/openid/login'
            || $response[ 'openid_ns' ] !== 'http://specs.openid.net/auth/2.0'
            || preg_match( '/^https?:\/\/steamcommunity.com\/openid\/id\/(7656119[0-9]{10})\/?$/', $response[ 'openid_identity' ]) !== 1
        ) {
            throw new \UnexpectedValueException('change exception type');
        }
    }

    protected function createResourceOwner(array $response, string $key = null): ResourceOwnerInterface
    {
        preg_match( '/^https?:\/\/steamcommunity.com\/openid\/id\/(7656119[0-9]{10})\/?$/', $response[ 'openid_identity' ], $matches);

        $steamid = $matches[1] ?? null;
        $username = '';
        $avatarHash = '';


        if (null === $key)
        {
            $url = str_replace(['{key}', '{steamid}'], [$key, $steamid], $this->getUserDetailsUrl());
            $response = $this->getHttpClient()->request('GET', $url);
            $response->toArray(false);
        }

        return new SteamResourceOwner($steamid, $username, $avatarHash);
    }
}
