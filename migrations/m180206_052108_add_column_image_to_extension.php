<?php

use yii\db\Migration;

/**
 * Class m180206_052108_add_column_image_to_extension
 */
class m180206_052108_add_column_image_to_extension extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('extension', 'image', $this->string(512)->comment('Изображение или лого'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('extension', 'image');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180206_052108_add_column_image_to_extension cannot be reverted.\n";

        return false;
    }
    */
}
