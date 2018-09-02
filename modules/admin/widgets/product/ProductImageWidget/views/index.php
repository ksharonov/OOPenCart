<?php

use mihaildev\elfinder\ElFinder;
use yii\web\JsExpression;
use mihaildev\elfinder\InputFile;
use app\models\db\Product;

/* @var Product $productModel */
?>

<div class="product-image-widget">
    <br>
    <button type="button" class="btn btn-primary btn-flat btn-sm" data-toggle="modal" data-target="#addImageModal">
        Добавить изображение
    </button>
    <br><br>


    <div class="modal fade" id="addImageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document" style="width: 80%; height: 80%;">
            <div class="modal-content" style="height: 80%;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Атрибуты</h4>
                </div>
                <div class="modal-body" style="height: 80%;">
                    <?= ElFinder::widget([
                        'language' => 'ru',
                        'controller' => 'admin/elfinder',
                        'path' => 'image',
                        'filter' => 'image',
                        'multiple' => false,
                        'containerOptions' => ['style' => 'height: 100%;'],
                        'callbackFunction' => new JsExpression("function(file, id){productImage.add(file, {$productModel->id})}") // id - id виджета
                    ]); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row product-images">
        <?php foreach ($productModel->images as $image) { ?>
            <div class="col-md-2 product-images__item">
                <a href="#" class="thumbnail product-images__link" data-id="<?= $image->id ?>">
                    <img class="product-images__image" src="<?= $image->path ?>">
                </a>
            </div>
        <?php } ?>
    </div>

</div>
