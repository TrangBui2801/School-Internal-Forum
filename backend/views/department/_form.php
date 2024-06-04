<?php

use dosamigos\switchery\Switchery;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Department $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="department-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'short_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?>

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
<script>
    $(document).ready(function() {
        $('#department-phone_number').on('keyup', function() {
            $(this).val($(this).val().replace(/\D/g, ''));
        });
    });
</script>