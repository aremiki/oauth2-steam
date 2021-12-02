<?php

namespace OAuth2Steam\Client;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use OAuth2Steam\Provider\OAuth2ProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class OAuth2Client implements OAuth2ClientInterface
{
    private const DEFAULT_OPTIONS = [
        'openid.identity' => 'http://specs.openid.net/auth/2.0/identifier_select',
        'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select',
        'openid.ns' => 'http://specs.openid.net/auth/2.0',
        'openid.mode' => 'checkid_setup',
    ];

    private $router;

    private $provider;

    public function __construct(RouterInterface $router, OAuth2ProviderInterface $provider)
    {
        $this->router = $router;
        $this->provider = $provider;
    }

    /**
     * @inheritDoc
     */
    public function redirect(string $realm, string $redirectRoute, array $routeParams = []): Response
    {
        $redirectUrl = $this->generateUrl($redirectRoute, $routeParams);

        $options = array_merge(self::DEFAULT_OPTIONS, [
            'openid.realm' => $realm,
            'openid.return_to' => $redirectUrl,
        ]);
        $url = $this->provider->getAuthorizationUrl($options);

        return new RedirectResponse($url);
    }

    private function generateUrl(string $route, array $routeParams = []): string
    {
        return $this->router->generate($route, $routeParams, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * @param string|null $key
     * @return ResourceOwnerInterface
     */
    public function fetchUser(string $key = null): ResourceOwnerInterface
    {
        return $this->provider->fetchUser($key);
    }
}
