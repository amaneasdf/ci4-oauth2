<?php

namespace App\Libraries\OAuth2\Repo;

use CodeIgniter\Database\ConnectionInterface;
use App\Libraries\OAuth2\Entities\AccessTokenEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

class AccessTokenRepository implements AccessTokenRepositoryInterface {
    /**
     * Database connection
     *
     * @var null|\CodeIgniter\Database\ConnectionInterface
     */
    protected $db;

    public function __construct( ? ConnectionInterface $db = null) {
        if (null === $db) {
            $dbGroup = (new \Config\Database())->defaultGroup;
            $db      = \Config\Database::connect($dbGroup);
        }

        $this->db = $db;
    }

    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null) {
        // initiate token entity
        $token = new AccessTokenEntity();

        $token->setClient($clientEntity);
        $token->setUserIdentifier($userIdentifier);

        // Add scopes to token
        foreach ($scopes as $scope) {
            $token->addScope($scope);
        }

        return $token;
    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity) {

    }

    public function revokeAccessToken($tokenId) {

    }

    public function isAccessTokenRevoked($tokenId) {

    }
}