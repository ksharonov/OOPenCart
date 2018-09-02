<?php

namespace app\helpers;

use app\models\db\Product;
use app\models\db\ProductAttributeGroup;
use app\models\db\ProductToAttribute;
use app\models\base\Compare;
use app\models\base\CompareItem;

/**
 * Хелпер сравнения
 */
class CompareHelper
{

    /**
     * Генерация массива сравнения двух продуктам по всем атрибутам всех продуктов
     *
     * @param array $productIds
     * @return Compare[]
     */
    public static function createCompareData($productIds)
    {
        if (!$productIds) {
            return [];
        }

        $productIds = array_keys(Product::find()
            ->select('id')
            ->where(['id' => $productIds])
            ->indexBy('id')
            ->column());


        $compare = [];
        $tempAttrs = [];

        $products = Product::find()
            ->where(['id' => $productIds])
            ->all();

        $attributes = ProductToAttribute::find()
            ->where(['productId' => $productIds])
            ->all();

        $attributeGroups = ProductAttributeGroup::find()
            ->all();

        foreach ($attributeGroups as $group) {
            $tempAttrs[$group->title] = [];
        }

        foreach ($attributes as $attr) {
            $tempAttrs[$attr->attr->group->title ?? '-'][$attr->attr->title] = null;
        }

        asort($tempAttrs);

        $compareModel = new Compare();

        foreach ($products as $product) {
            $compareItem = new CompareItem();
            $compareItem->productId = $product->id;
            $compareItem->attributes = (object)$tempAttrs;

            foreach ($product->attrs as $attr) {
                $compareItem->attributes->{$attr->attr->group->title ?? 'null'}[$attr->attr->title] = $attr;
            }
            foreach ($compareItem->attributes as $key => $attribute) {
                if (!$attribute) {
                    unset($compareItem->attributes->{$key});
                }
            }
            $compare[] = $compareItem;
        }

        /** позже возвращать тут */
        $compareModel->items = $compare;

        return $compare;
    }

    /**
     * Массив атрибутов для шаблона
     */
    public static function createShortCompareData($compare)
    {
        $preAttrsGroups = [];

        foreach ($compare as $item) {
            foreach ($item->attributes as $titleGroup => $attrs) {
                if (!isset($preAttrsGroups[$titleGroup])) {
                    $preAttrsGroups[$titleGroup] = [];
                }
                foreach ($attrs as $title => $attr) {
                    if (!isset($preAttrsGroups[$titleGroup][$title])) {
                        $preAttrsGroups[$titleGroup][$title] = [];
                    }
                    $preAttrsGroups[$titleGroup][$title ?? '-'][$item->productId] = $attr->attrValue ?? '-';
                }
            }
        }

        return $preAttrsGroups;
    }
}