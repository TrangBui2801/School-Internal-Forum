<?php

use common\models\constants\TestAnswerConstant;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Question $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="question-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'content:ntext',
            'status',
            'threadId',
            'score',
            'levelId',
            'picked_count',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ],
    ]) ?>
    <div class="accordion" id="show-answers">
        <?php foreach ($model->getAnswers()->all() as $key => $answer) : ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="answer-<?= ($key + 1) ?>">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?= ($key + 1) ?>" aria-expanded="true" aria-controls="collapse-<?= ($key + 1) ?>">
                        Answer <?= ($key + 1) ?>
                    </button>
                </h2>
                <div id="collapse-<?= ($key + 1) ?>" class="accordion-collapse collapse" aria-labelledby="answer-<?= ($key + 1) ?>" data-bs-parent="#show-answers">
                    <div class="accordion-body">
                        <div class="card" style="padding: 10px;">
                            <h4>Answer <?= $key + 1 ?></h4>
                            <p class="answer-content">Content: <?= $answer->content ?></p>
                            <p class="answer-explanation">Explanation: <?= $answer->content ?></p>
                            <p class="answer-is_correct">Correct: <?= $answer->is_correct == TestAnswerConstant::ANSWER_CORRECT ? "True" : "False" ?></p>

                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>