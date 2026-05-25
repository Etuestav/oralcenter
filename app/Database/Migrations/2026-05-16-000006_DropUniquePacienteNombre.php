<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropUniquePacienteNombre extends Migration
{
    public function up()
    {
        $indexes = $this->db->query('SHOW INDEX FROM paciente')->getResult();

        foreach ($indexes as $index) {
            if ($index->Key_name === 'nomb_pac' && (int) $index->Non_unique === 0) {
                $this->db->query('ALTER TABLE paciente DROP INDEX nomb_pac');
                break;
            }
        }
    }

    public function down()
    {
        $indexes = $this->db->query('SHOW INDEX FROM paciente')->getResult();

        foreach ($indexes as $index) {
            if ($index->Key_name === 'nomb_pac') {
                return;
            }
        }

        $this->db->query('ALTER TABLE paciente ADD UNIQUE INDEX nomb_pac (nomb_pac)');
    }
}
