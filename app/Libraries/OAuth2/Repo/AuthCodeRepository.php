<?php

namespace App\Libraries\OAuth2\Repo;

use App\Models\OAuth2AuthCodeModel;
use CodeIgniter\Database\ConnectionInterface;
use App\Libraries\OAuth2\Entities\AuthCodeEntity;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;

class AuthCodeRepository implements AuthCodeRepositoryInterface {
    /**
     * Database connection
     *
     * @var null|\CodeIgniter\Database\ConnectionInterface
     */
    protected $db;

    public function __construct( ? ConnectionInterface $db = null) {
        $this->db = $db;
    }

    public function getNewAuthCode() {
        return new AuthCodeEntity();
    }

    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity) {
        // prepare data
        $tokenData = [
            'authorization_code' => $authCodeEntity->getIdentifier(),
            'client_id'          => $authCodeEntity->getClient()->getIdentifier(),
            'user_id'            => $authCodeEntity->getUserIdentifier(),
            'redirect_uri'       => $authCodeEntity->getRedirectUri(),
            'expires'            => $authCodeEntity->getExpiryDateTime(),
            'scope'              => implode(' ', $authCodeEntity->getScopes()),
        ];

        try {
            // init model
            $authCodeModel = new OAuth2AuthCodeModel($this->db);
            // store data
            $authCodeModel->insert($tokenData);
        } catch (\Throwable $th) {
            throw new UniqueTokenIdentifierConstraintViolationException($th->getMessage(), $th->getCode(), get_class($th));
        }
    }

    public function revokeAuthCode($codeId) {
        // init model
        $authCodeModel = new OAuth2AuthCodeModel($this->db);
        $authCodeModel->update($codeId, [
            'isRevoked' => true,
        ]);
    }

    public function isAuthCodeRevoked($codeId) {
        // init model
        $authCodeModel = new OAuth2AuthCodeModel($this->db);
        $authCode      = $authCodeModel->find($codeId);
        return $authCode['isRevoked'] ?? true;
    }
}
