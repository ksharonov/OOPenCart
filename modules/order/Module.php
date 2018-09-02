<?php

namespace app\modules\order;

/**
 * order module definition class
 * TODO Скорее всего сюда придётся перенести всё по заказу
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\order\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
