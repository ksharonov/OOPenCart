<?php

namespace app\modules\api\controllers;

use app\system\base\ApiController;
use app\widgets\ProductListWidget\ProductListWidget;
use Yii;
use yii\helpers\Json;
//use yii\web\Controller;
use app\system\base\Controller;
use app\models\db\ProductToAttribute;
use app\models\db\ProductAttribute;
use app\components\FavoriteComponent;

/**
 * Контроллер АПИ для каталога
 * todo подумать про единый механизм для всех каталогов без нескольких точек входа
 */
class CatalogController extends ApiController
{

    public $filterSelect;
    public $title = false;
    public $mode = false;

    public function beforeAction($action)
    {
        $arr = [];
        $attributes = [];
        $attributeIds = [];

        $filterSelect = Yii::$app->request->post('filterSelect') ?? [];
        $category = Yii::$app->request->post('category');

        foreach ($filterSelect as $attribute) {
            $attributeIds[] = $attribute['id'];
            $attributes[$attribute['id']] = array_diff($attribute['selects'], [""]);
        }

        $productAttributes = ProductAttribute::find()
            ->andWhere(['id' => $attributeIds])
            ->all();

        foreach ($productAttributes as $productAttribute) {
            $arr[$productAttribute->id] = [];
            foreach ($productAttribute->paramsArray as $element) {
                $selected = $attributes[$productAttribute->id];

                $arr[$productAttribute->id][$element] = ProductToAttribute::find()
                    ->joinWith('product')
                    ->joinWith('product.categories')
                    ->where(['product_category.id' => $category])
                    ->andWhere(['product_to_attribute.attrValue' => $element])
                    ->andWhere(['product_to_attribute.attributeId' => $productAttribute->id])
                    ->count();
            }
        }
        $this->filterSelect = $arr;
        $this->title = Yii::$app->request->post('title') ?? false;
        $this->mode = Yii::$app->request->post('mode') ?? false;

        return parent::beforeAction($action);
    }

    /**
     * Возвращает отрендеренный каталог (Временно пока тут всё в кучу)
     * @return string
     */
    public function actionIndex()
    {

        if ($this->mode == ProductListWidget::MODE_FAVORITE) {
            /** @var FavoriteComponent $favoriteComponent */
            $favoriteComponent = Yii::$app->favorite;
            $favorite = $favoriteComponent->get();
            list($searchModel, $dataProvider) = $favoriteComponent->searchProvider;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return [
                'html' => $this->renderPartial('favorite', [
                    'title' => $this->title,
                    'mode' => $this->mode,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
                ]),
                'filterSelect' => $this->filterSelect,
            ];
        } else {
            return [
                'html' => $this->renderPartial('index', [
                    'title' => $this->title,
                    'mode' => $this->mode
                ]),
                'filterSelect' => $this->filterSelect,
            ];
        }
    }

    public function actionFavorite()
    {

    }
}
