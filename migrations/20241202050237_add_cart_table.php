<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddCartTable extends AbstractMigration
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
        $table = $this->table('cart');

        $table->addColumn('quantity', 'integer', ['null' => false])
            ->addColumn('total', 'integer', ['null' => false])
            ->addColumn('discount', 'integer', ['null' => false])
            ->addColumn('customer_id', 'integer', ['null' => false, 'signed' => false])
            ->addForeignKey('customer_id', 'customer', 'id', ['delete' => 'CASCADE'])
            ->addIndex('customer_id', ['unique' => true])
            ->create();
    }
}
