<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Thread $model */

$this->title = Yii::t('app', 'Update group: {name}', [
    'name' => $model->name,
]);
?>
<div class="group-update">

    <?= $this->render('_group-form', [
        'all_files' => $all_files,
        'all_files_preview' => $all_files_preview,
        'files_type' => $files_type,
        'model' => $model,
    ]) ?>

</div>