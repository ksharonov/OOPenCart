<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\modules\admin\modules\rbac\models\AuthItemModel */

$context = $this->context;
$labels = $this->context->getLabels();
$this->title = Yii::t('yii2mod.rbac', 'Update ' . $labels['Item'] . ' : {0}', $model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.rbac', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('yii2mod.rbac', 'Update');
$this->render('/layouts/_sidebar');
?>
<div class="auth-item-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>

</div>