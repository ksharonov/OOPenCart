<?php

namespace app\modules\backoffice;

use yii\filters\AccessControl;

/**
 * profile module definition class
 */
class Module extends \yii\base\Module
{

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\backoffice\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
