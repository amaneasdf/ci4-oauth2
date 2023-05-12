<?php

namespace App\Libraries\OAuth2\Entities;

use App\Entities\OAuthClient;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\ClientEntityInterface;

class ClientEntity implements ClientEntityInterface {
    use EntityTrait;
    use ClientTrait;

    /**
     * Client's Secret key's hash
     *
     * @var string
     */
    protected $clientSecret;

    protected $grantType;

    public function __construct(OAuthClient $client) {
        $this->identifier   = $client->id;
        $this->redirectUri  = $client->redirect_uri;
        $this->clientSecret = $client->secret;
        $this->grantType    = $client->grant_type;
        $this->name         = $client->name;
    }

    public function isConfidential() {
        return !empty($this->clientSecret);
    }

    public function getGrantType() {
        return $this->grantType;
    }

    public function validateSecret(string $clientSecret) {
        return password_verify($clientSecret, $this->clientSecret);
    }
}
