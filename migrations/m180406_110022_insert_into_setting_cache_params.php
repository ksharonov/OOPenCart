<?php

use yii\db\Migration;

/**
 * Class m180406_110022_insert_into_setting_cache_params
 */
class m180406_110022_insert_into_setting_cache_params extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'setKey' => 'SITE.CACHE.ENABLE',
            'setValue' => '0',
            'type' => 1,
            'title' => 'Вкл/выкл кеширование',
        ]);

        $this->insert('setting', [
            'setKey' => 'SITE.CACHE.DURATION',
            'setValue' => '1440',
            'type' => 0,
            'title' => 'Длительность актуальности кеша в минутах',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('setting', ['setKey' => 'SITE.CACHE.ENABLE']);
        $this->delete('setting', ['setKey' => 'SITE.CACHE.DURATION']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180406_110022_insert_into_setting_cache_params cannot be reverted.\n";

        return false;
    }
    */
}
