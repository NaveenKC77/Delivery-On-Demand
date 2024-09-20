<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class MyNewMigration extends AbstractMigration
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
        // create the table
        $table = $this->table('user_logins');
        $table->addColumn('user_id', 'integer')
            ->addColumn('created', 'datetime')
            ->create();
        if ($this->isMigratingUp()) {
            $table->insert([['user_id' => 1, 'created' => '2020-01-19 03:14:07']])
                ->save();
        }
    }
}
