<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'order_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'order_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'customer_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'rating' => [
                'type'       => 'TINYINT',
            ],
            'aspects' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'items' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tags' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'comment' => [
                'type'       => 'VARCHAR',
                'constraint' => 300,
                'null'       => true,
            ],
            'recommend' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
            ],
            'is_published' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'is_anonymous' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null'       => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('order_number', false, true);
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('reviews', true);
    }

    public function down()
    {
        $this->forge->dropTable('reviews', true);
    }
}
