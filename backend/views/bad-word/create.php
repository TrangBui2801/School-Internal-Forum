<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\BadWord $model */

$this->title = Yii::t('app', 'Create Bad Word');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bad Words'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bad-word-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
