<?php

use yii\db\Migration;

/**
 * Class m180214_091842_add_slug_to_post
 */
class m180214_091842_add_slug_to_post extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('post', 'slug', $this->string(255)->comment('Ссылка'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('post', 'slug');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180214_091842_add_slug_to_post cannot be reverted.\n";

        return false;
    }
    */
}
