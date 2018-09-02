<?php

use yii\db\Migration;

/**
 * Class m180330_084931_add_chache_filter_cat_man_to_setting
 */
class m180330_084931_add_chache_filter_cat_man_to_setting extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'title' => 'Время в секундах хранения кеша списка производителей для категории',
            'setKey' => 'CACHE.FILTER.CAT.MAN',
            'setValue' => 3600,
            'type' => \app\models\db\Setting::TYPE_TEXT
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180330_084931_add_chache_filter_cat_man_to_setting cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180330_084931_add_chache_filter_cat_man_to_setting cannot be reverted.\n";

        return false;
    }
    */
}
