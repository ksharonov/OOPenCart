<?php

namespace app\modules\admin\modules\rbac\controllers;

use yii\rbac\Item;
use app\modules\admin\modules\rbac\base\ItemController;

/**
 * Class RoleController
 *
 * @package app\modules\admin\modules\rbac\controllers
 */
class RoleController extends ItemController
{
    /**
     * @var int
     */
    protected $type = Item::TYPE_ROLE;

    /**
     * @var array
     */
    protected $labels = [
        'Item' => 'Role',
        'Items' => 'Roles',
    ];
}
