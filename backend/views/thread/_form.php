<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\switchery\Switchery;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\Thread $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="thread-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    
    <?= $form->field($model, 'sDescription')->textarea(['rows' => 2]) ?>

    <div class="row">
        <div class="row col col-lg-6 col-md-6 col-sm-12">

            <div class="col-lg-12 col-sm-12 col-md-12">
                <?= $form->field($model, 'short_description')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12">
                <?= $form->field($model, 'moderatorId')->widget(Select2::classname(), [
                    'data' => $moderatorIds,
                    'options' => ['placeholder' => 'Select moderator...'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]) ?>
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12">
                <?= $form->field($model, 'topicId')->widget(Select2::classname(), [
                    'data' => $topics,
                    'options' => ['placeholder' => 'Select topic...'],
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]) ?>
            </div>
            <div class="col-lg-12 col-sm-12 col-md-12" ;">
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
        <div class="col col-lg-6 col-md-6 col-sm-12">
            <?= $form->field($model, 'file[]')->widget(FileInput::classname(), [
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



    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>