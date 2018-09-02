<?php

namespace app\modules\admin\controllers;

use app\models\db\Apikey;
use app\models\db\ProductAnalogue;
use app\models\db\ProductAttribute;
//use app\models\db\ProductFile;
use app\models\db\File;
use app\models\db\ProductPrice;
use app\models\search\ProductSearch;
use app\models\db\ProductToAttribute;
use app\models\db\ProductToCategory;
use app\models\db\ProductUnit;
use app\modules\admin\base\AdminController;
use yii\helpers\Url;
use Yii;
use app\models\db\Product;
use yii\data\ActiveDataProvider;
use yii\filters\AccessRule;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\modules\rbac\filters\AccessControl;

/**
 * ProductsController implements the CRUD actions for Product model.
 */
class ProductsController extends AdminController
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
                    'delete' => ['POST', 'GET'],
                ],
            ],

        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Предварительное создание нового продукта
     * @return mixed
     */
    public function actionPreCreate()
    {
        $model = new Product();
        $model->save();
        return $this->redirect(['update', 'id' => $model->id]);
    }


    //public $layout = "micromain";

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        // доступ для Лексемы
        $params = Yii::$app->request->get();

        //dump($params);

        if (isset($params['keyid']) &&
            isset($params['hash']) &&
            isset($params['userelmodel'])) {
            // ----------- экшн для Лексемы
            $this->layout = "micromain";

            $requestHash = $params['hash'];
            $requestKeyId = $params['keyid'];

            $apikey = Apikey::findOne($requestKeyId);

            if (!$apikey) {
                // access denied
                echo "Access denied";
                return false;
            }
            $preHashString = $id . $apikey->keyValue;
            //dump(md5($preHashString));

            if ($requestHash !== md5($preHashString)) {
                // access denied
                echo "Access denied";

                return false;
            }

            if ($params['userelmodel'] == "true") {
                $model = Product::findByGuid($id, true);

            } else {
                $model = Product::find()
                    ->with(['seo'])
                    ->where(['id' => $id])
                    ->one();
            }

            if (!$model) {
                echo "Error.";
                exit;
            }

//            $model->scenario = 'udpate';

            //dump($model);
            $model->load(Yii::$app->request->post());

            if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
                //return $this->redirect(['index']);
                return $this->render('update', [
                    'model' => $model,
                ]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

        } else {
            // ----------- стандартный экшн
            if (Yii::$app->user->isGuest) {
                return $this->redirect(\yii\helpers\Url::home());
            }

            $model = Product::find()
                ->with(['seo'])
                ->where(['id' => $id])
                ->one();


        }

        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->status == Product::STATUS_DELETED) {
            $model->delete();
        } else {
            $model->status = Product::STATUS_DELETED;
            $model->save(false);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
