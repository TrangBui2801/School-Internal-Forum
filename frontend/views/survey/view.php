<?php

use frontend\models\Test;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var frontend\models\TestSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Tests');
$this->params['breadcrumbs'][] = $this->title;
?>

<link rel="stylesheet" href="../dist/css/testdetail.css">
<div class="test-index">

    <?php Pjax::begin(); ?>

    <div class="content_test">
        <div class="start-test">
            <img src="/resources/img/test_img.png" alt="">
            <div class="start-test-title">
                <h1>School survey</h1>
                <h4><?= $survey->title ?></h4>
                <p><a href="javascript:void(0)" id="start-test-container">Start</a></p>
            </div>
        </div>
    </div>
    <div class="test-container" id="test-container">
        <!-- Quiz section -->
        <div class="quiz">
            <div class="quiz__heading" id="quiz__heading">
                <!-- <a href="#" onclick="close_quiz__heading()"><i class="fas fa-times"></i></a> -->
                <h2 class="quiz__heading-text">
                    Your score <span class="result"></span>.
                </h2>
            </div>
            <form class="quiz-form" id="quiz-form" method="POST" action="<?= Url::to(['survey/take-survey', 'id' => $survey->id, 'surveyId' => $surveyId]) ?>">
            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()); ?>
                <?php foreach ($questions as $key => $question) : ?>
                    <div class="quiz-form__quiz">
                        <p class="quiz-form__question">
                            <?= ($key + 1) . ". " . $question->content ?>
                        </p>
                        <?php foreach ($question->answers as $ansKey => $answer) : ?>
                            <label class="quiz-form__ans" for="<?= $answer->id ?>">
                                <input type="radio" class="answer_choice" name="q<?= $key + 1 ?>" id="<?= $answer->id ?>" value="<?= $question->id . "-" . $answer->id ?>" <?php if(in_array($answer->id, $checked_answers)) { echo "checked='true'"; } ?>/>
                                <span class="design"></span>
                                <span class="text"><?= $answer->content ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

                <input class="submit" type="submit" value="Submit" id="btn-testSubmit" />
            </form>
        </div>
    </div>

    <?php Pjax::end(); ?>

</div>

<script>
    $(document).ready(function() {
        $("#start-test-container").click(function() {
            $(".test-container").fadeIn(1000);
            $("#start-test-container").fadeOut(500);
        });
        $("#btn-testSubmit").on("click", function() {
            $("html, body").animate({
                    scrollTop: $("#test-container").offset().top - 100
                },
                350
            );
        });
    });
</script>
<script src="../dist/js/testdetail.js"></script>