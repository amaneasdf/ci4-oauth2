<?php

namespace App\Libraries\OAuth2\Entities;

use League\OAuth2\Server\Entities\Traits\ScopeTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

class ScopeEntity implements ScopeEntityInterface {
    use EntityTrait;
    use ScopeTrait;
}
