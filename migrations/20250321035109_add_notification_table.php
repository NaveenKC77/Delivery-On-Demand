<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddNotificationTable extends AbstractMigration
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
        $table = $this->table('notification');

        $table->addColumn('title','string', ['limit'=> 255,'null'=> false])
        ->addColumn('content','string', ['limit'=> 255,'null'=> false])
        ->addColumn( 'is_read','boolean',['null'=> false])
        ->addColumn('user_id','integer',['null'=> false,'signed'=>false])
        ->addColumn("created_at", "datetime", ["null" => false])
        ->addColumn("updated_at", "datetime", ["null" => false])
        ->addForeignKey('user_id','user','id',['delete' => 'CASCADE'])
        ->create();
    }
}
