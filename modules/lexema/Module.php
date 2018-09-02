<?php

namespace app\modules\lexema;

use yii\filters\AccessControl;

/**
 * profile module definition class
 */
class Module extends \yii\base\Module
{

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\lexema\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
