<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UserMigration extends AbstractMigration
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
        $table->addColumn('username', 'string')
            ->addColumn('roles', 'json')
            ->addColumn('email', 'string')
            ->addColumn('password', 'string')
            ->create();

            if ($this->isMigratingUp()) {
            $table->insert([['username'=>'admin','roles'=>'["ROLE_ADMIN"]','email'=>'admin@dod.com','password'=>'$2y$13$eAFf0Mtfyw5OiDiwxL7f2u4uxWufZBisB27MSj2YE/8b7hbhHNWOC']])
                  ->save();
        }

    }
}
