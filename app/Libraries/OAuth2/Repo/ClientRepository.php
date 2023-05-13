<?php

namespace App\Libraries\OAuth2\Repo;

use App\Models\OAuthClientModel;
use CodeIgniter\Database\ConnectionInterface;
use App\Libraries\OAuth2\Entities\ClientEntity;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface {
    /**
     * Database connection
     *
     * @var null|\CodeIgniter\Database\ConnectionInterface
     */
    protected $db;

    public function __construct( ? ConnectionInterface $db = null) {
        $this->db = $db;
    }

    public function getClientEntity($clientIdentifier) {
        $clientModel = new OAuthClientModel($this->db);
        $clientData  = $clientModel->find($clientIdentifier);
        return null === $clientData ? null : new ClientEntity($clientData);
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType) {
        /**
         * @var \App\Libraries\OAuth2\Entities\ClientEntity $client
         */
        $client = $this->getClientEntity($clientIdentifier);
        if (null === $client) {
            return false;
        }

        // validate client secret
        if ($client->isConfidential()) {
            if (null === $clientSecret || !$client->validateSecret($clientSecret)) {
                return false;
            }
        }

        // validate grant
        $clientGrant = $client->getGrantType();
        if (null !== $clientGrant && $grantType !== $clientGrant) {
            return false;
        }

        return true;
    }
}
