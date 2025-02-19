<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\ConfigType $model */

$this->title = Yii::t('app', 'Create Config Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Config Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
