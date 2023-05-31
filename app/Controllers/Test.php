<?php

namespace App\Controllers;

use Config\Services;
use App\Libraries\Psr7Bridge;
use App\Controllers\BaseController;

class Test extends BaseController {
    public function index() {
        // initiate authorization server
        $auth = Services::oauth2Server();

        // Enable grant(s)
        $auth->enableGrantType(new \League\OAuth2\Server\Grant\ClientCredentialsGrant(), new \DateInterval('P50D'));

        try {
            // Try to respond to the request
            $result = $auth->respondToAccessTokenRequest(Psr7Bridge::createServerRequest($this->request), Psr7Bridge::createResponse($this->response));
            return Psr7Bridge::createCI4Response($result);
        } catch (\League\OAuth2\Server\Exception\OAuthServerException $exception) {
            // All instances of OAuthServerException can be formatted into a HTTP response
            $httpRes = $exception->generateHttpResponse(Psr7Bridge::createResponse($this->response));
            return Psr7Bridge::createCI4Response($httpRes);
        } catch (\Error $th) {
            // other errors
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'error'   => 'Error',
                    'message' => implode(':', [$th->getCode(), $th->getMessage()]),
                ]);
        }
    }
}