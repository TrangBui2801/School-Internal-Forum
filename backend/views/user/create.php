<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\User $model */

$this->title = Yii::t('app', 'Create User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'all_files' => $all_files,
        'all_files_preview' => $all_files_preview,
        'files_type' => $files_type,
        'model' => $model,
        'departments' => $departments,
        'genders' => $genders
    ]) ?>

</div>