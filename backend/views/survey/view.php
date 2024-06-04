<?php

use backend\models\Question;
use backend\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use scotthuangzl\googlechart\GoogleChart;

/** @var yii\web\View $this */
/** @var backend\models\Survey $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Surveys'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="survey-view">
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
  <div class="card mt-10">
    <div class="card-header">
      <h3>Survey: <?= $model->id ?></h3>
    </div>
    <div class="card-body">
      <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
          'title',
          'start_date',
          'end_date',
          'created_at',
          [
            'label' => Yii::t('app', 'Create by'),
            'value' => User::find()->where(['=', 'id', $model->created_by])->one()->full_name
          ],
          'updated_at',
          [
            'label' => Yii::t('app', 'Update by'),
            'value' => $model->updated_at ? User::find()->where(['=', 'id', $model->updated_by])->one()->full_name : ""
          ],
          [
            'label' => 'newColumn',
            'value' => function($model) {
                $current = date_create(date('Y-m-d'));
                $endDate = date_create(date('Y-m-d', strtotime($model->end_date)));
                return $current->diff($endDate)->format('%d days');
            }
        ],
        ],
      ]) ?>
    </div>
  </div>
  <div class="card mt-10">
    <div class="card-header">
      <h3>Survey result:</h3>
    </div>
    <div class="card-body row">
      <?php if ($questions) : ?>
        <?php foreach ($questions as $key => $question) : ?>
          <div class="col col-lg-12 col-md-12 col-sm-12 margin-5 border-radius-5">
            <div class="card margin-5 border-radius-5">
              <?php $data = $question->getChartDataOfSurvey($question->id);
              $chart_data = array();
              array_push($chart_data, array('Question ' . ($key + 1), 'Number of answers'));
              for ($i = 0; $i < (count($data)); $i++) {
                array_push($chart_data, array($data[$i]['key'], $data[$i]['value']));
              }
              ?>
              <?= GoogleChart::widget(array(
                'visualization' => 'PieChart',
                'data' => $chart_data,
                'options' => array('title' => 'Question ' . ($key + 1))
              ));
              ?>
              <div class="accordion mb-10" id="show-questions-<?= ($key + 1) ?>">
                <div class="accordion-item">
                  <h2 class="accordion-header" id="question<?= $key + 1 ?>">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $key + 1 ?>" aria-expanded="true" aria-controls="collapse<?= $key + 1 ?>">
                      Question <?= $key + 1 ?>
                    </button>
                  </h2>
                  <div id="collapse<?= $key + 1 ?>" class="accordion-collapse collapse" aria-labelledby="question<?= $key + 1 ?>" data-bs-parent="#show-questions-<?= ($key + 1) ?>">
                    <div class="accordion-body">
                      <?php if ($question->answers) : ?>
                        <?php foreach ($question->answers as $ansKey => $answer) : ?>
                          <div class="card" style="width: 100%; padding: 10px;">
                            <p>Answer <?= $ansKey + 1 ?>: <?= $answer->content ?></p>
                          </div>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>