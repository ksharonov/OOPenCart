<?php

namespace app\helpers;

use app\models\db\ProductAttributeGroup;
use app\models\db\ProductToAttribute;
use app\models\db\Product;

class AttributesHelper
{
    public static function createAttributesData(Product $product)
    {
        $attributesData = [];
        $tempAttrs = [];

        $attributes = ProductToAttribute::find()
            ->where(['productId' => $product->id])
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

        $attributesData = $tempAttrs;

        foreach ($product->attrs as $attr) {
            $attributesData[$attr->attr->group->title ?? '-'][$attr->attr->title] = $attr;
        }

        foreach ($attributesData as $key => $attrData) {
            if (!$attrData) {
                unset($attributesData[$key]);
            }
        }

        return $attributesData;
    }
}