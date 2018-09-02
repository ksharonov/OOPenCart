<?php

namespace app\widgets\TabsWidget;

use app\system\base\Controller;
use app\system\template\TemplateLoader;
//use yii\base\Widget;
use app\system\base\Widget;
use yii\db\ActiveRecord;
use app\widgets\TabsWidget\TabsAsset;
use app\widgets\TabsWidget\interfaces\ITabsOptions;
use yii\helpers\Json;

/**
 * Виджет для табов. В массив табов можно передать класс выводимого виджета или вьюху таба. У вьюхи приоритета больше,
 * поэтому используется либо одно, либо другое.
 *
 * @property string $id
 * @property ActiveRecord $model
 * @property array $params
 * @property array $tabs
 * @property ITabsOptions $options
 */
class TabsWidget extends Widget
{


    public $id;
    public $viewPath;
    public $model = null;
    public $params = [];


    /**
     * @var ITabsOptions олоо
     */
    public $options = [
        'step' => false
    ];

    public $tabs = [
        [
            'id' => null,
            'title' => null,
            'viewPath' => null,
            'view' => null,
            'widget' => null,
            'model' => null,
            'hide' => false,
            'params' => [],
            'listTabOptions' => null,
            'listContentOptions' => null
        ]
    ];


    public function init()
    {
        $this->options = (object)$this->options;
        parent::init();
    }

    public function run()
    {
        if (!$this->id) {
            return null;
        }

        $view = $this->getView();
        TabsAsset::register($view);

        $id = 0;
        foreach ($this->tabs as $key => &$tab) {
            if (!key_exists('id', $tab)) {
                $tab['id'] = 'tab' . $id;
            }

            $tab['id'] = $this->id . "-" . $tab['id'];

            if (!key_exists('title', $tab)) {
                $tab['title'] = 'Tab' . $id;
            }

            if (!key_exists('params', $tab)) {
                $tab['params'] = [];
            }

            if (!key_exists('model', $tab)) {
                $tab['model'] = null;
            }

            if (!key_exists('listTabOptions', $tab)) {
                $tab['listTabOptions'] = null;
            } else {
                $tab['listTabOptions'] = (object)$tab['listTabOptions'];
            }

            if (!key_exists('listContentOptions', $tab)) {
                $tab['listContentOptions'] = null;
            } else {
                $tab['listContentOptions'] = (object)$tab['listContentOptions'];
            }

            if (key_exists('hide', $tab) && $tab['hide']) {
                unset($this->tabs[$key]);
            }

            $id++;

            $tab = (object)$tab;
        }

        return $this->render('index', [
            'id' => $this->id,
            'viewPath' => $this->viewPath,
            'model' => $this->model,
            'tabs' => $this->tabs,
            'params' => $this->params,
            'options' => $this->options
        ]);
    }
}