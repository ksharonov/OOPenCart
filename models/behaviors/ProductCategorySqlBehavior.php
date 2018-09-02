<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 04.05.2018
 * Time: 14:47
 */

namespace app\models\behaviors;


use yii\base\Behavior;

class ProductCategorySqlBehavior extends Behavior
{
	/**
	 * Список id дочерних категорий
	 * @var
	 */
	public $_sqlChilds;

	/**
	 * Количество товаров в этой категории
	 * @var
	 */
	public $sqlTotalCount;

	/** Возвращает массив с ID дочерних категорий
	 * @return array|null
	 */
	public function getSqlChilds()
	{
		if ($this->_sqlChilds) {
			$childs = explode(",", $this->_sqlChilds);
			return $childs;
		}
		return null;
	}

	public function setSqlChilds($sqlChilds)
	{
		$this->_sqlChilds = $sqlChilds;
	}
}