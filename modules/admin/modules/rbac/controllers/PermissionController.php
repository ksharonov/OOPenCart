<?php

namespace app\modules\admin\modules\rbac\controllers;

use yii\rbac\Item;
use app\modules\admin\modules\rbac\base\ItemController;

/**
 * Class PermissionController
 *
 * @package app\modules\admin\modules\rbac\controllers
 */
class PermissionController extends ItemController
{
    /**
     * @var int
     */
    protected $type = Item::TYPE_PERMISSION;

    /**
     * @var array
     */
    protected $labels = [
        'Item' => 'Permission',
        'Items' => 'Permissions',
    ];
}
