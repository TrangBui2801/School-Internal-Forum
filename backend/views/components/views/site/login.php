<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Admin login');
?>
<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <div class="login100-pic js-tilt" data-tilt>
                <img src="../dist/img/img-01.png" alt="IMG">
            </div>
            <?php $form = ActiveForm::begin(['id' => 'login-form', 'class' => "login100-form validate-form"]) ?>
            <span class="login100-form-title">
                Admin Login
            </span>

            <div class="wrap-input100 validate-input" data-validate="Username is required">
                <?= $form->field($model, 'username')
                    ->label(false)
                    ->textInput(['placeholder' => $model->getAttributeLabel('username'), 'class' => 'input100']) ?>
                <span class="focus-input100"></span>
                <span class="symbol-input100">
                    <i class="fa fa-user" aria-hidden="true"></i>
                </span>
            </div>

            <div class="wrap-input100 validate-input" data-validate="Password is required">
                <?= $form->field($model, 'password')
                    ->label(false)
                    ->passwordInput(['placeholder' => $model->getAttributeLabel('password'), 'class' => 'input100']) ?>
                <span class="focus-input100"></span>
                <span class="symbol-input100">
                    <i class="fa fa-lock" aria-hidden="true"></i>
                </span>
            </div>
            <div class="container-login100-form-btn">
                <?= Html::submitButton('Sign In', ['class' => 'login100-form-btn']) ?>
            </div>

            <div class="text-center p-t-12">
                
            </div>

            <div class="text-center p-t-136">
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>