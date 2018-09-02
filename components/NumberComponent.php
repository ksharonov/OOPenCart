<?php

namespace app\components;

use yii\base\Component;

/**
 * Class NumberComponent
 * @package app\components
 */
class NumberComponent extends Component
{

    /**
     * В денежный формат
     */
    public function asMoney($value)
    {
        if (is_null($value)) {
            return null;
        } else {
            return number_format($value, 0, ',', ' ');
        }
    }
}