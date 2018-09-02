<?php

namespace app\commands;

use app\models\db\OrderContent;
use app\models\db\Product;
use app\models\db\ProductToCategory;
use app\models\db\Setting;
use yii\console\Controller;
use yii\db\Query;
use yii\db\Expression;

class ProductController extends Controller
{
    public function actionBestCategorizer()
    {
        $categoryBestId = Setting::get('PRODUCT.LIST.BEST.CATEGORY.ID');
        $bestCountParam = Setting::get('BEST.SALES.COUNT');

        $queryBest = OrderContent::find()
            ->select('productId, SUM(count) as s')
            ->from('order_content')
            ->groupBy('`productId`')
            ->createCommand()
            ->rawSql;

        $bests = (new Query())
            ->from("($queryBest) qb")
            ->where("qb.s > {$bestCountParam}")
            ->all();

        foreach ($bests as $best) {
            $product = Product::find()
                ->where(['id' => $best['productId']])
                ->one();

            $productToCategory = ProductToCategory::findOne([
                'productId' => $product->id,
                'categoryId' => $categoryBestId
            ]);

            if (!$productToCategory) {
                $productToCategory = new ProductToCategory();
                $productToCategory->productId = $product->id;
                $productToCategory->categoryId = $categoryBestId;
                $productToCategory->save();
            }
        }
    }
}