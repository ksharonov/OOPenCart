<?php
use app\models\db\User;
use app\models\db\Client;
use yii\widgets\Pjax;
use app\widgets\PaginationWidget\PaginationWidget;

/* @var yii\web\View $this */
/* @var User $user */
/* @var Client $client */
/* @var \app\models\search\DocumentSearchProfile $searchModel */
/* @var \yii\data\ActiveDataProvider $dataProvider */

//dump($client->files);
?>
<?php Pjax::begin(['timeout' => 10000]) ?>
<?php //echo $this->render('_search', ['model' => $searchModel]); ?>

<?php
//dump($dataProvider->models);
?>

<?php Pjax::end() ?>

<?php Pjax::begin(['timeout' => 10000, 'options' => ['class' => 'documents']]) ?>
    <div class="row">
        <h2 class="profile__title profile__title_mini col-lg-48">Документы</h2>
        <div class="documents__list">
            <?php foreach ($dataProvider->models as $model) { ?>
                <div class="col-lg-16 col-md-24 col-sm-24">
                    <div class="documents__item">
                        <div class="documents__image-wrap">
                            <img class="documents__image" src="/img/icons/documents__image.png" alt="<?= $model->title ?>">
                        </div>
                        <div class="documents__content">
                            <p class="documents__name">
                                <?= $model->title ?>
                            </p>
                            <a href="<?= $model->path ?>" class="documents__download" download="<?= $model->title . ".pdf" ?>" data-pjax="0">Скачать</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="clearfix"></div>
        </div>
        <?= PaginationWidget::widget([
            'id' => 'orders-list',
            'pagination' => $dataProvider->pagination
        ]);
        ?>
    </div>
<?php Pjax::end() ?>