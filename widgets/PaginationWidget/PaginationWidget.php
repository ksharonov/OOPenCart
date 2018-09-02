<?php

namespace app\widgets\PaginationWidget;

use Yii;
use yii\base\Exception;
use yii\base\Widget;
use yii\data\Pagination;
use yii\helpers\Url;

/**
 * Виджет прослойки для виджета пагинации
 *
 * @property Pagination|false $pagination
 */
class PaginationWidget extends Widget
{

    /**
     * @var integer id-списка
     */
    public $id = null;

    /**
     * @var Pagination | null
     */
    public $pagination = null;

    /**
     * @var integer количество страниц, которые нужно прибавить
     */
    public $pageMoreCount = 12;

    public $showAll = false;

    /**
     * Инициализация
     * @throws Exception
     */
    public function init()
    {
        if (!$this->id) {
            throw new Exception('Отсутствие идентификатора виджета');
        }

        if (!($this->pagination instanceof Pagination)) {
            throw new Exception('Входной параметр $pagination не является объектом класса yii\data\Pagination');
        }
    }

    public function run()
    {
        $perPage = Yii::$app->request->get('per-page') ?? $this->pagination->pageSize ?? null;
        $page = Yii::$app->request->get('page') ?? $this->pagination->page ?? null;

        $currentUrl = Url::current([
            'page' => $page,
            'per-page' => $perPage + $this->pageMoreCount,
            '_pjax' => "#$this->id"
        ]);

        // кнопка "Показать все"
        $showAll = null;

        if (Yii::$app->controller->id == 'search') {
		    $perPage = 1000000;
        } else {
            $perPage = 0;
        }

        if ($this->showAll) {
            $showAll = Url::current([
                'page' => 0,
                'per-page' => $perPage,
                '_pjax' => "#$this->id"
            ]);
        }

        if ($this->pagination->totalCount > $this->pagination->pageSize) {
            return $this->render('index', [
                'currentUrl' => $currentUrl,
                'pagination' => $this->pagination,
                'showAll' => $showAll,
            ]);
        }
    }

}