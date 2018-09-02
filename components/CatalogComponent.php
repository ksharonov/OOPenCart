<?php

namespace app\components;


class CatalogComponent
{
    /**
     * Объект корзины
     * @var
     */
    public $catalog;

    public function __construct()
    {
//        dump(1);
    }

    public function set(&$param)
    {
        $this->catalog = $param;
    }

    public function get()
    {
        return $this->catalog;
    }
}