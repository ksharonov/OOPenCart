<?php

namespace app\modules\profile\widgets\ReconciliationWidget;

use app\models\session\ReconciliationSession;
use app\system\base\Widget;

class ReconciliationWidget extends Widget
{
    public function run()
    {
        $view = $this->getView();
        ReconciliationAsset::register($view);

        $model = ReconciliationSession::get();

        //dump($model);
        $user = \Yii::$app->user->identity;

        $client = $user->client;

        return $this->render('form', [
            'model' => $model,
            'client' => $client,
        ]);
    }
}