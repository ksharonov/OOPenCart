<?php

use yii\db\Migration;

/**
 * Class m180511_071032_insert_slider_param
 */
class m180511_071032_insert_slider_param extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'title' => 'Слайды для главной страницы',
            'setKey' => 'SITE.SLIDER.IMAGES',
            'setValue' => '{}',
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
        echo "m180511_071032_insert_slider_param cannot be reverted.\n";

        return false;
    }
    */
}
