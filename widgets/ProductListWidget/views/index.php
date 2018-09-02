<?php
use yii\widgets\Pjax;
use yii\widgets\ListView;
use app\widgets\PaginationWidget\PaginationWidget;
use app\widgets\ProductListWidget\ProductListWidget;
use app\modules\catalog\widgets\SortWidget\SortWidget;
use app\modules\catalog\widgets\FastFilterWidget\FastFilterWidget;

/* @var yii\web\View $this */
/* @var integer $id */
/* @var app\models\search\ProductSearch $searchModel */
/* @var yii\data\ActiveDataProvider $dataProvider */
/* @var array $listOptions */
/* @var bool $enablePagination */
/* @var string $activeViewMode */
/* @var string $mode */
/* @var ProductListWidget $context */

$mode = ProductListWidget::$modes[$mode];
$context = $this->context;
if ($isTurbo){
    $viewPostfix = '_itemTurbo';
} else {
    $viewPostfix = '_item';
}
?>
<!--<button class="_change_product_list_view">Сменить вид</button>-->
<div class="_current_url" data-value="<?= \yii\helpers\Url::current() ?>"></div>
<?php Pjax::begin([
//    'enablePushState' => true,
//    'scrollTo' => 100,
    'id' => 'product-list',
    'options' => [
        'class' => 'row',
        'data-name' => $id,
        'data-mode' => $mode,
        'data-title' => $title,
        'data-addition-views' => implode(', ', $context->additionViews),
        'data-min-price' => (int)$minMax['min'],
        'data-max-price' => (int)$minMax['max']
    ],
    'timeout' => 10000
]);
//dump($dataProvider->pagination);
//dump($dataProvider->models);
?>

<?php if ($title) { ?>
    <h2 class="_220v__section-title col-sm-48"><?= $title ?></h2>
<?php } ?>
<?php
//dump($dataProvider->models);
?>
<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => 'card/_itemTurbo',
    'layout' => "{items}",
    'options' => [
        'data-view' => ProductListWidget::MODE_VIEW_CARD,
        'class' => 'product-grid product-grid__list clearfix',
        'style' => $activeViewMode != ProductListWidget::MODE_VIEW_CARD ? [
            'display' => 'none'
        ] : []
    ],
    'itemOptions' => [
        'class' => $listOptions['card']['itemClass']
    ]
]); ?>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => 'list/_itemTurbo',
    'layout' => "{items}",
    'options' => [
        'data-view' => ProductListWidget::MODE_VIEW_LIST,
        'class' => 'product-grid clearfix',
        'style' => $activeViewMode != ProductListWidget::MODE_VIEW_LIST ? [
            'display' => 'none'
        ] : []
    ],
    'itemOptions' => [
        'class' => $listOptions['list']['itemClass']
    ]
]); ?>

<?php
if ($enablePagination) {
    echo PaginationWidget::widget([
        'id' => $id,
        'pagination' => $dataProvider->pagination,
        'showAll' => true,
    ]);
}

?>

<?php Pjax::end(); ?>
