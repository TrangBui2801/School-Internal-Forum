<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\switchery\Switchery;
use kartik\select2\Select2;
use yii\web\JsExpression;

/** @var yii\web\View $this */
/** @var backend\models\Menu $model */
/** @var yii\widgets\ActiveForm $form */
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/3.2.0/css/fontawesome-iconpicker.css" integrity="sha512-9yS+ck0i78HGDRkAdx+DR+7htzTZJliEsxQOoslJyrDoyHvtoHmEv/Tbq8bEdvws7s1AVeCjCMOIwgZTGPhySw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parentId')->widget(Select2::classname(), [
        'data' => $parentMenus,
        'options' => ['placeholder' => 'Select a parent menu or left blank'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col col-lg-3 col-md-3 col-sm-12" style="display: flex; align-items: center; position: relative;">
            <div style="position: absolute; top: 1%; left: 3%; translate(0, -50%);">
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
        <div class="col col-lg-9 col-md-9 col-sm-12">
            <?= $form->field($model, 'url')->textInput(['maxlength' => true, 'readonly' => true]) ?>
        </div>
    </div>


    <?= $form->field($model, 'icon')->textInput(['maxlength' => true, 'readonly' => true]) ?>

    <?= $form->field($model, 'icon_style')->textInput(['maxlength' => true, 'readonly' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/3.2.0/js/fontawesome-iconpicker.min.js" integrity="sha512-7dlzSK4Ulfm85ypS8/ya0xLf3NpXiML3s6HTLu4qDq7WiJWtLLyrXb9putdP3/1umwTmzIvhuu9EW7gHYSVtCQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        $('.iconpicker-popover').addClass('w-100');
        $('.iconpicker-item').on('click', function() {
            $('#menu-icon_style').val($.trim($(this).attr('title').substring(1, 4)));
        });
        $('#menu-label').on('keyup', function() {
            if ($(this).val() == "")
            {
                $('#menu-url').val("");
            }
            else
            {
                let parent = $('#menu-parentid').val();
                if (parent != "") {
                    let url = "/" + convertLabelToUrl($(this).val());
                    url += "/index";
                    $('#menu-url').val(url);
                }
            }
        });

        function convertLabelToUrl(str) {
            strVal = '';
            str = str.split(' ');
            let idx = 1;
            for (let chr = 0; chr < str.length; chr++) {
                if (chr >= idx)
                {
                    strVal += "-";
                }
                strVal += str[chr].toLowerCase();
            }
            return strVal;
        }
    });
    $('#menu-icon').iconpicker({
        placement: "inline",
        templates: {
            popover: '<div class="iconpicker-popover popover"><div class="arrow"></div>' +
                '<div class="popover-title"></div><div class="popover-content"></div></div>',
            footer: '<div class="popover-footer"></div>',
            buttons: '<button class="iconpicker-btn iconpicker-btn-cancel btn btn-default btn-sm">Cancel</button>' +
                ' <button class="iconpicker-btn iconpicker-btn-accept btn btn-primary btn-sm">Accept</button>',
            search: '<input type="search" class="form-control iconpicker-search" placeholder="Type to filter" />',
            iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
            iconpickerItem: '<a role="button" href="#" class="iconpicker-item"><i></i></a>'
        }
    });
</script>