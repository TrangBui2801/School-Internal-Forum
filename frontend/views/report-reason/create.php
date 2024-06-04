<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\ReportReason $model */

$this->title = Yii::t('app', 'Create Report Reason');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Report Reasons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="report-reason-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
