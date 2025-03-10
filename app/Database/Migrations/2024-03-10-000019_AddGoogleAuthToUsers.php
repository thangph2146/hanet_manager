<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGoogleAuthToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'u_email' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => true,
                'after'          => 'u_username'
            ],
            'u_google_id' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => true,
                'after'          => 'u_email'
            ],
            'u_FullName' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => true,
                'after'          => 'u_FirstName'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'u_email');
        $this->forge->dropColumn('users', 'u_google_id');
        $this->forge->dropColumn('users', 'u_FullName');
    }
} 