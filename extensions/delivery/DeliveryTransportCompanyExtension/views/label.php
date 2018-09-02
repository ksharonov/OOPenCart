<?php
/** @var \app\models\db\Address[] $addresses */
/** @var array $transportCompanies */
/** @var \app\models\session\OrderSession $order */
?>
<div class="radio">
    <input id="delivery-<?= $id ?>" type="radio" hidden class="radio__input" <?= $extensionParams->checked ?>
           name="<?= $extensionParams->name ?>" value="<?= $extensionParams->value ?>">
    <label class="radio__label" for="delivery-<?= $id ?>">
        <?= $extensionParams->label ?>&nbsp;
        <?= $form
            ->field($order, "deliveryData[$id][transportCompany]", [
                'options' => [
                    'class' => 'inline'
                ],
                'labelOptions' => [
                    'class' => ''
                ],
                'inputOptions' => [
                    'class' => 'checkout__select'
                ]])
            ->dropDownList($transportCompanies)
            ->label(false);
        ?>
    </label>
</div>
<!--        <a href="#" class="delivery-form__shop"> «ТДК Гостиный Двор»</a>-->