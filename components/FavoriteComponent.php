<?php

namespace app\components;

use app\models\base\FavoriteItem;
use Yii;
use yii\base\Component;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use app\models\base\Favorite;
//use app\models\search\ProductSearch;
use app\modules\catalog\widgets\FilterWidget\models\ProductSearch;

/**
 * Class FavoriteComponent
 * Компонент данных избранного
 * @package app\components
 *
 * @property array $searchProvider
 */
class FavoriteComponent extends Component
{
    public $favorite;

    public $productIds = [];

    public function __construct(array $config = [])
    {
        if (!$this->favorite) {
            $this->build();
        }
        parent::__construct($config);
    }

    /**
     * Сборка избранного в удобный вид
     *
     * @return Favorite
     */
    public function build()
    {
        $cookies = Yii::$app->request->cookies;
        $favorite = Json::decode($cookies->getValue('favorite')) ?? [];

        $favoriteModel = new Favorite();
        foreach ($favorite as &$item) {
            $favoriteItemModel = new FavoriteItem();
            $favoriteItemModel->productId = $item;
            array_push($this->productIds, $item);
            $item = $favoriteItemModel;
        }
        $favoriteModel->items = $favorite;

        $this->favorite = $favoriteModel;
    }

    /**
     * @return Favorite
     */
    public function get()
    {
        return $this->favorite;
    }

    /**
     * Возвращает модель поиска и DataProvider
     * @return array
     */
    public function getSearchProvider()
    {
        $searchModel = new ProductSearch();
        $searchModel->id = $this->productIds;
        $searchModel->sort = '-price';
        $dataProvider = $searchModel->search([]);
        return [
            $searchModel,
            $dataProvider
        ];
    }
}