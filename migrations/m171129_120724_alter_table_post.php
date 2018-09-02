<?php

use yii\db\Migration;

/**
 * Class m171129_120724_alter_table_post
 */
class m171129_120724_alter_table_post extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('post','thumbnail', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('post', 'thumbnail');

        return true;
    }
}
