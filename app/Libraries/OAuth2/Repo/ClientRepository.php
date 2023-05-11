<?php

namespace App\Libraries\OAuth2\Repo;

use App\Libraries\OAuth2\Entities\ClientEntity;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface {
    public function getClientEntity($clientIdentifier) {

        return new ClientEntity();
    }

    public function validateClient($clientIdentifier, $clientSecret, $grantType) {

    }
}
