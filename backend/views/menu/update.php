<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Menu $model */

$this->title = Yii::t('app', 'Update Menu: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="menu-update">

    <?= $this->render('_form', [
        'model' => $model,
        'parentMenus' => $parentMenus
    ]) ?>

</div>
