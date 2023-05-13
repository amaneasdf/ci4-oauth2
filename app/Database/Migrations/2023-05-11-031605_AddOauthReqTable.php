<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOauthReqTable extends Migration {
    public function up() {
        // Clients Table
        $this->forge->addField([
            'client_id'     => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => false,
            ],
            'client_name'   => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => true,
            ],
            'client_secret' => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => true,
            ],
            'redirect_uri'  => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'grant_type'    => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => true,
            ],
            'scope'         => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'user_id'       => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => true,
            ],
            'created_at'    => [
                'type'       => 'INT',
                'constraint' => '11',
                'null'       => true,
            ],
            'updated_at'    => [
                'type'       => 'INT',
                'constraint' => '11',
                'null'       => true,
            ],
            'deleted_at'    => [
                'type'       => 'INT',
                'constraint' => '11',
                'null'       => true,
            ],
        ]);
        $this->forge->addPrimaryKey('client_id');
        $this->forge->createTable('oauth_clients');

        // Access Token Table
        $this->forge->addField([
            'access_token' => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => false,
            ],
            'client_id'    => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => false,
            ],
            'user_id'      => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => true,
            ],
            'expires'      => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
            'scope'        => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'null'       => true,
            ],
            'updated_at'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'null'       => true,
            ],
        ]);
        $this->forge->addPrimaryKey('access_token');
        $this->forge->createTable('oauth_access_tokens');

        // Authorization Code Table
        $this->forge->addField([
            'authorization_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '40',
                'null'       => false,
            ],
            'client_id'          => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => false,
            ],
            'user_id'            => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => true,
            ],
            'redirect_uri'       => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'expires'            => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
            'scope'              => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'id_token'           => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at'         => [
                'type'       => 'INT',
                'constraint' => '11',
                'null'       => true,
            ],
            'updated_at'         => [
                'type'       => 'INT',
                'constraint' => '11',
                'null'       => true,
            ],
        ]);
        $this->forge->addPrimaryKey('authorization_code');
        $this->forge->createTable('oauth_authorization_codes');

        // Refresh Token Table
        $this->forge->addField([
            'refresh_token' => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => false,
            ],
            'client_id'     => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => false,
            ],
            'user_id'       => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => true,
            ],
            'expires'       => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
            'scope'         => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at'    => [
                'type'       => 'INT',
                'constraint' => '11',
                'null'       => true,
            ],
            'updated_at'    => [
                'type'       => 'INT',
                'constraint' => '11',
                'null'       => true,
            ],
        ]);
        $this->forge->addPrimaryKey('refresh_token');
        $this->forge->createTable('oauth_refresh_tokens');

        // User Table
        $this->forge->addField([
            'username'       => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => true,
            ],
            'password'       => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => true,
            ],
            'first_name'     => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => true,
            ],
            'last_name'      => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => true,
            ],
            'email'          => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => true,
            ],
            'email_verified' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'null'       => true,
            ],
            'scope'          => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at'     => [
                'type'       => 'INT',
                'constraint' => '11',
                'null'       => true,
            ],
            'updated_at'     => [
                'type'       => 'INT',
                'constraint' => '11',
                'null'       => true,
            ],
            'deleted_at'     => [
                'type'       => 'INT',
                'constraint' => '11',
                'null'       => true,
            ],
        ]);
        $this->forge->addPrimaryKey('username');
        $this->forge->createTable('oauth_user');

        // Scope Table
        $this->forge->addField([
            'scope'      => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => false,
            ],
            'is_default' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'null'       => true,
            ],
            'created_at' => [
                'type'       => 'INT',
                'constraint' => '11',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'INT',
                'constraint' => '11',
                'null'       => true,
            ],
            'deleted_at' => [
                'type'       => 'INT',
                'constraint' => '11',
                'null'       => true,
            ],
        ]);
        $this->forge->addPrimaryKey('scope');
        $this->forge->createTable('oauth_scope');

        // JWT Table
        $this->forge->addField([
            'client_id'  => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => false,
            ],
            'subject'    => [
                'type'       => 'VARCHAR',
                'constraint' => '80',
                'null'       => true,
            ],
            'public_key' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'created_at' => [
                'type'       => 'INT',
                'constraint' => '11',
                'null'       => true,
            ],
            'updated_at' => [
                'type'       => 'INT',
                'constraint' => '11',
                'null'       => true,
            ],
        ]);
        $this->forge->createTable('oauth_jwt');
    }

    public function down() {
        // Drop all table
        $this->forge->dropTable('oauth_clients');
        $this->forge->dropTable('oauth_access_tokens');
        $this->forge->dropTable('oauth_authorization_codes');
        $this->forge->dropTable('oauth_refresh_tokens');
        $this->forge->dropTable('oauth_user');
        $this->forge->dropTable('oauth_scope');
        $this->forge->dropTable('oauth_jwt');
    }
}
