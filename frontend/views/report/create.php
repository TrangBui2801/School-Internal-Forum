<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Report $model */

$this->title = Yii::t('app', 'Create Report');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
