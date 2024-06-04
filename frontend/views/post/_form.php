<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use dosamigos\ckeditor\CKEditor;
use kartik\depdrop\DepDrop;
use kartik\file\FileInput;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var frontend\models\Post $model */
/** @var yii\widgets\ActiveForm $form */
?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/default.min.css">


<section class="post-content">
    <?php 
    $threadId = Yii::$app->getRequest()->getQueryParam('threadId');
    if ($threadId)
    {
        $formAction = Url::to(['post/create', 'threadId' => $threadId]);
    }
    else
    {
        $formAction = Url::to(['post/create']);
    }
    $actionURL = 
    $form = ActiveForm::begin([
        'method' => 'POST',
        'action' => $formAction
    ]); ?>
    <div class="container-fluid">
        <?php if (!$isPrivate) : ?>
            <div class="row">
                <div class="col-md-12 col-lg-4 col-sm-12 mb-3">
                    <?= $form->field($model, 'categoryId')->widget(Select2::classname(), [
                        'data' => $categories,
                        'options' => ['placeholder' => 'Select a Thread ...', 'id' => 'sltCategory'],
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]) ?>
                </div>
                <div class="col-md-12 col-lg-4 col-sm-12 mb-3">
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
                <div class="col-md-12 col-lg-4 col-sm-12 mb-3">
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
        <?php endif; ?>
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
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'title')->textarea(['rows' => 3, 'maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'short_description')->textarea(['rows' => 4, 'maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'content')->widget(CKEditor::className(), [
                    'options' => ['rows' => 6],
                    'preset' => 'basic',
                    'clientOptions' => [
                        'height' => 400,
                        'toolbarGroups' => [
                            ['name' => 'document', 'groups' => ['mode', 'document', 'doctools']],
                            ['name' => 'clipboard', 'groups' => ['clipboard', 'undo']],
                            ['name' => 'editing', 'groups' => ['find', 'selection', 'spellchecker']],
                            ['name' => 'forms'],
                            '/',
                            ['name' => 'basicstyles', 'groups' => ['basicstyles', 'colors', 'cleanup']],
                            ['name' => 'paragraph', 'groups' => ['list', 'indent', 'blocks', 'align', 'bidi']],
                            ['name' => 'links'],
                            ['name' => 'insert'],
                            '/',
                            ['name' => 'styles'],
                            ['name' => 'blocks'],
                            ['name' => 'colors'],
                            ['name' => 'tools'],
                            ['name' => 'others'],
                        ],
                    ]
                ]) ?>
            </div>
        </div>
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
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-jump btn-post btn-shadow" id="submit-post">Save</button>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</section>
<script>
    $(document).ready(function() {
        let badWords = new Array();
        <?php foreach ($listBadWords as $badWord) : ?>
            badWords.push('<?= $badWord ?>');
        <?php endforeach; ?>
        function checkBadWord() {
            let content = $('#post-content').val();
            let canSubmit = true;
            if (content != "") {
                $.each(badWords, function(index, value) {
                    var regExp = new RegExp(value, "ig");
                    var matches = content.match(regExp);
                    if (matches) {
                        var test = '<span style="background-color:#f1c40f">' + value + '</span>';
                        content = content.replace(regExp, test);
                        canSubmit = false;
                    }
                });
                CKEDITOR.instances['post-content'].setData(content);
            }
            return canSubmit;
        };
        $('form button[type=submit]').on('click', function(event) {
            console.log(CKEDITOR.instances['post-content'].getData())
            // CKEDITOR.instances['post-content'].setData("Check");
            let canSubmit = checkBadWord();
            if (!canSubmit)
            {
                event.preventDefault();
            }
        });
    })
</script>