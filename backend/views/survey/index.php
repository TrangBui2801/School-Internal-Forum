<?php

use backend\models\Survey;
use backend\models\Test;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var backend\models\SurveySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Surveys');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="survey-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Survey'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'class' => LinkPager::class,
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'start_date',
            'end_date',
            'status',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            [
                'label' => 'newColumn',
                'attribute'=> 'newColumn',
                'value' => function($model) {
                    $current = date_create(date('Y-m-d'));
                    $endDate = date_create(date('Y-m-d', strtotime($model->end_date)));
                    return $current->diff($endDate)->format('%d days');
                }
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Test $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
