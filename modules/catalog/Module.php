<?php

namespace app\modules\catalog;

/**
 * Module module definition class
 * TODO Разбраться чуть позже с содержимым контроллеров best/discount/new
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\catalog\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
