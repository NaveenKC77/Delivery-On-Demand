<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddOrderTable extends AbstractMigration
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
        $table = $this->table("order");

        $table->addColumn('customer_id', 'integer', ['signed' => false])
        ->addColumn('subtotal', 'integer', ['null' => false])
        ->addColumn('tax', 'integer', ['null' => false])
        ->addColumn('shipping', 'integer', ['null' => false])
        ->addColumn('total', 'integer', ['null' => false])
        ->addForeignKey('customer_id', 'customer', 'id', ['delete' => 'SET NULL'])
        ->create();

    }
}
