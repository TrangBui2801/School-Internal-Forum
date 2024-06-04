<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\TestLevel $model */

$this->title = Yii::t('app', 'Create Test Level');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Test Levels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-level-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
