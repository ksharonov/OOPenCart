<?php

use yii\db\Migration;

/**
 * Class m180207_100049_add_default_time_zone_to_setting
 */
class m180207_100049_add_default_time_zone_to_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'title' => 'Стандартный часовой пояс',
            'setKey' => 'DEFAULT.TIME.ZONE',
            'setValue' => 5,
            'type' => \app\models\db\Setting::TYPE_TEXT
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
        echo "m180207_100049_add_default_time_zone_to_setting cannot be reverted.\n";

        return false;
    }
    */
}
