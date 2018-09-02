<?php

namespace app\extensions\delivery\DeliveryPickupExtension;

use yii\web\AssetBundle;

class DeliveryPickupAsset extends AssetBundle
{
    public $sourcePath = '@app/extensions/delivery/DeliveryPickupExtension';

    public $publishOptions = [
        'forceCopy' => true,
    ];

    public $js = [
        'js/script.js'
    ];
}