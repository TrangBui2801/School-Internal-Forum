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
                <h1>Welcome to the online test</h1>
                <ul>
                    <li>
                        <h4>
                            <span class="title-span-topic">Tester: </span>
                            <span class="title-span-rep"><?= $test->user->full_name ?></span>
                        </h4>
                    </li>
                    <li>
                        <h4>
                            <span class="title-span-topic">Thread: </span>
                            <span class="title-span-rep"><?= $test->thread->name ?></span>
                        </h4>
                    </li>
                    <li>
                        <h4>
                            <span class="title-span-topic">Level: </span>
                            <span class="title-span-rep"><?= $test->level->name ?></span>
                        </h4>
                    </li>
                    <li>
                        <h4>
                            <span class="title-span-topic">Number of questions: </span>
                            <span class="title-span-rep"><?= count($questions) ?></span>
                        </h4>
                    </li>
                    <li>
                        <h4>
                            <span class="title-span-topic">Time: </span>
                            <span class="title-span-rep" id="countdown"><?= sprintf('%02d:%02d', $test->level->time, 0) ?></span>
                        </h4>
                    </li>
                </ul>
                <p><a href="javascript:void(0)" id="start-test-container">Start</a></p>
            </div>
        </div>
    </div>
    <div class="test-container" id="test-container" <?php if ($isFinished) { echo "style='display: block;'"; } ?>>
        <div class="heading">
            <h1 class="heading__text">Test</h1>
        </div>

        <!-- Quiz section -->
        <div class="quiz">
            <div class="quiz__heading" id="quiz__heading" <?php if ($isFinished) { echo "style='display: block;'"; } ?>>
                <!-- <a href="#" onclick="close_quiz__heading()"><i class="fas fa-times"></i></a> -->
                <h2 class="quiz__heading-text">
                    Your score <span class="result"><?php if ($isFinished) { echo $test->score . "/" . $maxScore; } ?></span>.
                </h2>
            </div>
            <form class="quiz-form" id="quiz-form">
                <?php foreach ($questions as $key => $question) : ?>
                    <div class="quiz-form_explanation">
                        <?php if ($isFinished): ?>
                        <?php endif; ?>
                    </div>
                    <div class="quiz-form__quiz">
                        <p class="quiz-form__question">
                            <?= ($key + 1) . ". " . $question->content ?>
                        </p>
                        <?php foreach ($question->answers as $ansKey => $answer) : ?>
                            <label class="quiz-form__ans" for="<?= $answer->id ?>">
                                <input type="radio" class="answer_choice" name="q<?= $key + 1 ?>" id="<?= $answer->id ?>" value="<?= $question->id . "-" . $answer->id ?>" <?php if ($isFinished) { echo "disabled"; } ?> <?php if ($answer->isChosen) { echo "checked"; }?>/>
                                <span class="design"></span>
                                <span class="text"><?= $answer->content ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

                <input class="submit <?php if ($isFinished) { echo "disabled"; } ?>" type="submit" value="Submit" id="btn-testSubmit"/>
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

    // Test Form
    const form = document.querySelector(".quiz-form");
    const label = document.querySelectorAll(".quiz-form__ans");
    const check = form.querySelectorAll('.answer_choice');
    const result = document.querySelector(".quiz__heading");

    var seconds;
    var temp;
    var GivenTime = document.getElementById('countdown').innerHTML

    function countdown() {
        time = document.getElementById('countdown').innerHTML;
        timeArray = time.split(':')
        seconds = timeToSeconds(timeArray);
        if (seconds == '') {
            temp = document.getElementById('countdown');
            temp.innerHTML = GivenTime;
            time = document.getElementById('countdown').innerHTML;
            timeArray = time.split(':')
            seconds = timeToSeconds(timeArray);
        }
        seconds--;
        temp = document.getElementById('countdown');
        temp.innerHTML = secondsToTime(seconds);
        var timeoutMyOswego = setTimeout(countdown, 1000);
        if (secondsToTime(seconds) == '00:00') {
            clearTimeout(timeoutMyOswego); //stop timer
            console.log('Time"s UP')
        }
    }

    function timeToSeconds(timeArray) {
        var minutes = (timeArray[0] * 1);
        var seconds = (minutes * 60) + (timeArray[1] * 1);
        return seconds;
    }

    function secondsToTime(secs) {
        var hours = Math.floor(secs / (60 * 60));
        hours = hours < 10 ? '0' + hours : hours;
        var divisor_for_minutes = secs % (60 * 60);
        var minutes = Math.floor(divisor_for_minutes / 60);
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var divisor_for_seconds = divisor_for_minutes % 60;
        var seconds = Math.ceil(divisor_for_seconds);
        seconds = seconds < 10 ? '0' + seconds : seconds;

        return minutes + ':' + seconds;
        //hours + ':' + 

    }
    countdown();

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        let score = 0;
        console.log(check);

        let data = [];
        //check ans
        check.forEach((ans, i) => {
            if (ans.checked)
            {
                data.push(ans.value);
            }
        });

        result.style.display = "block";
        let output = 0;
        const timer = setInterval(() => {
            result.querySelector(".result").textContent = `${output}/<?= $maxScore ?>`;
            if (output === score) {
                clearInterval(timer);
            } else {
                output++;
            }
        }, 30);

        $.ajax({
            type: "POST",
            url: "answer",
            data: {
                userAns: data,
                testId: <?= $test->id  ?>
            },
            success: function(res) {
                result.style.display = "block";
                let output = 0;
                check.forEach((ans, i) => {
                    for (let i = 0; i < 4; i++) {
                        ans.disabled = true;
                    }
                });
                document.getElementById('btn-testSubmit').classList.add("disabled");
                const timer = setInterval(() => {
                    result.querySelector(".result").textContent = `${output}/<?= $maxScore ?>`;
                    if (output == res) {
                        clearInterval(timer);
                    } else {
                        output++;
                    }
                }, 30);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error: " + errorThrown);
            },
        });
    });
</script>
<script src="../dist/js/testdetail.js"></script>