<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdmissionSchedule extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'schedule_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'schedule_year' => [
                'type'       => 'VARCHAR',
                'constraint' => '4',
                'comment'    => 'ปีการศึกษา',
            ],
            'schedule_round' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'comment'    => 'รอบการรับสมัคร',
            ],
            'schedule_level' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'comment'    => 'ระดับชั้น',
            ],
            'schedule_recruit_start' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'วันรับสมัคร (เริ่ม)',
            ],
            'schedule_recruit_end' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'วันรับสมัคร (สิ้นสุด)',
            ],
            'schedule_exam' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'วันสอบ',
            ],
            'schedule_announce' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'วันประกาศผล',
            ],
            'schedule_report' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'วันรายงานตัว',
            ],
            'schedule_note' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'หมายเหตุ',
            ],
        ]);
        $this->forge->addKey('schedule_id', true);
        $this->forge->createTable('tb_admission_schedule');

        // Insert sample data
        $data = [
            [
                'schedule_year'          => date('Y') + 543,
                'schedule_round'         => 'ห้องเรียนพิเศษ (Gifted)',
                'schedule_level'         => 'ม.1',
                'schedule_recruit_start' => date('Y-m-d 08:30:00'),
                'schedule_recruit_end'   => date('Y-m-d 16:30:00', strtotime('+5 days')),
                'schedule_exam'          => date('Y-m-d 09:00:00', strtotime('+10 days')),
                'schedule_announce'      => date('Y-m-d 09:00:00', strtotime('+15 days')),
                'schedule_report'        => date('Y-m-d 09:00:00', strtotime('+20 days')),
                'schedule_note'          => 'สมัครออนไลน์เท่านั้น',
            ],
            [
                'schedule_year'          => date('Y') + 543,
                'schedule_round'         => 'ห้องเรียนพิเศษ (Gifted)',
                'schedule_level'         => 'ม.4',
                'schedule_recruit_start' => date('Y-m-d 08:30:00'),
                'schedule_recruit_end'   => date('Y-m-d 16:30:00', strtotime('+5 days')),
                'schedule_exam'          => date('Y-m-d 09:00:00', strtotime('+10 days')),
                'schedule_announce'      => date('Y-m-d 09:00:00', strtotime('+15 days')),
                'schedule_report'        => date('Y-m-d 09:00:00', strtotime('+20 days')),
                'schedule_note'          => 'สมัครออนไลน์เท่านั้น',
            ],
            [
                'schedule_year'          => date('Y') + 543,
                'schedule_round'         => 'ห้องเรียนปกติ',
                'schedule_level'         => 'ม.1',
                'schedule_recruit_start' => date('Y-m-d 08:30:00', strtotime('+1 month')),
                'schedule_recruit_end'   => date('Y-m-d 16:30:00', strtotime('+1 month + 5 days')),
                'schedule_exam'          => date('Y-m-d 09:00:00', strtotime('+1 month + 10 days')),
                'schedule_announce'      => date('Y-m-d 09:00:00', strtotime('+1 month + 15 days')),
                'schedule_report'        => date('Y-m-d 09:00:00', strtotime('+1 month + 20 days')),
                'schedule_note'          => '-',
            ],
            [
                'schedule_year'          => date('Y') + 543,
                'schedule_round'         => 'ห้องเรียนปกติ',
                'schedule_level'         => 'ม.4',
                'schedule_recruit_start' => date('Y-m-d 08:30:00', strtotime('+1 month')),
                'schedule_recruit_end'   => date('Y-m-d 16:30:00', strtotime('+1 month + 5 days')),
                'schedule_exam'          => date('Y-m-d 09:00:00', strtotime('+1 month + 10 days')),
                'schedule_announce'      => date('Y-m-d 09:00:00', strtotime('+1 month + 15 days')),
                'schedule_report'        => date('Y-m-d 09:00:00', strtotime('+1 month + 20 days')),
                'schedule_note'          => '-',
            ],
        ];

        $db = \Config\Database::connect();
        $db->table('tb_admission_schedule')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('tb_admission_schedule');
    }
}

