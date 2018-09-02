<?php

namespace app\modules\admin\modules\api\controllers;

use app\models\db\Product;
use app\models\db\ProductAttribute;
use app\models\db\ProductFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * Действия для фильтров товара
 */
class ProductFiltersController extends Controller
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
                    'get-by-source' => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Возвращает возможные фильтры по источнику
     * @return string
     */
    public function actionGetBySource()
    {
        $params = \Yii::$app->request->get();

        $params['source'] = $params['source'] ?? null;
        $params['search'] = $params['search'] ?? '';

        if ($params['source'] == ProductFilter::SOURCE_FIELD) {
            return Json::encode(Product::$filteredFields);
        } elseif ($params['source'] == ProductFilter::SOURCE_ATTRIBUTE) {
            $attributes = ProductAttribute::find()
                ->select('product_attribute.id, product_attribute.title, product_attribute_group.title as group')
                ->innerJoin('product_to_attribute')
                ->innerJoin('product_attribute_group')
                ->where(['like', 'product_attribute.title', $params['search']])
                ->asArray()
                ->limit(20)
                ->all();


            $attrsGroups = [];

            foreach ($attributes as $attribute) {
                $attrsGroups[$attribute['group']][] = $attribute;
            }

            return Json::encode($attrsGroups);
        } else {

        }
    }
}