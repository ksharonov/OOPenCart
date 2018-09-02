<div class="radio">
    <input id="delivery-<?= $id ?>" type="radio" hidden class="radio__input" <?= $extensionParams->checked ?>
           name="<?= $extensionParams->name ?>" value="<?= $extensionParams->value ?>">
    <label class="radio__label" for="delivery-<?= $id ?>">
        <?=$extensionParams->label ?>
    </label>
</div>