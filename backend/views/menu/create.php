<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Menu $model */

$this->title = Yii::t('app', 'Create Menu');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-create">

    <?= $this->render('_form', [
        'model' => $model,
        'parentMenus' => $parentMenus
    ]) ?>

</div>
