<?php

namespace OAuth2Steam\Provider;

interface OAuth2ProviderInterface
{
    /**
     * @param array $options
     * @return string
     */
    public function getAuthorizationUrl(array $options = []): string;

    /**
     * @return string
     */
    public function getBaseAuthorizationUrl(): string;

    /**
     * @return string
     */
    public function getUserDetailsUrl(): string;
}
