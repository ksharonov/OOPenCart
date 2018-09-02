<?php
use app\widgets\ProductListWidget\ProductListWidget;

?>

    <!--    <h2>Похожие товары</h2>-->

<?= ProductListWidget::widget([
        'id' => 'similar-product-list',
        'title' => 'Аналоги',
        'models' => $model->productAnalogues,
        'isTurbo' => false,
        'enablePagination' => false,
        'perPage' => 4,
        'listOptions' => [
            'card' => [
                'itemClass' => 'col-lg-12 col-md-12 col-sm-24'
            ],
            'list' => [
                'itemClass' => 'col-lg-48'
            ]
        ]
    ]
); ?>