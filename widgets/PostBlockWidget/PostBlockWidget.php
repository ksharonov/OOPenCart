<?php

namespace app\widgets\PostBlockWidget;

use yii\base\Widget;
use app\models\db\Post;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Карусель списка новостей, обзоров
 *
 * @property Post[] $model
 */
class PostBlockWidget extends Widget
{
    const TYPE_NEWS = 0;
    const TYPE_REVIEWS = 1;

    /** @var int лимит записей */
    public $limit = 3;

    /** @var array опции */
    public $options = [];

    /** @var int выбранный тип */
    public $type = self::TYPE_NEWS;

    /** @var array типы данных */
    public static $types = [
        self::TYPE_NEWS => 'Новости',
        self::TYPE_REVIEWS => 'Обзоры'
    ];

    /** @var array вьюхи */
    public static $views = [
        self::TYPE_NEWS => '_news',
        self::TYPE_REVIEWS => '_reviews'
    ];

    /** @var Post[] модель данных */
    private $model;

    /** @var string выбранная вьюха */
    private $view;

    /**
     * @return string
     */
    public function run(): string
    {
        return $this->render($this->view, [
            'model' => $this->model,
            'title' => self::$types[$this->type]
        ]);
    }

    /**
     * Инициализация виджета
     *
     * @return void
     */
    public function init()
    {
        $this->prepareOptions();
        $this->prepareModel();
        $this->prepareView();

        parent::init();
    }

    /**
     * Инициализация опций
     *
     * @return void
     */
    public function prepareOptions()
    {
        $options = [
            'class' => []
        ];

        if (!isset($this->options['class'])) {
            $this->options['class'] = "";
        } else {
            $options['class'] = array_merge($options['class'], explode(" ", $this->options['class']));
        }

        if ($this->type == self::TYPE_NEWS) {
            $options['class'][] = "news";
        } elseif ($this->type == self::TYPE_REVIEWS) {
            $options['class'][] = "reviews";
        }

        $this->options['class'] = implode(" ", $options['class']);
    }

    /**
     * Инициализация запроса
     * @return void
     */
    public function prepareModel()
    {
        /** @var ActiveQuery $model */
        $model = Post::find();

        $where = [];

        if ($this->type == self::TYPE_NEWS) {
            $where = [
                'type' => self::TYPE_NEWS,
                'status' => Post::POST_PUBLISHED
            ];
        } elseif ($this->type == self::TYPE_REVIEWS) {
            $where = [
                'type' => self::TYPE_REVIEWS,
                'status' => Post::POST_PUBLISHED
            ];
        }

        $model = $model
            ->where($where)
            ->limit($this->limit)
            ->orderBy('dtCreate DESC')
            ->all();

        $this->model = $model;
    }

    /**
     * Инициализация вьюхи
     *
     * @return void
     */
    public function prepareView()
    {
//        $this->view = self::$views[$this->type];
        $this->view = self::$views[self::TYPE_REVIEWS];
    }
}