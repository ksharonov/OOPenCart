<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\modules\admin\modules\rbac\models\BizRuleModel */

$this->title = Yii::t('yii2mod.rbac', 'Update Rule : {0}', $model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.rbac', 'Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('yii2mod.rbac', 'Update');
$this->render('/layouts/_sidebar');
?>
<div class="rule-item-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>
</div>