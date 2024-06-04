<?php

use dosamigos\switchery\Switchery;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Survey $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="survey-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="survey-date row">
        <div class="col col-md-12 col-lg-12 col-sm-12 mb-10">
            <?= $form->field($model, 'title')->textarea(['row' => 3])  ?>
        </div>
    </div>

    <div class="survey-date row">
        <div class="col col-md-12 col-lg-6 col-sm-12 mb-10">
            <?= $form->field($model, 'start_date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true
                ]
            ]) ?>
        </div>
        <div class="col col-md-12 col-lg-6 col-sm-12 mb-10">
            <?= $form->field($model, 'end_date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true
                ]
            ]) ?>
        </div>
    </div>

    <div class="survey-status row">
        <div class="col col-md-12 col-sm-12 col-lg-6">
            <?= $form->field($model, 'surveyQuestionNumber')->textInput(['type' => 'number']); ?>
        </div>
        <div class="col col-md-12 col-sm-12 col-lg-6">
            <label for="">Status</label>
            <?= $form->field($model, 'status')->widget(Switchery::class, [
                'options' => [
                    'label' => false
                ],
                'class' => 'mr-20',
                'clientOptions' => [
                    'size' => 'large',
                    'onColor' => 'success',
                    'offColor' => 'danger'
                ]
            ])->label(false); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>