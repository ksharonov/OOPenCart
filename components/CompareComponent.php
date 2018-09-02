<?php

namespace app\components;

use app\helpers\CompareHelper;
use Yii;
use yii\base\Component;
use yii\helpers\Json;
use app\models\base\Compare;

/**
 * Class CartComponent
 * Компонент данных по корзине
 * @package app\components
 * @property int $sum
 * @property int $count
 */
class CompareComponent extends Component
{
    /**
     * Объект сравнения
     * @var Compare
     */
    public $compare;

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {
        if (!$this->compare) {
            $this->build();
        }

        parent::__construct($config);
    }

    /**
     * Сборка сравнения в удобный вид
     *
     * @return void
     */
    public function build()
    {
        $cookies = Yii::$app->request->cookies;
        $compareCookie = Json::decode($cookies->getValue('compare')) ?? [];
        $this->compare = CompareHelper::createCompareData($compareCookie);
    }

}