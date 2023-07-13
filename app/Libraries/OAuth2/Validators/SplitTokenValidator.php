<?php

namespace App\Libraries\OAuth2\Validators;

use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;

class SplitTokenValidator extends BearerTokenValidator {

    public function validateAuthorization(ServerRequestInterface $request) {
        if ($request->hasHeader('authorization') === false) {
            throw OAuthServerException::accessDenied('Missing "Authorization" header');
        }

        $header    = $request->getHeader('authorization');
        $signature = \trim((string) \preg_replace('/^\s*Bearer\s/', '', $header[0]));

        // Fetch complete payload from cache
        $cache = \Config\Services::cache();
        $token = $cache->get(hash('sha256', $signature));
        if (null === $token) {
            throw OAuthServerException::accessDenied('Access token could not be verified or outdated');
        }

        // compile JWT token and put it back in request header
        $jwt        = $token . '.' . $signature;
        $modRequest = $request->withHeader('authorization', "Bearer {$jwt}");

        return parent::validateAuthorization($modRequest);
    }
}