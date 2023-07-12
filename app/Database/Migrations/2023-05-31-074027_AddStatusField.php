<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusField extends Migration {
    public function up() {
        //  Add status field to client table
        $this->forge->addColumn('oauth_clients', [
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => '2',
                'default'    => 1,
            ],
        ]);

        // Add status field to client table
        $this->forge->addColumn('oauth_user', [
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => '2',
                'default'    => 1,
            ],
        ]);
    }

    public function down() {
        // Drop status collumns
        $this->forge->dropColumn('oauth_clients', 'status');
        $this->forge->dropColumn('oauth_user', 'status');
    }
}
