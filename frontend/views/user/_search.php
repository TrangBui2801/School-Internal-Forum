<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\UserSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, '_username') ?>

    <?= $form->field($model, '_password') ?>

    <?= $form->field($model, 'full_name') ?>

    <?= $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'phone_number') ?>

    <?php // echo $form->field($model, 'birthday') ?>

    <?php // echo $form->field($model, 'avatar') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'introduction') ?>

    <?php // echo $form->field($model, 'short_introduction') ?>

    <?php // echo $form->field($model, 'facebook_link') ?>

    <?php // echo $form->field($model, 'skype_link') ?>

    <?php // echo $form->field($model, 'github_link') ?>

    <?php // echo $form->field($model, 'youtube_link') ?>

    <?php // echo $form->field($model, 'departmentId') ?>

    <?php // echo $form->field($model, 'role') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'auth_key') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
