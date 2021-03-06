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
use app\models\db\Setting;

/**
 * Created by PhpStorm.
 * User: Elshat
 * Date: 01.02.2018
 * Time: 18:02
 */

class TerminalOneC extends OneCLoader
{
    //public $source = 'kkm_terminal?terminal=';

    public function getGuidTerminalOneC ()
    {
        $term = Setting::get('TERMINAL.NAME.ONEC');
        $inOneC = str_replace(' ', '+', $term);
        $this->source = 'kkm_terminal?terminal='.$inOneC;
        $data = $this->load();
        return $data['Терминал'][0]['ТерминалGUID'];

    }
}