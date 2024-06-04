<?php

use dosamigos\switchery\Switchery;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Question $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="question-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

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
            ])?>
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
        <div class="col-md-12 col-lg-4 col-sm-12 mb-3">
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
    </div>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 mb-3">
            <?= $form->field($model, 'score')->textInput() ?>
        </div>
    </div>

    <?php foreach ($answers as $i => $answer) : ?>
        <fieldset class="card" style="padding: 10px;">
            <legend>Answer <?= $i + 1 ?>:</legend>
            <?= $form->field($answer, "[$i]content")->textarea(['row' => 2]) ?>
            <?= $form->field($answer, "[$i]explanation")->textarea(['row' => 3]) ?>
            <div class="col-lg-12 col-sm-12 col-md-12" ;">
                <?= $form->field($answer, "[$i]is_correct")->checkbox(['class' => 'answer-is-correct']); ?>
            </div>
        </fieldset>
    <?php endforeach; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $('.answer-is-correct').change(function() {
        if ($(this).is(':checked')) {
            $('.answer-is-correct').prop('checked', false);
            $(this).prop('checked', true);
        }
    });
</script>