<?php

namespace app\modules\admin\base;

use Yii;

/**
 * Class AdminController
 * @package app\modules\admin\base
 */
class AdminController extends \yii\web\Controller
{
    public function afterAction($action, $result)
    {
        if ($action->id == 'view') {
            return $this->redirect(['index', 'page' => Yii::$app->session->get('last-page')]);
        } elseif ($action->id == 'index') {
            Yii::$app->session->set('last-page', Yii::$app->request->get('page') ?? null);
        }

        return parent::afterAction($action, $result);
    }
}