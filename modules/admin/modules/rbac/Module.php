<?php

namespace app\modules\admin\modules\rbac;

use yii\filters\AccessControl;

/**
 * GUI manager for RBAC.
 *
 * Use [[\yii\base\Module::$controllerMap]] to change property of controller.
 *
 * ```php
 * 'controllerMap' => [
 *     'assignment' => [
 *         'class' => 'app\modules\admin\modules\rbac\controllers\AssignmentController',
 *         'userIdentityClass' => 'app\models\db\User',
 *         'searchClass' => [
 *              'class' => 'app\modules\admin\modules\rbac\models\search\AssignmentSearch',
 *              'pageSize' => 10,
 *         ],
 *         'idField' => 'id',
 *         'usernameField' => 'username'
 *         'gridViewColumns' => [
 *              'id',
 *              'username',
 *              'email'
 *         ],
 *     ],
 * ],
 * ```php
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
                        'roles' => ['admin.full'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['manager'],
                    ],
                ],
            ]
        ];
    }

    /**
     * @var string the default route of this module. Defaults to 'default'
     */
    public $defaultRoute = 'assignment';

    /**
     * @var string the namespace that controller classes are in
     */
    public $controllerNamespace = 'app\modules\admin\modules\rbac\controllers';
}
