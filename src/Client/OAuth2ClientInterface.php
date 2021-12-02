<?php

namespace OAuth2Steam\Client;

use Symfony\Component\HttpFoundation\Response;

interface OAuth2ClientInterface
{
    /**
     * @param string $realm
     * @param string $redirectRoute
     * @return Response
     */
    public function redirect(string $realm, string $redirectRoute): Response;
}
