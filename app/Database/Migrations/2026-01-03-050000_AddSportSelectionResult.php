<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSportSelectionResult extends Migration
{
    public function up()
    {
        // Add column for sport selection result
        // This stores whether sport applicants passed the sport selection round
        $this->forge->addColumn('tb_recruitstudent', [
            'recruit_sportSelectionResult' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'default' => 'รอคัดเลือก',
                'after' => 'recruit_sportPosition'
            ]
        ]);
    }

    public function down()
    {
        // Remove the column
        $this->forge->dropColumn('tb_recruitstudent', 'recruit_sportSelectionResult');
    }
}
