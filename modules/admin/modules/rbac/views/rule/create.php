<?php

use yii\helpers\Html;

/* @var $this  yii\web\View */
/* @var $model \app\modules\admin\modules\rbac\models\BizRuleModel */

$this->title = Yii::t('yii2mod.rbac', 'Create Rule');
$this->params['breadcrumbs'][] = ['label' => Yii::t('yii2mod.rbac', 'Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->render('/layouts/_sidebar');
?>
<div class="rule-item-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]); ?>

</div>