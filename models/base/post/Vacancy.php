<?php

namespace app\models\base\post;

use app\models\db\Post;

/**
 * Class Vacancy
 *
 * Класс модели Вакансий
 *
 * @package app\models\base\post
 */
class Vacancy extends Post
{

    /**
     * @var array кастомные поля
     */
    public static $defaultParams = [
        [
            'title' => 'Опыт работы',
            'key' => 'experience',
            'value' => '1-3 года'
        ],
        [
            'title' => 'Зарплата',
            'key' => 'salary',
            'value' => '8000'
        ],
        [
            'title' => 'Город',
            'key' => 'city',
            'value' => 'Нефтекамск'
        ]
    ];

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название вакансии',
            'content' => 'Контент',
            'dtCreate' => 'Дата создания',
            'dtUpdate' => 'Дата обновления',
            'status' => 'Статус',
            'thumbnail' => 'Изображение',
            'slug' => 'Название ссылки',
            'type' => 'Тип поста',
            'params' => 'Параметры',
            'categoryId' => 'Категория'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'dtCreate', 'status'], 'required'],
            [['dtCreate', 'dtCreate', 'params', 'categoryId'], 'safe'],
            [['status', 'categoryId', 'type'], 'integer'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['thumbnail'], 'string'],
            [['slug'], 'slugValidator']
        ];
    }

    public function __construct(array $config = [])
    {
        $this->type = Post::TYPE_VACANCY;
        $this->categoryId = null;
        parent::__construct($config);
    }
}