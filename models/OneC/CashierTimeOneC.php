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

class CashierTimeOneC extends OneCLoader
{
    public $source = 'cashier';

    public function getGuidCashierTimeOneC ()
    {
        $data = $this->load();
        //print_r($data);
        return $data['КассоваяСмена']['0']['СменаGuid'];

    }
}