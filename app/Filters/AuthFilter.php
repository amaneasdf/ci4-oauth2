<?php

namespace App\Filters;

use App\Libraries\Psr7Bridge;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface {
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param  RequestInterface $request
     * @param  array|null       $arguments
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null) {
        $req      = Psr7Bridge::createServerRequest($request);
        $server   = \Config\Services::oauth2ResServer();
        $response = \Config\Services::response();

        // try to validate incoming request
        try {
            $req = $server->validateAuthenticatedRequest($req);
        } catch (\League\OAuth2\Server\Exception\OAuthServerException $ex) {
            $httpRes = $ex->generateHttpResponse(Psr7Bridge::createResponse($response));
            return Psr7Bridge::createCI4Response($httpRes);
        } catch (\Error $er) {
            // other errors
            return $response
                ->setStatusCode(500)
                ->setJSON([
                    'error'   => 'Error',
                    'message' => implode(':', [$er->getCode(), $er->getMessage()]),
                ]);
        }

        // get client credential from validated token
        $tokenID  = $req->getAttribute('oauth_access_token_id');
        $clientID = $req->getAttribute('oauth_client_id');
        $uid      = $req->getAttribute('oauth_user_id');
        $scope    = $req->getAttribute('oauth_scopes');

        // other validation here

        // Set client credentials into the request header to be relayed to other filters and/or controller
        $request
            ->setHeader('X-OAuthToken', $tokenID)
            ->setHeader('X-ClientID', $clientID)
            ->setHeader('X-UID', $uid)
            ->setHeader('X-OAuthScope', $scope);
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param  RequestInterface  $request
     * @param  ResponseInterface $response
     * @param  array|null        $arguments
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
        //
    }
}
