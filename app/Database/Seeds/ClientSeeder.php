<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClientSeeder extends Seeder {
    public function run() {
        //
        $data = [
            'client_id'     => 'test_client',
            'client_name'   => 'Testing',
            'client_secret' => password_hash('Test123!@#', PASSWORD_BCRYPT),
            'grant_type'    => 'client_credentials',
            'created_at'    => time(),
            'updated_at'    => time(),
        ];

        $this->db->table('oauth_clients')->insert($data);
    }
}
