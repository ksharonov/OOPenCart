<?php

namespace app\modules\admin\modules\api\controllers\common;

use app\models\db\Seo;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;

class SeoController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'set' => ['get']
                ],
            ],
        ];
    }

    /**
     * Устанавливает SEO-атрибуты
     * @return boolean
     * @throws \yii\web\HttpException
     */
    public function actionSet()
    {
        $params = \Yii::$app->request->get();

        $tmpModel = new Seo();
        $tmpModel->load($params);

        if (!$tmpModel->relModelId) {
            return false;
        }

        if ($tmpModel) {
            $model = Seo::findOne([
                'relModel' => $tmpModel->relModel,
                'relModelId' => $tmpModel->relModelId
            ]);

            if (!$model) {
                $model = new Seo([
                    'relModel' => $tmpModel->relModel,
                    'relModelId' => $tmpModel->relModelId
                ]);
            }

            $model->title = $tmpModel->title;
            $model->meta_description = $tmpModel->meta_description;
            $model->meta_keywords = $tmpModel->meta_keywords;
            $model->params = $tmpModel->params;
            $model->save();
        }
        return false;
    }
}