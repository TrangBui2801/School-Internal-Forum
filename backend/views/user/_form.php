<?php

use dosamigos\switchery\Switchery;
use kartik\date\DatePicker;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use function PHPSTORM_META\type;

/** @var yii\web\View $this */
/** @var backend\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, '_username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, '_password')->textInput(['maxlength' => true, 'type' => 'password']) ?>

    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

    <div class="row">
        <div class="col col-lg-6 col-md-6 col-sm-12">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'birthday')->widget(DatePicker::classname(), [
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true
                ]
            ]) ?>
            <?= $form->field($model, 'gender')->widget(Select2::classname(), [
                'data' => $genders,
                'options' => ['placeholder' => 'Select gender...'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]) ?>
            <?= $form->field($model, 'departmentId')->widget(Select2::classname(), [
                'data' => $departments,
                'options' => ['placeholder' => 'Select department...'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]) ?>
        </div>
        <div class="col col-lg-6 col-md-6 col-sm-12">
            <?= $form->field($model, 'avatar[]')->widget(FileInput::classname(), [
                'options' => [
                    'multiple' => false,
                    'accept' => 'img/*',
                    'class' => 'form-control',
                    'placeholder' => 'maximum size is 4 MB',
                ],
                'pluginOptions' => [
                    'minFileCount' => 0,
                    'maxFileCount' => 5,
                    'maxFileSize' => '4096',
                    'initialPreview' => $all_files,
                    'initialPreviewAsData' => true,
                    'initialPreviewConfig' => $all_files_preview,
                    'fileActionSettings' => [
                        'showDownload' => false,
                        'showRemove' => true,
                    ],
                    'showUpload' => false,
                    // 'defaultPreviewContent' => yii\helpers\Url::to(["/common-files/images/empty-file.png"]),
                    'overwriteInitial' => true,
                    'showRemove' => TRUE,
                ]
            ]); ?>
        </div>
    </div>



    <?= $form->field($model, 'introduction')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'short_introduction')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'facebook_link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'skype_link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'github_link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'youtube_link')->textInput(['maxlength' => true]) ?>

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
        $('#user-phone_number').on('keyup', function() {
            $(this).val($(this).val().replace(/\D/g, ''));
        });
    });
</script>