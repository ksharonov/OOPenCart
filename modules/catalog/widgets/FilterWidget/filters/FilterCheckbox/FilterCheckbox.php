<?php
/**
 * Created by PhpStorm.
 * User: Кирилл
 * Date: 27-12-2017
 * Time: 11:34 AM
 */

namespace app\modules\catalog\widgets\FilterWidget\filters\FilterCheckbox;

use app\models\db\ProductFilter;
use app\modules\catalog\widgets\FilterWidget\models\ProductSearch;
use app\models\db\ProductAttribute;
use yii\base\Widget;
use app\models\db\ProductCategory;

/**
 * Виджет чекбокса для фильтров
 *
 * @property ProductFilter $filter
 * @property ProductAttribute $filterParams
 * @property string $searchModelName
 * @property ProductCategory $category
 */
class FilterCheckbox extends Widget
{
    public $category;
    public $filter;
    public $filterParams;
    public $searchModelName;

    public function run()
    {
        return $this->render('index', [
            'category' => $this->category,
            'searchModelName' => $this->searchModelName,
            'filter' => $this->filter,
            'filterParams' => $this->filterParams
        ]);
    }
}