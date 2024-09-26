<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateProductTable extends AbstractMigration
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
        $table =$this->table('product',['signed'=>false]);
        $table->addColumn('name','string')
        ->addColumn('description','text')
        ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
        ->addColumn('available','boolean')
        ->addColumn('category_id','integer',['signed'=>false])
        ->addForeignKey('category_id', 'category', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
            ->create();

        // if($this->isMigratingUp()){
        //     $table->insert([['name'=>'testProduct','description'=>'test product description','available'=>0,'category_id'=>1]]);
        // }

    }
}
