<?php

namespace app\commands;

use app\models\db\ProductAttribute;
use app\models\db\ProductToAttribute;
use yii\console\Controller;
use yii\helpers\Json;

/**
 * Class AttributeCollectorController
 *
 * Сборщик атрибутов
 *
 * @package app\commands
 */
class AttributeCollectorController extends Controller
{
    /**
     * Запускатор сборщика атрибутов
     */
    public function actionStart()
    {
        $productToAttribute = ProductToAttribute::find()
            ->select('attributeId, GROUP_CONCAT(attrValue) as attrValues')
            ->groupBy('attributeId')
            ->asArray()
            ->all();

        $counter = 0;

        foreach ($productToAttribute as &$attr) {
            $attr['attrValues'] = explode(',', $attr['attrValues']) ?? [];
            $attr['attrValues'] = array_unique($attr['attrValues']);
            sort($attr['attrValues']);

            $productAttribute = ProductAttribute::findOne($attr['attributeId']);

            if ($productAttribute) {
                $productAttribute->params = Json::encode(array_values($attr['attrValues']));
                $productAttribute->save();
                $counter++;
            }

        }

        echo "Obnovleno $counter-strok";
    }
}