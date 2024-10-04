<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'customer_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'customer_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'customer_email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'customer_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'customer_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['KSG', 'Non-KSG'],
                'null' => false,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
                'default' => 'current_timestamp()',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
                'default' => 'current_timestamp()',
                'on_update' => 'current_timestamp()',
            ],
        ]);

        // Add primary key
        $this->forge->addKey('customer_id', true);

        // Create the table
        $this->forge->createTable('customers');
    }

    public function down()
    {
        // Drop the table
        $this->forge->dropTable('customers');
    }
}
