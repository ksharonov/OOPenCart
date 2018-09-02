<?php
/**
 * Created by PhpStorm.
 * User: aleksey
 * Date: 04.05.2018
 * Time: 13:58
 */

namespace app\models\behaviors;


use yii\base\Behavior;

class ProductSqlBehavior extends Behavior
{
    public $sqlIsSale;
    public $sqlIsNew;
    public $sqlIsBest;
    public $sqlPrices;
    public $sqlStorages;
    public $sqlManufacturer;
    public $sqlGuid;
    public $sqlRating;

    /** Возвращает объект со свойствами retail(розница) и wholesale(опт)
     * (без SQL запроса)
     * @return mixed|\stdClass
     */
    public function getSqlPrice()
    {
        $prices = json_decode($this->sqlPrices);
        return $prices;
    }

    /** Остаток товара по всем складам
     * (без SQL запроса)
     * @return int|float
     */
    public function getSqlBalance()
    {
        $retSumm = 0;
        $summ = json_decode($this->sqlStorages) ?? [];
        //todo не бейте, но лучше быстро, чем долго
        foreach ($summ as $key => $item) {
            if ($key != 'summ' && !is_null($item)) {
                $retSumm += $item;
            }
        }
//        dump($retSumm);
        return $retSumm;
        return $summ->summ ?? 0;
    }

    /** Возвращает список складов, на которых есть остаток по товару
     * (без SQL запроса)
     * @return Storage[]
     */
    public function getSqlBalances()
    {
        /** @var Storage[] $storages */
        $storages = \Yii::$app->registry->storages;

        if ($this->sqlStorages) {
            $balances = json_decode($this->sqlStorages, true);
            if (is_array($balances)) {
                array_pop($balances);
                $storagesWithBalance = [];
                foreach ($balances as $id => $balance) {
                    if ($balance) {
                        foreach ($storages as $storage) {
                            if ($storage->id === $id) {
                                $storage->_quantity = $balance;
                                $storagesWithBalance[] = $storage;
                            }
                        }
                    }
                }
                return $storagesWithBalance;
            }
        }
        return [];
    }

    public function getAverageRating()
	{
		return (int)$this->sqlRating;
	}
}