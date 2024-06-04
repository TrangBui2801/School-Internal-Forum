<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Thread $model */

$this->title = Yii::t('app', 'Create Thread');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Threads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thread-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'all_files' => $all_files,
        'all_files_preview' => $all_files_preview,
        'files_type' => $files_type,
        'topics' => $topics,
        'moderatorIds' => $moderatorIds,
    ]) ?>

</div>
