<?php

namespace app\widgets\ReCallWidget;

use app\helpers\MailHelper;
use Yii;
use app\models\form\ReCallForm;
use app\widgets\ReCallWidget\ReCallAsset;

/**
 * Виджет обратной связи
 * Class RecallWidget
 */
class ReCallWidget extends \yii\base\Widget
{
    public function run()
    {
        $reCallForm = new ReCallForm();
        $view = $this->getView();
        ReCallAsset::register($view);

        $params = Yii::$app->request->post();

        if (\Yii::$app->request->isPost && $reCallForm->load(['ReCallForm' => $params]) && isset($params['name']) && $reCallForm->validate()) {
            MailHelper::newCallback($reCallForm);
            $reCallForm->save();
        }

        return $this->render('index', [
            'form' => $reCallForm,
            'model' => $reCallForm
        ]);
    }
}
