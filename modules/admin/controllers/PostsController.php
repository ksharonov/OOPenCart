<?php

namespace app\modules\admin\controllers;

use app\modules\admin\base\AdminController;
use app\system\db\ActiveRecord;
use Yii;
use app\models\db\Post;
use app\models\search\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\base\post\Review;
use app\models\base\post\Vacancy;
use app\models\base\post\News;

/**
 * PostsController implements the CRUD actions for Post model.
 */
class PostsController extends AdminController
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
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
     * Предварительное создание нового продукта
     * @return mixed
     */
    public function actionPreCreate($type)
    {
        if (is_null($type)) {
            return $this->redirect('/admin/posts');
        }

        $className = $this->findType($type);

        /** @var Post $model */
        $model = new $className;
        $model->title = '';
        $model->save(false);

        return $this->redirect("update?id={$model->id}&type={$type}");
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type)
    {
        if (is_null($type)) {
            return $this->redirect('/admin/posts');
        }

        $className = $this->findType($type);

        /** @var Post $model */
        $model = new $className;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $type = null)
    {
        $model = $this->findModel($id, $type);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Post model.
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
     * @param $id
     * @param null $type
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel($id, $type = null)
    {
        /** @var ActiveRecord $className */
        $className = $this->findType($type);

        if (($model = $className::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $type
     * @return null|string
     */
    public function findType($type)
    {
        $retType = null;

        switch ($type) {
            case Post::TYPE_NEWS:
                $retType = News::className();
                break;
            case Post::TYPE_REVIEWS:
                $retType = Review::className();
                break;
            case Post::TYPE_VACANCY:
                $retType = Vacancy::className();
                break;
            case null:
                $retType = Post::className();
                break;
            default:
                $retType = Post::className();
        }

        return $retType;
    }
}
