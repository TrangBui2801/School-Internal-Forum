<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\BadWord $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="bad-word-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-6"><input type="text" id="add-bad-word" style="width: 50%;" class="form-control" placeholder="Type and enter to add" /></div>
        <div class="col-lg-6 col-sm-12 col-md-6"><input type="search" id="search-bad-word" style="width: 50%; float: right;" class="form-control" placeholder="Type to search" /></div>
    </div>

    <div class="row" style="padding: 10px;">
        <div id="bad-word-list" class="col-lg-12 col-sm-12 col-md-12 bad-words card" style="display: inline; padding-top: 5px; padding-bottom: 5px; height: 100px; overflow-y: scroll;">
            <?php $badWords = explode(', ', $model->value); ?>
            <?php foreach ($badWords as $badWord) : ?>
                <div class="bad-word-card card" data="<?= $badWord ?>" style="width: fit-content; padding: 5px; display: inline-block; margin: 5px;">
                    <span>
                        <span><?= $badWord ?></span>
                        <a class="remove-badword" data="<?= $badWord ?>" href="javascript:void(0);">&nbsp;x</a>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?= $form->field($model, 'value')->textarea(['rows' => 5, 'hidden' => true])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $(document).ready(function() {
        let badWordsString = $('#badword-value').val();
        let badWordArray = badWordsString.split(', ');
        $(window).keydown(function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
        $('#add-bad-word').on('keydown', function(e) {
            if (e.keyCode == 13) {
                let badWord = $(this).val().trim();
                if (badWord != "") {
                    let isExist = false;
                    $.each(badWordArray, function(index, value) {
                        if (badWord == value) {
                            isExist = true;
                            return true;
                        }
                    })
                    if (!isExist) {
                        if (badWordsString != "") {
                            badWordsString += ", ";
                        }
                        badWordsString += badWord;
                        badWordArray.push(badWord);
                        $('#badword-value').val(badWordsString);
                        let cardData = '<div class="bad-word card" style="width: fit-content; padding: 5px; display: inline-block; margin: 5px;">';
                        cardData += '<span><span>' + badWord + '</span><a class="remove-badword" data=' + badWord + ' href="javascript:void(0);">&nbsp;x</a></span>';
                        cardData += '</div>';
                        $('#bad-word-list').append(cardData);
                    }
                }
                $(this).val('');
            }
        });
        $('#search-bad-word').on('keyup', function() {
            let searchData = $(this).val();
            if (searchData == "") {
                console.log(badWordsString);
                $('#badword-value').val(badWordsString);
            } else {
                let searchBadWordData = "";
                let dataIndex = 0;
                $.each(badWordArray, function(index, value) {
                    if (value.indexOf(searchData) != -1) {
                        if (dataIndex != 0) {
                            searchBadWordData += ", ";
                        }
                        searchBadWordData += value;
                        dataIndex++;
                    }
                });
                $('#badword-value').val(searchBadWordData);
            }
        });
        $('.remove-badword').on('click', function() {
            let data = $(this).attr('data');
            if (data != "") {
                badWordArray = $.grep(badWordArray, function(value) {
                    return value != data;
                });
                let dataIndex = 0;
                badWordsString = "";
                $.each(badWordArray, function(index, value) {
                    if (dataIndex != 0) {
                        badWordsString += ", ";
                    }
                    badWordsString += value;
                    dataIndex++;
                });
                $('#badword-value').val(badWordsString);
                $(this).closest('.bad-word-card').remove();
            }
        });
    });
</script>