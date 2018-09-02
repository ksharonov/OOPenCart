<?php

namespace app\system\template;

use unclead\multipleinput\components\BaseColumn;
use yii\base\Model;
use yii\helpers\Html;
use unclead\multipleinput\MultipleInputColumn;
use yii\base\DynamicModel;
use yii\helpers\Json;

/**
 * Class TemplateParams
 *
 * @property array $options
 *
 * @package app\system\template
 */
class TemplateParams extends Model
{

    /**
     * @var array настройки
     */
    public $setting = [
        [
            'key' => 'SET1',
            'title' => 'Название настройки 1'
        ],
        [
            'key' => 'SET2',
            'title' => 'Название настройки 1'
        ]
    ];

    /** @var array атрибуты */
    public static $attributes = [
        'setting' => 'Настройки'
    ];

    /** @var MultipleInputColumn[] */
    public static $attributesOptions = [
        'setting' => [
            'max' => 1,
            'min' => 1,
            'allowEmptyList' => true,
            'columns' => [
                [
                    'name' => 'key',
                    'title' => 'Ключ поля',
                    'type' => 'hiddenInput',
                    'options' => [
                    ]
                ],
                [
                    'name' => 'title',
                    'title' => 'Название',
                    'type' => BaseColumn::TYPE_HIDDEN_INPUT,
                    'options' => [
                    ]
                ],
                [
                    'name' => 'title',
                    'title' => 'Название',
                    'type' => BaseColumn::TYPE_STATIC,
                    'options' => [
                    ]
                ],
                [
                    'name' => 'value',
                    'title' => 'Значение',
                    'defaultValue' => 1,
                    'type' => BaseColumn::TYPE_CHECKBOX,
                ]
            ]
        ]
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['setting'], 'safe']
        ];
    }

}