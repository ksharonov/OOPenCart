<?php

use yii\db\Migration;

/**
 * Class m180330_121717_alter_table_manufacturer
 */
class m180330_121717_alter_table_manufacturer extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn('manufacturer', 'image');
        $this->addColumn('manufacturer', 'shortDescription', $this->string(70)->comment('Краткое описание'));
        $this->addColumn('manufacturer', 'description', $this->text()->comment('Полное описание'));
        $this->addColumn('manufacturer', 'slug', $this->string(255)->comment('Ссылка'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180330_121717_alter_table_manufacturer cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180330_121717_alter_table_manufacturer cannot be reverted.\n";

        return false;
    }
    */
}
