<?php

use yii\db\Migration;

/**
 * Class m180122_052654_add_title_to_template
 */
class m180122_052654_add_title_to_template extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('template', 'title', $this->string(256)->comment('Название шаблона'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('template', 'title');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180122_052654_add_title_to_template cannot be reverted.\n";

        return false;
    }
    */
}
