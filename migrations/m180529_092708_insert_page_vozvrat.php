<?php

use yii\db\Migration;

/**
 * Class m180529_092708_insert_page_vozvrat
 */
class m180529_092708_insert_page_vozvrat extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('page', [
            'title' => 'Возврат',
            'slug' => 'return'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180529_092708_insert_page_vozvrat cannot be reverted.\n";

        return false;
    }
    */
}
