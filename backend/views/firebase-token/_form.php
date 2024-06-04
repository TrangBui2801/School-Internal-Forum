<?php

use dosamigos\switchery\Switchery;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\FirebaseToken $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="firebase-token-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'userId')->textInput() ?>

    <?= $form->field($model, 'deviceId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deviceToken')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'deviceType')->textInput(['maxlength' => true]) ?>

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

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
