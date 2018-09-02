<?php

use yii\db\Migration;

/**
 * Class m180510_103421_insert_manager_user
 */
class m180510_103421_insert_manager_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $user = \app\models\db\User::find()
            ->where(['username' => 'Manager'])
            ->one();

        if (!$user) {
            $user = new \app\models\db\User();
        }

        $user->username = "Manager";
        $user->password = "123123123";
        $user->save();

        $auth = new \yii\rbac\DbManager();
        $auth->init();
        $role = $auth->getRole('manager');
        $auth->assign($role, $user->id);


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        \app\models\db\User::deleteAll(['username' => 'Manager']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180510_103421_insert_manager_user cannot be reverted.\n";

        return false;
    }
    */
}
