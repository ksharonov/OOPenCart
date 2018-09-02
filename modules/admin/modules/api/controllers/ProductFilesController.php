<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 15-12-2017
 * Time: 10:33 AM
 */

namespace app\modules\admin\modules\api\controllers;

use yii\filters\VerbFilter;
use yii\web\Controller;
use app\models\db\File;
use app\helpers\ModelRelationHelper;

class ProductFilesController extends Controller
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
                    'set' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Устанавливает файл
     * @return boolean
     * @throws \yii\web\HttpException
     */
    public function actionSet()
    {
        $params = \Yii::$app->request->post();

        $model = new File();
        $model->relModelId = $params['productId'];
        $model->relModel = $params['relModel'];
        $model->path = $params['path'];
        $model->type = $params['type'];
        $model->status = File::FILE_PUBLISHED;

        if ($params['link']) {
            $model->param->link = $params['link'];
        } else {
            $model->param->link = '#';
        }

        $model->save();
    }

    /**
     * Удаляет файл
     * @return boolean
     * @throws \yii\web\HttpException
     */
    public function actionDelete()
    {
        $params = \Yii::$app->request->post();

        File::deleteAll(['id' => $params['id']]);
    }
}