<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\system\template\TemplateLoader;

AppAsset::register($this);

/** @var \yii\base\Module | \yii\base\Application $context */
$context = $this->context;

$templateLoader = TemplateLoader::register();
$templateLoader->setOptions([
    'moduleId' => $context->module->id
]);

$view = &$this;

$view->beginPage();

echo $templateLoader->getLayout([
    'view' => $view,
    'templateLoader' => $templateLoader,
    'content' => $content
]);

$view->endPage();
?>