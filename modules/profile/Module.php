<?php

namespace app\modules\profile;

use yii\filters\AccessControl;

/**
 * profile module definition class
 */
class Module extends \yii\base\Module
{

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\profile\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
