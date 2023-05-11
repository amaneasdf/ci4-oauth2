<?php

namespace App\Libraries\OAuth2\Entities;

use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;

class RefreshTokenEntity implements RefreshTokenEntityInterface {
    use EntityTrait;
    use RefreshTokenTrait;
}