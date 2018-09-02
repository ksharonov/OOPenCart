<?php

use yii\db\Migration;

/**
 * Class m180522_061319_update_extensions_in_db
 */
class m180522_061319_update_extensions_in_db extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $ext = \app\models\db\Extension::findOne(['name' => 'shop_payment_extension']);

        if ($ext) {
            $ext->class = 'app\\extensions\\payment\\ShopPaymentExtension\\ShopPaymentExtension';
            $ext->save();
        }

        $ext = \app\models\db\Extension::findOne(['name' => 'online_payment_extension']);

        if ($ext) {
            $ext->class = 'app\\extensions\\payment\\OnlinePaymentExtension\\OnlinePaymentExtension';
            $ext->save();
        }

        $ext = \app\models\db\Extension::findOne(['name' => 'non_cash_payment_extension']);

        if ($ext) {
            $ext->class = 'app\\extensions\\payment\\NonCashPaymentExtension\\NonCashPaymentExtension';
            $ext->save();
        }

        $ext = \app\models\db\Extension::findOne(['name' => 'non_cash_payment_extension']);

        if ($ext) {
            $ext->class = 'app\\extensions\\payment\\NonCashPaymentExtension\\NonCashPaymentExtension';
            $ext->save();
        }

        $extParent = \app\models\db\Extension::findOne(['name' => 'online_payment_extension']);
        $ext = \app\models\db\Extension::findOne(['name' => 'sberbank_payment_widget']);

        if ($ext && $extParent) {
            $ext->parentId = $extParent->id;
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
        echo "m180522_061319_update_extensions_in_db cannot be reverted.\n";

        return false;
    }
    */
}
