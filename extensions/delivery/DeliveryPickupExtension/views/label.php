<?php
/** @var \app\models\db\Storage[] $storage */
/** @var \yii\widgets\ActiveForm $form */
/** @var \app\models\session\OrderSession $order */
/** @var \DateTime $date */
?>
<div class="radio">
    <input id="delivery-<?= $id ?>" type="radio" hidden class="radio__input" <?= $extensionParams->checked ?>
           name="<?= $extensionParams->name ?>" value="<?= $extensionParams->value ?>">
    <label class="radio__label" for="delivery-<?= $id ?>">
        <?= $extensionParams->label ?> (<span id="deliveryDateFrom"><?=$date->format('d.m.Y')?></span> с 8:00)
        из&nbsp;
        <?= $form
            ->field($order, "deliveryData[$id][storageId]", [
                'options' => [
                    'class' => 'inline'
                ],
                'labelOptions' => [
                    'class' => ''
                ],
                'inputOptions' => [
                    'class' => 'checkout__select'
                ]])
            ->dropDownList(\yii\helpers\ArrayHelper::map($storage, 'id', 'title'))
            ->label(false);
        ?>
    </label>
</div>