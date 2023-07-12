<?php

namespace Config;

use League\OAuth2\Server\CryptKey;
use CodeIgniter\Config\BaseService;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResourceServer;
use CodeIgniter\Database\ConnectionInterface;
use League\OAuth2\Server\AuthorizationServer;
use App\Libraries\OAuth2\Repo\ScopeRepository;
use App\Libraries\OAuth2\Repo\ClientRepository;
use App\Libraries\OAuth2\Repo\AccessTokenRepository;

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

    /**
     * @param  bool                     $getShared
     * @param  null|ConnectionInterface $db
     * @return AuthorizationServer
     */
    public static function oauth2Server($getShared = true, ?ConnectionInterface $db = null) {
        if ($getShared) {
            return static::getSharedInstance('oauth2Server');
        }

        // prepare repositories
        $clientRepo = new ClientRepository($db);
        $tokenRepo  = new AccessTokenRepository($db);
        $scopeRepo  = new ScopeRepository($db);

        $privatekey = new CryptKey(__DIR__ . $_ENV['encryption.openssl.keypath']);
        $encryptkey = hex2bin($_ENV['encryption.openssl.key']);
        $server     = new AuthorizationServer(
            $clientRepo,
            $tokenRepo,
            $scopeRepo,
            $privatekey,
            $encryptkey
        );

        // pass emitted OAuth2 event to Codeignter's event handler
        $server->getEmitter()
            ->addListener(
                'client.authentication.failed',
                function (RequestEvent $event) {
                    \CodeIgniter\Events\Events::trigger('oauth2.client.authentication.failed', $event);
                }
            )
            ->addListener(
                'user.authentication.failed',
                function (RequestEvent $event) {
                    \CodeIgniter\Events\Events::trigger('oauth2.user.authentication.failed', $event);
                }
            );

        return $server;
    }

    /**
     * @param  bool                     $getShared
     * @param  null|ConnectionInterface $db
     * @return ResourceServer
     */
    public static function oauth2ResServer($getShared = true, ?ConnectionInterface $db = null) {
        if ($getShared) {
            return static::getSharedInstance('oauth2ResServer');
        }

        $publickey = new CryptKey(__DIR__ . $_ENV['encryption.openssl.public']);
        $server    = new ResourceServer(new AccessTokenRepository($db), $publickey);

        return $server;
    }
}
