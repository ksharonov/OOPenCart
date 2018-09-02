<?php

namespace app\modules\admin\modules\api\controllers;

use app\models\db\File;
use yii\helpers\Json;
use yii\web\Controller;
use app\models\db\ProductToAttribute;
use yii\filters\VerbFilter;
use app\helpers\ModelRelationHelper;

/**
 * Default controller for the `Module` module
 */
class ProductImageController extends Controller
{


    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'set-images' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Устанавливает изображения
     * @return boolean
     * @throws \yii\web\HttpException
     */
    public function actionSetImages()
    {
        $params = \Yii::$app->request->post();

        File::deleteAll([
            'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT,
            'relModelId' => $params['id']
        ]);

        if (!isset($params['data'])) {
            return true;
        }

        foreach ($params['data'] as $file) {
            $model = new File();
            $model->relModelId = $params['id'];
            $model->relModel = ModelRelationHelper::REL_MODEL_PRODUCT;
            $model->path = $file;
            $model->type = File::TYPE_IMAGE;
            $model->status = File::FILE_PUBLISHED;
            $model->save();
        }
    }

    /**
     *
     */
    public function actionRemoveImages()
    {
        $params = \Yii::$app->request->post();

        if (!isset($params['id'])) {
            return false;
        }

        File::deleteAll([
            'relModel' => ModelRelationHelper::REL_MODEL_PRODUCT,
            'id' => $params['id']
        ]);

        return true;
    }
}
