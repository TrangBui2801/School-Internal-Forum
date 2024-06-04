<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\EventAttendance $model */

$this->title = Yii::t('app', 'Create Event Attendance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Event Attendances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-attendance-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
