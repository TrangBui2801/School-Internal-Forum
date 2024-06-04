<?php

use kartik\file\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\Thread $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="thread-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12">
            <?= $form->field($model, 'image[]')->widget(FileInput::classname(), [
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