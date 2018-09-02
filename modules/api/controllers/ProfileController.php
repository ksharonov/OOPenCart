<?php

namespace app\modules\api\controllers;

use Yii;
use app\models\db\Sms;
use app\models\db\Client;
use app\models\db\User;
use app\modules\lexema\models\LexemaConnect;
use app\modules\lexema\models\LexemaImport;
use app\system\base\ApiController;
use app\models\session\ReconciliationSession;
use app\system\base\widgets\ActiveForm;
use yii\web\Response;

class ProfileController extends ApiController
{
    /** @var LexemaImport */
    public $importer;

    /** @var ReconciliationSession */
    public $model;

    public function init()
    {
    	$this->importer = new LexemaImport();

        if (!isset($this->model)) {
            $this->model = ReconciliationSession::get();
        }

        parent::init(); // TODO: Change the autogenerated stub
    }

    /**
     * @return array
     * @throws \ReflectionException
     * @deprecated
     */
    public function actionAct()
    {
        $lexemaImporter = new LexemaImport();

        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $clientId = $user->client->guid;

        $result = $lexemaImporter->getReconciliationAct($clientId);

        if ($result) {
            return ['link' => $result];
        }
    }

    /**
     * @return bool
     */
    public function actionRequestAct()
    {
        $post = \Yii::$app->request->post();

        /** @var User $user */
        $user = \Yii::$app->user->identity;

        $clientId = $user->client->id;
        $clientGuid = $user->client->guid;

        $result = $this->importer->requestAct($clientGuid, $post['from'], $post['to']);

        if (($result === 3) or ($result === 2)) {
            $this->model->addAct($clientId, $post);
            $this->model->save();
            return true;
        } else {
            return $result;
        }
    }

    /**
     * @return bool
     */
    public function actionValidate()
    {
        $post = \Yii::$app->request->post();

        /** @var User $user */
        $user = \Yii::$app->user->identity;
        $clientId = $user->client->id;

        if ($this->model->addAct($clientId, $post)) {
            return true;
        } else return false;
    }

    /**
     * @return bool
     * @throws \ReflectionException
     */
    public function actionCheckAct()
    {
        /** @var User $user */
        $user = \Yii::$app->user->identity;

        $clientId = $user->client->id;
        $clientGuid = $user->client->guid;

        $acts = $this->model->getActs($clientId);

        if (!$acts) {
            return false;
        }

        foreach ($acts as $act) {
            $actExploded = explode("-", $act);
            $from = $actExploded[0];
            $to = $actExploded[1];

            $result = $this->importer->getReconciliationAct($clientGuid, $from, $to);
            if ($result === false) {
                // либо нет акта, либо нет файла
            } else {
                // есть файл
                // можно добавить в await
                $this->model->addAwait($clientId, $result);
                $this->model->deleteAct($clientId, $act);
                $this->model->save();
            }
        }

        if ($this->model->hasAwait($clientId)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function actionCheckAwait()
    {
        /** @var User $user */
        $user = \Yii::$app->user->identity;

        $clientId = $user->client->id;

        if ($this->model->hasAwait($clientId)) {
            return true;
        } else {
            return false;
        }
    }

    public function actionTest()
    {
        /** @var User $user */
        $user = \Yii::$app->user->identity;

        $clientId = $user->client->id;

        $data = [
            "01.04.2018-04.04.2018",
            "01.04.2018-05.04.2018",
            "01.04.2018-06.04.2018"
        ];

//        $this->model->acts[17] = $data;
        $this->model->acts[17][] = "01.04.2018-29.04.2018";
        $this->model->save();
    }


    /**
     * Отправка СМС при оформлении
     */
    public function actionSendPhoneChangeSms()
    {
        if (Yii::$app->user->identity) {
            if (is_null(Yii::$app->user->identity->phone)) {
                $session = Yii::$app->session;
                $session->set('phoneChanged', true);
                return false;
            }
            Sms::sendCodeToUser(Yii::$app->user->identity);
        }

        return true;
    }

    /**
     * Подтверждение СМС при оформлении
     */
    public function actionSubmitPhoneChangeSms()
    {
        $code = \Yii::$app->request->post('code') ?? null;

        if (Yii::$app->user->identity) {
            $sms = Sms::findLastSmsByUser(Yii::$app->user->identity);
            $sms->attempts++;

            if ($sms && $sms->code == $code) {
                $sms->success = Sms::IS_SUCCESS;
            }

            $sms->save();

            if ($sms->success) {
                $session = Yii::$app->session;
                $session->set('phoneChanged', true);
                return true;
            }
        }

        return false;
    }

}