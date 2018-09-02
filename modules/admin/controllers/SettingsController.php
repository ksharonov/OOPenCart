<?php

namespace app\modules\admin\controllers;

use app\modules\admin\base\AdminController;
use Yii;
use app\models\db\Setting;
use app\models\search\SettingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * SettingsController implements the CRUD actions for Setting model.
 */
class SettingsController extends AdminController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => false,
                        'roles' => ['manager'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin.full'],
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'index' => ['POST', 'GET']
                ],
            ],
        ];
    }

    /**
     * Lists all Setting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SettingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        if (Yii::$app->request->post('hasEditable')) {
            $param = Yii::$app->request->post();
            $cid = Yii::$app->request->post('editableKey');
            $index = $param['editableIndex'];
            $editableAttr = $param['editableAttribute'];

            $model = Setting::findOne($cid);

            $out = Json::encode(['output' => '', 'message' => '']);
            $posted = current($param['SettingSearch']);

            if (isset($param['SettingSearch'][$index][$editableAttr])) {
                $posted[$editableAttr] = $param['SettingSearch'][$index][$editableAttr];
            } else {
                $posted[$editableAttr] = '';
            }

            $post = ['Setting' => $posted];
            if ($model->load($post)) {
                $model->save();
                $output = $posted[$editableAttr];

                $out = Json::encode(['output' => $output, 'message' => '']);
            }
            echo $out;
            return null;
        }


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Setting model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Setting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Setting();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Setting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Setting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Setting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Setting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Setting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
