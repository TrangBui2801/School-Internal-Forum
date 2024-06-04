<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Question $model */

$this->title = Yii::t('app', 'Update Survey Question ' . $questionOrder);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Survey'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Question' . $questionOrder];
?>
<div class="question-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_question', [
        'model' => $model,
        'answers' => $answers,
    ]) ?>

</div>