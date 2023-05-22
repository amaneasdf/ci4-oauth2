<?php

namespace App\Libraries\OAuth2\Repo;

use App\Models\OAuthScopeModel;
use CodeIgniter\Database\ConnectionInterface;
use App\Libraries\OAuth2\Entities\ScopeEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

class ScopeRepository implements ScopeRepositoryInterface {
    /**
     * Database connection
     *
     * @var null|\CodeIgniter\Database\ConnectionInterface
     */
    protected $db;

    public function __construct( ? ConnectionInterface $db = null) {
        $this->db = $db;
    }

    public function getScopeEntityByIdentifier($identifier) {
        // fetch scope data from DB
        $scopeModel = new OAuthScopeModel($this->db);
        if (($scopeData = $scopeModel->find($identifier)) === null) {
            return null;
        }

        return new ScopeEntity($scopeData['scope']);
    }

    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null) {
        // Integration with existing app's permission system
        // TODO : Code Here

        // Because currently there's no active permission system revolving scope within the app
        // we'll just pass the original requested scopes
        return $scopes;
    }
}