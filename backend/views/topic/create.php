<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Topic $model */

$this->title = Yii::t('app', 'Create Topic');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Topics'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'all_files' => $all_files,
        'all_files_preview' => $all_files_preview,
        'files_type' => $files_type,
        'categories' => $categories,
        'moderatorIds' => $moderatorIds,
    ]) ?>

</div>