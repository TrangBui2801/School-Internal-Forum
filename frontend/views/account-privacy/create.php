<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\AccountPrivacy $model */

$this->title = Yii::t('app', 'Create Account Privacy');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Account Privacies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-privacy-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
