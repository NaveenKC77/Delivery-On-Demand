<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AlterProductTableToAllowNullImagePath extends AbstractMigration
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

    public function up()
    {
        // Forward migration (if using up/down instead of change)
        $table = $this->table('product');
        $table->changeColumn('image_path', 'string', ['limit' => 255,'null' => true])
            ->update();
    }

    public function down()
    {
        // Revert the column change (reversal of the operation)
        $table = $this->table('product');
        $table->changeColumn('image_path', 'string', ['limit' => 255])  // Reverting back to the original type
        ->update();
    }
}
