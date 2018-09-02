<?php

namespace app\modules\admin\widgets\product\ProductFileWidget;

use app\models\db\File;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use app\modules\admin\widgets\product\ProductFileWidget\ProductFileAsset;
use app\helpers\ModelRelationHelper;

class ProductFileWidget extends Widget
{
    public $model = null;
    public $withImage = false;

    public function run()
    {
        $view = $this->getView();
        ProductFileAsset::register($view);
        $query = File::find()
            ->where([
                'relModel' => $this->model->getRelModel(),
                'relModelId' => $this->model->id
            ]);

        if (!$this->withImage) {
            $query = $query->andWhere(['<>', 'type', File::TYPE_IMAGE]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10000
            ]
        ]);
        return $this->render('index', [
            'productModel' => $this->model,
            'dataProvider' => $dataProvider,
        ]);
    }
}