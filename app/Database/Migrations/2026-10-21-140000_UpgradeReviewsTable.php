<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpgradeReviewsTable extends Migration
{
    public function up()
    {
        $fields = [
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
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];

        foreach ($fields as $name => $definition) {
            if (!$this->db->fieldExists($name, 'reviews')) {
                $this->forge->addColumn('reviews', [$name => $definition]);
            }
        }

        if ($this->db->fieldExists('review', 'reviews')) {
            $this->db->query('UPDATE reviews SET comment = review WHERE comment IS NULL');
        }
    }

    public function down()
    {
        // Keep review data when rolling back this compatibility migration.
    }
}
