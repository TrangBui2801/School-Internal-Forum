<?php

use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\Test $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="content_test" style="padding: 20px;">
    <div class="start-test">
        <img src="../images/test_img.png" alt="">
        <div class="start-test-title">
            <h1>Welcome to the online test</h1>
        </div>
    </div>
    <?php $form = ActiveForm::begin([
        'action' => Url::to('../test/create'),
        'method' => 'POST',
        'id' => 'create-test'
    ]); ?>
    <div class="row">
        <div class="col-md-12 col-lg-6 col-sm-12 mb-3">
            <?= $form->field($model, 'categoryId')->widget(Select2::classname(), [
                'data' => $categories,
                'options' => ['placeholder' => 'Select a Category ...', 'id' => 'sltCategory'],
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]) ?>
        </div>
        <div class="col-md-12 col-lg-6 col-sm-12 mb-3">
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
        <div class="col-md-12 col-lg-6 col-sm-12 mb-3">
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
        <div class="col-md-12 col-lg-6 col-sm-12 mb-3">
            <?= $form->field($model, 'levelId')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'options' => ['placeholder' => 'Select a level ...', 'id' => 'sltLevel'],
                'pluginOptions' => [
                    'depends' => ['sltThread'],
                    'allowClear' => false,
                    'url' => Url::to('../depdrop/get-test-level')
                ],
            ]) ?>
        </div>
        <div class="start-test">
            <h3>Begin</h3>
            <button type="submit">
                <img src="../images/gif/Gif_arrow_left.gif" alt="" class="gif-arrow-left">
            </a>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>