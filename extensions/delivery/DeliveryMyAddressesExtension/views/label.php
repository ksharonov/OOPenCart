<?php
/** @var \app\models\db\Address[] $addresses */
/** @var \yii\widgets\ActiveForm $form */
/** @var \app\models\session\OrderSession $order */
?>

<div class="radio">
    <input id="delivery-<?= $id ?>" type="radio" hidden class="radio__input" <?= $extensionParams->checked ?>
           name="<?= $extensionParams->name ?>" value="<?= $extensionParams->value ?>">
    <label class="radio__label" for="delivery-<?= $id ?>">
        <?= $extensionParams->label ?>&nbsp;
        <?php
        echo $form
            ->field($order, "deliveryData[$id][addressId]", [
                'options' => [
                    'class' => 'inline'
                ],
                'labelOptions' => [
                    'class' => ''
                ],
                'inputOptions' => [
                    'class' => 'checkout__select'
                ]])
            ->dropDownList(\yii\helpers\ArrayHelper::map($addresses, 'id', 'address'))
            ->label(false);
        ?>
    </label>
</div>