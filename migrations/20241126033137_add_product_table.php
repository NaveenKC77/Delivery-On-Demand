<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddProductTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table("product");

        $table->addColumn("name", "string", ["limit" => 255, 'null' => false])
            ->addColumn('slug', 'string', ['limit' => 255])
            ->addColumn('description', 'text', ['null' => false])
            ->addColumn('price', 'integer', ['limit' => 10, 'null' => false])
            ->addColumn('stock', 'integer', ['null' => false])
            ->addColumn('image_path', 'string', ['limit' => 255, 'null' => false])
            ->addColumn("created_at", "datetime", ["null" => false])
            ->addColumn("updated_at", "datetime", ["null" => false])
            ->create();
    }
}
