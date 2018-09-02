<?php

namespace app\modules\admin\modules\api\controllers;

use app\models\db\Discount;
use app\models\db\Product;
use app\models\db\ProductAttribute;
use app\models\db\ProductOptionParam;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\Controller;
use app\models\db\ProductToAttribute;
use app\models\db\ProductToCategory;
use app\models\db\ProductPrice;
use app\models\db\ProductAnalogue;
use app\models\db\ProductToOptionValue;
use app\models\db\ProductOption;
use app\models\db\ProductOptionValue;
use yii\data\ActiveDataProvider;
use app\models\db\ProductFilterFastParam;

/**
 * Default controller for the `Module` module
 */
class ProductsController extends Controller
{
    /**
     * Устанавливает атрибут для продукта
     * @var $className ActiveQuery
     * @return boolean
     * @throws \yii\web\HttpException
     */
    public function actionSetAttribute()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();

            $relationData = $params['relationData'];
            $className = "app\\models\\db\\" . $relationData['className'];
            $primaryId = $relationData['id'];

            if (!($model = $className::findOne([
                $primaryId => $params['productId'],
                'attributeId' => $params['attributeId']
            ]))
            ) {
                $model = new $className();
                $model->$primaryId = $params['productId'];
                $model->attributeId = $params['attributeId'];
            }

            $model->attrValue = $params['value'];
            return $model->save();
        } else {
            throw new \yii\web\HttpException(405, 'Use POST instead');
        }
    }

    /**
     * Удаляет атрибут у продукта
     * @return void
     * @throws \yii\web\HttpException
     */
    public function actionDeleteAttribute()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();

            $relationData = $params['relationData'];
            $className = "app\\models\\db\\" . $relationData['className'];
            $primaryId = $relationData['id'];


            $className::deleteAll([
                $primaryId => $params['productId'],
                'attributeId' => $params['attributeId']
            ]);
        } else {
            throw new \yii\web\HttpException(405, 'Use POST instead');
        }
    }

    /**
     * Устанавливает цену для продукта
     * @return boolean
     * @throws \yii\web\HttpException
     */
    public function actionSetPrice()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            $model = new ProductPrice();
            $model->productId = $params['productId'];
            $model->productPriceGroupId = $params['productPriceGroupId'];
            return $model->save();
        } else {
            throw new \yii\web\HttpException(405, 'Use POST instead');
        }
    }

    /**
     * Обновить цену продукта
     * @return boolean
     * @throws \yii\web\HttpException
     */
    public function actionUpdatePrice()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            if (($model = ProductPrice::findOne(['id' => $params['productPriceId']]))) {
                $model->value = $params['value'];
                return $model->save();
            }
            return false;
        } else {
            throw new \yii\web\HttpException(405, 'Use POST instead');
        }
    }

    /**
     * Удаляет цену у продукта
     * @return void
     * @throws \yii\web\HttpException
     */
    public function actionDeletePrice()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            ProductPrice::deleteAll(['id' => $params['id']]);
        } else {
            throw new \yii\web\HttpException(405, 'Use POST instead');
        }
    }

    /**
     * Устанавливает аналог продукта
     * @return boolean
     * @throws \yii\web\HttpException
     */
    public function actionSetAnalogue()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            $model = new ProductAnalogue();
            $model->productId = $params['productId'];
            $model->productAnalogueId = $params['analogueId'];
            $model->backcomp = true;
            return $model->save();
        } else {
            throw new \yii\web\HttpException(405, 'Use POST instead');
        }
    }

    /**
     * Меняет аналог продукта на противолоположный
     * @return boolean
     * @throws \yii\web\HttpException
     */
    public function actionToggleAnalogue()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            if (isset($params['id'])) {
                $id = $params['id'];
                $productAnalogue = ProductAnalogue::findOne([
                    'id' => $id
                ]);
                $backcomp = !(bool)$productAnalogue->backcomp;
                $productAnalogue->backcomp = (int)$backcomp;
                $productAnalogue->save();
            }
        } else {
            throw new \yii\web\HttpException(405, 'Use POST instead');
        }
    }

    /**
     * Возвращает попап изменения опций в товаре
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionOptionParamPopup()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();

            // Если productParamId выставлен, то происходит редактирование, иначе - новый набор параметров
            if (isset($params['productOptionParamId'])) {
                $optionParamId = $params['productOptionParamId'];
                $optionParam = ProductOptionParam::findOne(['id' => $optionParamId]);
            } else {
                $optionParam = new ProductOptionParam();

                if (isset($params['productModelId'])) {
                    $productId = $params['productModelId'];
                    $optionParam->productId = $productId;
                } else {
                    throw new \yii\web\HttpException(400, 'Product ID is not set!');
                }
            }

            return $this->renderPartial('option', [
                'optionParam' => $optionParam,
            ]);
        } else {
            throw new \yii\web\HttpException(405, 'Use POST instead');
        }
    }

    /**
     * Удаляет набор опций в товаре
     *
     * @throws \yii\web\HttpException
     */
    public function actionDeleteOptionParam()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            if (isset($params['productOptionParamId'])) {
                $optionParamId = $params['productOptionParamId'];
                $optionParam = ProductOptionParam::findOne(['id' => $optionParamId]);
                if ($optionParam) {
                    $optionParam->delete();
                }
            } else {
                throw new \yii\web\HttpException(400, 'Product option param ID is not set!');
            }

        } else {
            throw new \yii\web\HttpException(405, 'Use POST instead');
        }
    }

    public function actionAddOptionToProduct()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            if (isset($params['productId']) && isset($params['optionId'])) {
                $productToOptionValue = ProductToOptionValue::findOne([
                    'productId' => $params['productId'],
                    'optionId' => $params['optionId']
                ]);

                if ($productToOptionValue) {
                    return;
                }

                $optionValues = ProductOptionValue::findAll(['optionId' => $params['optionId']]);
                foreach ($optionValues as $optionValue) {
                    $productToOptionValue = new ProductToOptionValue();
                    $productToOptionValue->optionId = $params['optionId'];
                    $productToOptionValue->productId = $params['productId'];
                    $productToOptionValue->optionValueId = $optionValue->id;
                    $productToOptionValue->save();
                }
            }
        } else {
            throw new \yii\web\HttpException(418, 'Use POST instead');
        }
    }

    public function actionAddProductOptionValueToProduct()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            if (isset($params['productId']) && isset($params['optionValueId'])) {
                $productToOptionValue = ProductToOptionValue::findOne([
                    'productId' => $params['productId'],
                    'optionValueId' => $params['optionValueId']
                ]);

                if (!$productToOptionValue) {
                    $optionValue = ProductOptionValue::findOne(['id' => $params['optionValueId']]);
                    $option = ProductOption::findOne(['id' => $optionValue->optionId]);

                    $productToOptionValue = new ProductToOptionValue();
                    $productToOptionValue->optionId = $option->id;
                    $productToOptionValue->productId = $params['productId'];
                    $productToOptionValue->optionValueId = $params['optionValueId'];
                    $productToOptionValue->save();
                }
            }
        } else {
            throw new \yii\web\HttpException(418, 'Use POST instead');
        }
    }

    public function actionDeleteProductOptionValueToProduct()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            if (isset($params['productId']) && isset($params['optionValueId'])) {
                $productToOptionValue = ProductToOptionValue::findOne([
                    'productId' => $params['productId'],
                    'optionValueId' => $params['optionValueId']
                ]);

                if ($productToOptionValue) {
                    $productToOptionValue->delete();
                }
            }
        } else {
            throw new \yii\web\HttpException(418, 'Use POST instead');
        }
    }

    public function actionSetDiscount()
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            $model = new Discount();
            $model->load($params);
            if ($model->validate()) {
                return $model->save();
            }
        }
    }
}
