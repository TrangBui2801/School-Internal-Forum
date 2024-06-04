<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Post $model */

$this->title = Yii::t('app', 'Update Post: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="post-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'all_files' => $all_files,
        'all_files_preview' => $all_files_preview,
        'all_cover_files' => $all_cover_files,
        'all_cover_files_preview' => $all_cover_files_preview,
        'files_type' => $files_type,
        'model' => $model,
        'isPrivate' => $isPrivate,
        'categories' => $categories,
        'listBadWords' => $listBadWords,
    ]) ?>

</div>