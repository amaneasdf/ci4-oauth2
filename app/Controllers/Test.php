<?php

namespace App\Controllers;

use Config\Services;
use App\Libraries\Psr7Bridge;
use App\Controllers\BaseController;

class Test extends BaseController {
    public function index() {
        // initiate authorization server
        $auth    = Services::oauth2Server();
        $encrypt = Services::encrypter();
        $cache   = Services::cache();

        // Enable grant(s)
        $tokenLifetime = new \DateInterval('P50D');
        $auth->enableGrantType(new \League\OAuth2\Server\Grant\ClientCredentialsGrant(), $tokenLifetime);

        try {
            // Try to respond to the request
            $result     = $auth->respondToAccessTokenRequest(Psr7Bridge::createServerRequest($this->request), Psr7Bridge::createResponse($this->response));
            $ciResponse = Psr7Bridge::createCI4Response($result);
            $body       = json_decode($ciResponse->getBody());

            // fetch token signature
            $signature = substr($body->access_token, strrpos($body->access_token, '.') + 1);
            $token     = str_replace(".{$signature}", '', $body->access_token);

            // cache token payload
            $cacheID = hash('sha256', $signature);
            $cache->save($cacheID, $token, $body->expires_in);

            // send client only the signature of the token
            $body->access_token = $signature;
            return $ciResponse->setJSON($body);
        } catch (\League\OAuth2\Server\Exception\OAuthServerException $exception) {
            // All instances of OAuthServerException can be formatted into a HTTP response
            $httpRes = $exception->generateHttpResponse(Psr7Bridge::createResponse($this->response));
            return Psr7Bridge::createCI4Response($httpRes);
        } catch (\Error $th) {
            // other errors
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'error'   => get_class($th),
                    'message' => implode(':', [$th->getCode(), $th->getMessage()]),
                ]);
        }
    }

    public function resoServer() {
        $scopes = $this->request->header('X-OAuthScope')->getValue();
        if (!is_array($scopes) || !in_array('app', $scopes)) {
            return $this->response->setStatusCode(403);
        }

        return $this->response
            ->setStatusCode(200)
            ->setJSON([
                'code'    => '000',
                'message' => 'success',
                'data'    => [
                    'foo' => 'bar',
                ],
            ]);
    }
}