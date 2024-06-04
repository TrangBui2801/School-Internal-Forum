<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use dosamigos\ckeditor\CKEditor;
use kartik\depdrop\DepDrop;
use kartik\file\FileInput;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\Post $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col col-md-12 col-lg-4 col-sm-12">
            <?= $form->field($model, 'categoryId')->widget(Select2::classname(), [
                'data' => $categories,
                'options' => ['placeholder' => 'Select a Thread ...', 'id' => 'sltCategory'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]) ?>
        </div>
        <div class="col col-md-12 col-lg-4 col-sm-12">
            <?= $form->field($model, 'topicId')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'options' => ['placeholder' => 'Select a Topic ...', 'id' => 'sltTopic'],
                'pluginOptions' => [
                    'depends' => ['sltCategory'],
                    'allowClear' => false,
                    'url' => Url::to('../depdrop/get-topic-by-category')
                ],
            ]) ?>
        </div>

        <div class="col col-md-12 col-lg-4 col-sm-12">
            <?= $form->field($model, 'threadId')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'options' => ['placeholder' => 'Select a Thread ...', 'id' => 'sltThread'],
                'pluginOptions' => [
                    'depends' => ['sltTopic'],
                    'allowClear' => false,
                    'url' => Url::to('../depdrop/get-thread-by-topic')
                ],
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col col-md-12 col-lg-12 col-sm-12">
            <?= $form->field($model, 'cover_image[]')->widget(FileInput::classname(), [
                'options' => [
                    'multiple' => true,
                    'accept' => 'img/*',
                    'class' => 'form-control',
                    'placeholder' => 'maximum size is 4 MB',
                ],
                'pluginOptions' => [
                    'minFileCount' => 0,
                    'maxFileCount' => 1,
                    'maxFileSize' => '4096',
                    'initialPreview' => $all_cover_files,
                    'initialPreviewAsData' => true,
                    'initialPreviewConfig' => $all_cover_files_preview,
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

    <?= $form->field($model, 'title')->textarea(['rows' => 4, 'maxlength' => true]) ?>

    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full'
    ]) ?>

    <?= $form->field($model, 'short_description')->textarea(['row' => 4, 'maxlength' => true]) ?>

    <div class="row">
        <div class="col col-md-12 col-lg-12 col-sm-12">
            <?= $form->field($model, 'attachment[]')->widget(FileInput::classname(), [
                'options' => [
                    'multiple' => true,
                    'accept' => 'img/*', 'doc/*', 'file/*',
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