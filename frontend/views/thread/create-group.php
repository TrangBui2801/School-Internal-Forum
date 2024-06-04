<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Thread $model */

$this->title = Yii::t('app', 'Create Group');
?>
<div class="thread-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_group-form', [
        'all_files' => $all_files,
        'all_files_preview' => $all_files_preview,
        'files_type' => $files_type,
        'model' => $model,
    ]) ?>

</div>
