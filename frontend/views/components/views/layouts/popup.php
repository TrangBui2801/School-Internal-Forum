<?php

use yii\helpers\Url;
?>
<div class="popup-wrap">
    <div class="popup">
        <div class="popup-timer"><span class="seconds"></span> second(s) left</div>
        <div class="btn-close">x</div>
        <div class="popup-content">
            <div class="popup-text">
                <p class="message">
                    You have a survey:
                </p>
                <p class="survey-title"><?= $survey->survey->title ?></p>

            </div>
            <div class="popup-btn mb-10 mt-10">
                <a href="<?= Url::to(['survey/take-survey', 'id' => $survey->id, 'surveyId' => $survey->surveyId]) ?>" class="btn btn-outline-primary">Take</a>
            </div>
            <div class="popup-footer">
                <input type="checkbox" id="not-remind-btn" class="popup-footer-btn" style="display:inline;" data="<?= $survey->id ?>">
                <label for="not-remind-btn" class="popup-footer-btn" style="display:inline;">Do not remind me again</label>
            </div>
        </div>
    </div>
</div>