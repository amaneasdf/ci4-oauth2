<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class OAuthClient extends Entity {
    protected $datamap = [
        'id'     => 'client_id',
        'secret' => 'client_secret',
        'name'   => 'client_name',
    ];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [];
}
