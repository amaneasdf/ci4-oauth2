<?php

namespace App\Libraries\OAuth2\Entities;

use CodeIgniter\Entity\Entity;
use League\OAuth2\Server\Entities\ClientEntityInterface;

class ClientEntity extends Entity implements ClientEntityInterface {
    protected $datamap = [
        'identifier'   => 'client_id',
        'clientSecret' => 'client_secret',
        'grantType'    => 'grant_type',
        'name'         => 'client_name',
    ];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'status' => 'int',
    ];
    protected $allowedFields = [
        'client_id',
        'client_name',
        'redirect_uri',
        'grant_type',
        'scope',
        'user_id',
        'status',
    ];

    public function __get(string $key) {
        if (!in_array($this->mapProperty($key), $this->allowedFields, true)) {
            return null;
        }

        return parent::__get($key);
    }

    public function getIdentifier() {
        return $this->attributes['client_id'];
    }

    public function setIdentifier($identifier) {
        parent::__set('identifier', $identifier);
    }

    public function getName() {
        return $this->attributes['name'];
    }

    public function getRedirectUri() {
        return $this->attributes['redirect_uri'];
    }

    public function isConfidential() {
        return !empty($this->attributes['client_secret']);
    }

    public function getGrantType() {
        return $this->attributes['grant_type'];
    }

    public function validateSecret(string $clientSecret) {
        return password_verify($clientSecret, $this->attributes['client_secret']);
    }
}
