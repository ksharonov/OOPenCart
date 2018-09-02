<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var yii\web\View $this */
/* @var app\models\db\Product $model */
/* @var yii\widgets\ActiveForm $form */
/* @var \app\models\db\ProductReview $reviewModel */
?>
<?php
echo $this->render('review_popup', [
    'product' => $model
]);
?>
<?php if (isset(Yii::$app->user->identity->id) && false) { ?>
    <div class="block-form">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($reviewModel, 'rating')->dropDownList([1 => 1, 2, 3, 4, 5]) ?>

        <?= $form->field($reviewModel, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($reviewModel, 'positive')->textarea(['maxlength' => true]) ?>

        <?= $form->field($reviewModel, 'negative')->textarea(['maxlength' => true]) ?>

        <?= $form->field($reviewModel, 'comment')->textarea(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Оставить отзыв', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
<?php } ?>

<div class="reviews">
    <div class="reviews__summary">
        <div class="reviews__rate"><?= $model->rating ?></div>
        <div class="reviews__meta">
            <div class="rating rating_reviews">
                <div class="rating__fill rating__fill_sm rating__fill_<?= $model->rating ?>"></div>
            </div>
            <p class="reviews__numbers">
                Оценка покупателей рассчитана
                <br/>
                на
                основании <?= $model->reviewsCount ?> <?= \app\helpers\StringHelper::decline($model->reviewsCount, ['отзыва', 'отзыва', 'отзывов']) ?>
            </p>
        </div>
    </div>
    <div class="row">
        <h2 class="reviews__title col-sm-30">
            Отзывы о <?= $model->title; ?>
        </h2>
        <div class="col-sm-18 text-right">
            <button class="reviews__add" data-m-toggle="modal" data-m-target="#createReviewModal">Написать отзыв</button>
        </div>
    </div>
    <ul class="reviews__list">
        <?php foreach ($model->reviews as $review) { ?>
            <li class="reviews__item" itemscope itemtype="http://schema.org/Review">
                <div class="row">
                    <div class="col-lg-8 col-md-10">
                        <div class="reviews__info">
                            <div class="reviews__name" itemprop="author" itemscope itemtype="http://schema.org/Person">
                                <span itemprop="name"><?= $review->author ?></span>
                            </div>
                            <time class="reviews__date"><?= $review->dtc ?></time>
                            <!-- сохрани формат даты в мете -->
                            <meta itemprop="datePublished" content="2017-12-15"/>
                            <!-- класс и названия метаполей не такие же как наверху -->
                            <div class="rating rating_reviews" itemprop="reviewRating" itemscope
                                 itemtype="http://schema.org/Rating">
                                <div class="rating__fill rating__fill_sm rating__fill_<?= $review->rating ?>"></div>
                                <meta itemprop="worstRating" content="1">
                                <meta itemprop="ratingValue" content="<?= $review->rating ?>">
                                <meta itemprop="bestRating" content="5">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-40 col-md-38">
                        <div class="reviews__content">
                            <p class="reviews__text reviews__text_pro" itemprop="pro">
                                <?= $review->positive ?>
                            </p>
                            <p class="reviews__text reviews__text_con" itemprop="contra">
                                <?= $review->negative ?>
                            </p>
                            <p class="reviews__text reviews__text_comment" itemprop="reviewBody">
                                <?= $review->comment ?>
                            </p>
                        </div>
                    </div>
                </div>

            </li>
        <?php } ?>
    </ul>
</div>