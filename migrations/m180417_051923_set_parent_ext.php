<?php

use yii\db\Migration;

/**
 * Class m180417_051923_set_parent_ext
 */
class m180417_051923_set_parent_ext extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $connection = Yii::$app->db;

//        $ext = \app\models\db\Extension::find()
//            ->where(['name' => 'payment_widget'])
//            ->one();

        $ext = (new \yii\db\Query())
            ->from('extension')
            ->where(['name' => 'payment_widget'])
            ->one();

//        $extParent = \app\models\db\Extension::find()
//            ->where(['name' => 'online_payment_extension'])
//            ->one();

        $extParent = (new \yii\db\Query())
            ->from('extension')
            ->where(['name' => 'online_payment_extension'])
            ->one();

        if ($ext) {
            $ext['parentId'] = $extParent['id'];
            $connection->createCommand()->update('extension', $ext, "id = {$ext['id']}")->execute();
        } else {
            $this->insert('extension', [
                'title' => 'Тестовый виджет оплаты 1',
                'name' => 'payment_widget',
                'class' => 'app\\extensions\\TestPaymentExtension\\TestPaymentExtension',
                'type' => \app\models\db\Extension::EXTENSION_TYPE_PAYMENT,
                'status' => \app\models\db\Extension::STATUS_ACTIVE
            ]);

            $ext = (new \yii\db\Query())
                ->from('extension')
                ->where(['name' => 'payment_widget'])
                ->one();

            $ext['parentId'] = $extParent['id'];
            $connection->createCommand()->update('extension', $ext, "id = {$ext['id']}")->execute();

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
        echo "m180417_051923_set_parent_ext cannot be reverted.\n";

        return false;
    }
    */
}
