<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Thread $model */

$this->title = Yii::t('app', 'Create Thread');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Threads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="thread-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
