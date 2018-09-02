<?php

use yii\db\Migration;

/**
 * Class m180525_100609_edit_ext_non_cash_pay
 */
class m180525_100609_edit_ext_non_cash_pay extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $ext = \app\models\db\Extension::find()
            ->where(['name' => 'non_cash_payment_extension'])
            ->one();
        if ($ext) {
            $ext->access = \app\models\db\Extension::ACCESS_ENTITY;
            $ext->save();
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
        echo "m180525_100609_edit_ext_non_cash_pay cannot be reverted.\n";

        return false;
    }
    */
}
