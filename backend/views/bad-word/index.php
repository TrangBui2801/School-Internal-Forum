<?php

use backend\models\BadWord;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var backend\models\BadWordSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Bad Words');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bad-word-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!$isConfigExist): ?>
            <?= Html::a(Yii::t('app', 'Create Bad Word'), ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'config_typeId',
            'value:ntext',
            'status',
            'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, BadWord $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
