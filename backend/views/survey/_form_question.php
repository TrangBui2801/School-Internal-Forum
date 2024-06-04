<?php

use dosamigos\switchery\Switchery;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Question $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="question-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

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

    <?php foreach ($answers as $i => $answer) : ?>
        <fieldset class="card" style="padding: 10px;">
            <legend>Answer <?= $i + 1 ?>:</legend>
            <?= $form->field($answer, "[$i]content")->textarea(['row' => 2]) ?>
            <?= $form->field($answer, "[$i]explanation")->textarea(['row' => 3]) ?>
        </fieldset>
    <?php endforeach; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $('.answer-is-correct').change(function() {
        if ($(this).is(':checked')) {
            $('.answer-is-correct').prop('checked', false);
            $(this).prop('checked', true);
        }
    });
</script>