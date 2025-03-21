<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class PhoneAndFullNameToUser extends AbstractMigration
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

        $table = $this->table('user');

        $table->addColumn('first_name','string', ['limit'=> 30])
        ->addColumn('last_name','string', ['limit'=> 30])
        ->addColumn('phone_number','string', ['limit'=> 15])
        ->save();
    }
}
