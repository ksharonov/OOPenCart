<?php

namespace app\modules\admin;

use yii\base\InlineAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\assets\AdminAsset;

/**
 * Admin module definition class
 */
class Module extends \yii\base\Module
{

    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin.full', 'manager'],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'products' => 'update',
                        ],
                        'roles' => ['?'],
                    ],
                ],
            ]
        ];
    }
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }
}
