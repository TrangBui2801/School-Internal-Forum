<?php

use dosamigos\switchery\Switchery;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Answer $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="answer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'questionId')->textInput() ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'explanation')->textarea(['rows' => 6]) ?>

    <div class="row">
        <div class="col col-lg-6 col-md-6 col-sm-6" style="display: flex; align-items: center; position: relative;">
            <div>
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
        <div class="col col-lg-6 col-md-6 col-sm-6">
        <div>
                <label for="">Is correct</label>
                <?= $form->field($model, 'is_correct')->widget(Switchery::class, [
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
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
