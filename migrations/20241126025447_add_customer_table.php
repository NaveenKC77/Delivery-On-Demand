<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddCustomerTable extends AbstractMigration
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
        $table = $this->table("customer");

        $table->addColumn('address', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('user_id', 'integer', ['null' => false, 'signed' => false])
            ->addIndex('user_id', ['unique' => true])
            ->addForeignKey('user_id', 'user', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}
