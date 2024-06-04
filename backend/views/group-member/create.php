<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\GroupMember $model */

$this->title = Yii::t('app', 'Create Group Member');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Group Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-member-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
