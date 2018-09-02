<?php

namespace app\models\OneC;

use app\models\db\Client;
use app\models\db\ProductUnit;
use app\models\db\Unit;
use app\system\base\OneCLoader;
use app\models\db\OuterRel;
use app\helpers\ModelRelationHelper;
use app\models\db\Product;
use app\models\db\ProductToCategory;
use app\models\db\ProductCategory;
use app\models\db\StorageBalance;

/**
 * Created by PhpStorm.
 * User: Elshat
 * Date: 01.02.2018
 * Time: 18:02
 */
class TestConnectOneC extends OneCLoader
{
    public $source = 'test';

    public function addTestConnectOneC()
    {
        $data = $this->load();
        //print_r($data);
        if ($data) {
            return true;
        }
        return false;

    }
}