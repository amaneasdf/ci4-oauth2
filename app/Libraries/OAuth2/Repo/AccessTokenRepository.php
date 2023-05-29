<?php

namespace App\Libraries\OAuth2\Repo;

use App\Models\OAuthAccessTokenModel;
use CodeIgniter\Database\ConnectionInterface;
use App\Libraries\OAuth2\Entities\AccessTokenEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;

class AccessTokenRepository implements AccessTokenRepositoryInterface {
    /**
     * Database connection
     *
     * @var null|\CodeIgniter\Database\ConnectionInterface
     */
    protected $db;

    public function __construct( ? ConnectionInterface $db = null) {
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
        // prepare data
        $tokenData = [
            'access_token' => $accessTokenEntity->getIdentifier(),
            'client_id'    => $accessTokenEntity->getClient()->getIdentifier(),
            'user_id'      => $accessTokenEntity->getUserIdentifier(),
            'expires'      => $accessTokenEntity->getExpiryDateTime()->format('Y-m-d H:i:s'),
            'scope'        => [],
        ];
        foreach ($accessTokenEntity->getScopes() as $scope) {
            $tokenData['scope'] = $scope->getIdentifier();
        }
        $tokenData['scope'] = implode(' ', $tokenData['scope']);

        // save token data
        try {
            // Initiate Model
            $tokenModel = new OAuthAccessTokenModel($this->db);
            $tokenModel->insert($tokenData);
        } catch (\Throwable $th) {
            //throw $th;
            throw new UniqueTokenIdentifierConstraintViolationException($th->getMessage(), $th->getCode(), get_class($th));
        }
    }

    public function revokeAccessToken($tokenId) {
        // Initiate Model
        $tokenModel = new OAuthAccessTokenModel($this->db);
        $tokenModel->update($tokenId, ['is_evoked' => true]);
        // $tokenModel->delete($tokenId);
    }

    public function isAccessTokenRevoked($tokenId) {
        // Initiate Model
        $tokenModel = new OAuthAccessTokenModel($this->db);
        $token      = $tokenModel->find($tokenId);
        return $token['is_evoked'] ?? true;
    }
}