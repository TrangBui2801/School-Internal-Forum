<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Topic $model */

$this->title = Yii::t('app', 'Create Topic');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Topics'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topic-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
