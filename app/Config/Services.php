<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use League\OAuth2\Server\ResourceServer;
use League\OAuth2\Server\AuthorizationServer;
use App\Libraries\OAuth2\Repo\ClientRepository;
use App\Libraries\OAuth2\Repo\AccessTokenRepository;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService {
    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */

    public static function oauth2Server($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('oauth2Server');
        }

        // prepare repositories
        $clientRepo = new ClientRepository();
        $tokenRepo  = new AccessTokenRepository();

        $privatekey = $_ENV['encryption.openssl.keypath'];
        $encrytkey  = '';
        $server     = new AuthorizationServer(
            $clientRepo,
            $tokenRepo,
            null,
            $privatekey,
            $encrytkey
        );

        // set grant
        $server->enableGrantType(new ClientCredentialsGrant(), new \DateInterval('P50D'));

        return $server;
    }

    public static function oauth2ResServer($getShared = true) {
        if ($getShared) {
            return static::getSharedInstance('oauth2Server');
        }

        $publickey = $_ENV['encryption.openssl.keypath']; // same path because same host
        $server    = new ResourceServer(new AccessTokenRepository(), $publickey);

        return $server;
    }
}
