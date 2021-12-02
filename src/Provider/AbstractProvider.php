<?php

namespace OAuth2Steam\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractProvider implements OAuth2ProviderInterface
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(HttpClientInterface $client, RequestStack $requestStack)
    {
        $this->httpClient = $client;
        $this->requestStack = $requestStack;
    }

    public function getBaseAuthorizationUrl(): string
    {
        return 'https://steamcommunity.com/openid/login';
    }

    public function getUserDetailsUrl(): string
    {
        return 'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v2?key={key}&steamids={steamid}';
    }

    public function getAuthorizationUrl(array $options = []): string
    {
        $base   = $this->getBaseAuthorizationUrl();
        $query  = $this->getAuthorizationQuery($options);

        return $this->appendQuery($base, $query);
    }

    /**
     * Appends a query string to a URL.
     *
     * @param string $url The URL to append the query to
     * @param string $query The HTTP query string
     * @return string The resulting URL
     */
    protected function appendQuery(string $url, string $query): string
    {
        $query = trim($query, '?&');

        if ($query) {
            $glue = strstr($url, '?') === false ? '?' : '&';
            return $url . $glue . $query;
        }

        return $url;
    }

    /**
     * Builds the authorization URL's query string.
     *
     * @param  array $params Query parameters
     * @return string Query string
     */
    protected function getAuthorizationQuery(array $params): string
    {
        return $this->buildQueryString($params);
    }

    /**
     * Build a query string from an array.
     *
     * @param array $params
     *
     * @return string
     */
    protected function buildQueryString(array $params): string
    {
        return http_build_query($params, null, '&', \PHP_QUERY_RFC3986);
    }

    protected function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    /**
     * @param string|null $key
     * @return ResourceOwnerInterface
     */
    public function fetchUser(string $key = null): ResourceOwnerInterface
    {
        $request = $this->requestStack->getCurrentRequest();

        $this->checkResponse($request->query->all());
        return $this->createResourceOwner($request->query->all(), $key);
    }

    abstract protected function checkResponse(array $response);

    abstract protected function createResourceOwner(array $response, string $key = null): ResourceOwnerInterface;
}
