<?php

namespace app\commands;

use app\models\db\User;
use yii\console\Controller;
use app\helpers\ImportHelper;
use yii\db\Exception;

/**
 * Class SiteController
 *
 * Консольные команды
 *
 * @package app\commands
 */
class SiteController extends Controller
{
    /**
     * Импорт данных с 1С
     */
    public function actionImport()
    {
        $data = ImportHelper::Import1C();
        dump($data);
    }

    public function actionPassword($username, $newPassword)
    {
        $user = User::findByUsername($username);
        $user->password = \Yii::$app->security->generatePasswordHash($newPassword);
        $user->save();
    }

    public function actionTest()
    {
        $result = ImportHelper::CheckTestConnection1C();
        if ($result) {
            echo 'good connection';
        } else {
            echo 'bad connection';
        }

    }

    public function actionOk()
    {
        if (\Yii::$app->db->getTableSchema('{{%delivery_condition}}', true) !== null) {
            echo 1;
        } else {
            echo 2;
        }

        try {
            @\Yii::$app->db->createCommand('ALTER TABLE `block` DROP INDEX `idx-block-types`')->execute();
        } catch (Exception $exception) {
            echo 1;
        }

    }
}