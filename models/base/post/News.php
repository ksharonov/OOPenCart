<?php

namespace app\models\base\post;

use app\models\db\Post;

/**
 * Class News
 *
 * Класс модели новостей
 *
 * @package app\models\post\base
 */
class News extends Post
{

    /**
     * @var array кастомные поля
     */
    public static $defaultParams;

    public function __construct(array $config = [])
    {
        $this->type = Post::TYPE_NEWS;
        parent::__construct($config);
    }

}