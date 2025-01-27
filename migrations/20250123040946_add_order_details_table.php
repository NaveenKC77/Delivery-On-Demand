<?php

declare(strict_types=1);

use Phinx\Db\Action\AddColumn;
use Phinx\Migration\AbstractMigration;

final class AddOrderDetailsTable extends AbstractMigration
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
        $table = $this->table('order_details');

        $table->addColumn('order_id', 'integer', ['null' => false,'signed' => false])
        ->addColumn('name', 'string', ['null' => false])
        ->addColumn('shipping_address', 'string', ['null' => false,'limit' => 255])
        ->addColumn('postal_address', 'string', ['null' => false,'limit' => 255])
        ->addColumn('email', 'string', ['null' => false,'limit' => 255])
        ->addForeignKey('order_id', 'order', 'id', ['delete' => 'CASCADE'])
        ->create();
    }
}
