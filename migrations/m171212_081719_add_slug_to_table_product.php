<?php

use yii\db\Migration;

/**
 * Class m171212_081719_add_slug_to_table_product
 */
class m171212_081719_add_slug_to_table_product extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('product', 'slug', $this->string(255)->comment('Ссылка'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('product', 'slug');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171212_081719_add_slug_to_table_product cannot be reverted.\n";

        return false;
    }
    */
}
