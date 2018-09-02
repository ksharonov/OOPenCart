<?php

use yii\helpers\Html;

/* @var string $name */
/* @var \yii\base\Model $model */
/* @var string $attribute */
/* @var array $data */
/* @var string $formName */


echo Html::hiddenInput(null, null, [
    'data-inline-select-params' => true,
    'data-form-name' => $formName,
    'data-inline-select-relation' => "{$name}[{$attribute}]"
]);

foreach ($data as $key => $item) {
//    echo Html::label($item);
//    echo "<br>";

    if (in_array($key, $model->$attribute)) {
        $selected = 1;
        $class = "orders__filter orders__filter_active";
    } else {
        $selected = "";
        $class = "orders__filter";
    }

    echo "&nbsp;&nbsp;&nbsp;";

    echo Html::hiddenInput(null, $selected, [
        'data-name' => $attribute . $key,
        'data-inline-select' => true,
        'data-id' => $key,
        'data-inline-select-relation' => "{$name}[{$attribute}]"
    ]);

    echo Html::button($item, [
        'class' => $class,
        'data-inline-select' => true,
        'data-selected' => $selected,
        'data-id' => $key,
        'data-inline-select-relation' => "{$name}[{$attribute}]"
    ]);

//    echo "<br><br>";
}

//echo "<br><br>";

$model->$attribute = "";
//echo Html::activeHiddenInput($model, $attribute);
?>

<div hidden data-inline-select-relation="<?= "{$name}[{$attribute}]" ?>">

</div>
