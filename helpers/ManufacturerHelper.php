<?php

namespace app\helpers;

use app\models\db\Manufacturer;

class ManufacturerHelper {


    /**
     * @param Manufacturer[] $manufacturers
     */
    public static function createAlphabetList($manufacturers) {
        $list = [];


        foreach ($manufacturers as $manufacturer) {
            $firstLetter = mb_strtoupper(mb_substr($manufacturer->title, 0, 1));
            $list[$firstLetter][] = $manufacturer;
        }

        return $list;
    }
}