<?php

namespace app\widgets\ProductListWidget\base;

use app\models\db\Product;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Class ProductSearch
 *
 * Базовая модель поиска
 *
 * @package app\widgets\ProductListWidget\base
 * @property
 */
class ProductSearch extends Product
{

    /**
     * @var ActiveQuery
     */
    public $query;

    /**
     * @var ActiveDataProvider
     */
    private $_dataProvider;

    /**
     * Строка поиска
     * @var null
     */
    public $find = null;

    public function search()
    {
        /** @var ActiveQuery $query */
        $query = $this->query;
        $query->andWhere(['id' => 1]);
    }

    /**
     *
     */
    public function init()
    {
        $this->prepareProvider();
        $this->search();
    }

    /**
     * Подготовка
     */
    public function prepareRequest()
    {
        $request = \Yii::$app->request->get();
    }

    public function prepareProvider()
    {
        $this->query = Product::find();
        $this->_dataProvider = new ActiveDataProvider([
            'query' => $this->query,
        ]);
    }

    public function getActiveDataProvider()
    {
        return $this->_dataProvider;
    }
}