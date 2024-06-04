<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\FirebaseToken $model */

$this->title = Yii::t('app', 'Create Firebase Token');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Firebase Tokens'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firebase-token-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
