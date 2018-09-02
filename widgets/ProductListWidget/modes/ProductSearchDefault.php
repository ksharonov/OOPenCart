<?php

namespace app\widgets\ProductListWidget\modes;

use app\widgets\ProductListWidget\base\ProductSearch;

class ProductSearchDefault extends ProductSearch
{
    public function search()
    {
        $query = $this->query;

        $query->andWhere([
            'id' => 5
        ]);

        $query->orWhere([
            'AND', ['id' => 1], ['id' => 2]
        ]);
        parent::search();
    }
}