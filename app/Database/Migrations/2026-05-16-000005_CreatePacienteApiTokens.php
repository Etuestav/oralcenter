<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePacienteApiTokens extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_token' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'codi_pac' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'token_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
            ],
            'device_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 120,
                'null'       => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'last_used_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'revoked_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_token', true);
        $this->forge->addKey('codi_pac');
        $this->forge->addUniqueKey('token_hash');
        $this->forge->createTable('paciente_api_tokens', true);
    }

    public function down()
    {
        $this->forge->dropTable('paciente_api_tokens', true);
    }
}
