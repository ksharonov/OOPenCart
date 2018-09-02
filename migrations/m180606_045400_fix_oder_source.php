<?php

use yii\db\Migration;

/**
 * Class m180606_045400_fix_oder_source
 */
class m180606_045400_fix_oder_source extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $settings = \app\models\db\Setting::find()
            ->all();

        foreach ($settings as $setting) {
            if (strpos($setting->setKey, 'ORDER.STATUS') !== false) {
                $setting->params = null;
//                $setting->params = '[{"title":"","key":"Имя класса","value":"app\\\models\\\db\\\OrderStatus"}]';
                $setting->save();
            }
        }

        foreach ($settings as $setting) {
            if (strpos($setting->setKey, 'ORDER.STATUS') !== false) {
                $setting->params = '[{"title":"","key":"Имя класса","value":"app\\\models\\\db\\\OrderStatus"}]';
                $setting->save();
            }
        }
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
        echo "m180606_045400_fix_oder_source cannot be reverted.\n";

        return false;
    }
    */
}
