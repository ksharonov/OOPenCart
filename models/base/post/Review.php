<?php

namespace app\models\base\post;

use app\models\db\Post;

/**
 * Class Review
 *
 * Класс модели Вакансий
 *
 * @package app\models\base\post
 */
class Review extends Post
{

    /**
     * @var array кастомные поля
     */
    public static $defaultParams;

    public function __construct(array $config = [])
    {
        $this->type = Post::TYPE_REVIEWS;
        parent::__construct($config);
    }

}