<?php

use dosamigos\switchery\Switchery;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\GroupMember $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="group-member-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'groupId')->textInput() ?>

    <?= $form->field($model, 'memberId')->textInput() ?>

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

    <?= $form->field($model, 'creatorId')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
