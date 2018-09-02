<?php

use app\models\db\ProductOption;
use app\models\db\ProductOptionValue;
use app\models\db\Product;
use yii\helpers\Html;


/* @var ProductOption $productOption */
/* @var Product $product */
/* @var \app\models\db\ProductOptionValue[] $productOptionValues */


?>

<?php foreach ($productOption->values as $optionValue): ?>
    <?php $optionValueId = $optionValue->id; $productId = $product->id; ?>
    <div class="form-control">
        <?= Html::input('checkbox', "value-$optionValueId", '', [
            in_array($optionValue, $productOptionValues) ? "checked" : "" => "",
            'id' => "value-$optionValueId",
            'onchange' => "updateProductOptionValue($productId, $optionValueId, this)"
        ]); ?>
        <label for="value-<?= $optionValue->id; ?>"><?= $optionValue->value; ?></label>
    </div>
<?php endforeach; ?>
