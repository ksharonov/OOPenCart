<?php

use yii\db\Migration;

/**
 * Class m180125_050013_add_title_column
 */
class m180125_050013_add_title_column extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('file', 'title', $this->string(256)->comment('Название файла'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('file', 'title');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180125_050013_add_title_column cannot be reverted.\n";

        return false;
    }
    */
}
