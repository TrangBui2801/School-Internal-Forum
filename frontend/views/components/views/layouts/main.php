<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\models\constants\StatusConstant;
use frontend\models\Survey;
use yii\helpers\Html;

\frontend\views\components\assets\FontAwesomeAsset::register($this);
\frontend\views\components\assets\AdminLteAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');

$assetDir = Yii::$app->assetManager->getPublishedUrl('@frontend/web/dist');

$publishedRes = Yii::$app->assetManager->publish('@frontend/views/components/web/js');

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" style="overflow-x: hidden;">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="stylesheet" href="../dist/js/plugins/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:600%7cOpen&#43;Sans&amp;display=swap" media="screen">
    <link rel="stylesheet" href="../dist/js/plugins/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="../dist/js/plugins/slick/slick.css">
    <link rel="stylesheet" href="../dist/css/style.css">
    <link rel="stylesheet" href="../dist/css/index.css">
    <link rel="stylesheet" href="../dist/css/base.css">
    <script src="../dist/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/platform/1.3.5/platform.min.js"></script>
</head>
<?= \diecoding\toastr\ToastrFlash::widget(); ?>
<?php
$survey = Survey::getPopupSurvey();
?>
<?php if ($survey) : ?>
    <?= $this->render('popup', ['assetDir' => $assetDir, 'survey' => $survey]) ?>
<?php endif; ?>

<body class="control-sidebar-slide-open layout-fixed layout-navbar-fixed preloading">
    <?php $this->beginBody() ?>
    <!-- *header -->
    <?= $this->render('navbar', ['assetDir' => $assetDir]) ?>
    <!-- *Modal profile-->
    <?= $this->render('profile-modal', ['assetDir' => $assetDir]) ?>

    <!-- *Modal update profile-->
    <?= $this->render('edit-profile-modal', ['assetDir' => $assetDir]) ?>
    <!-- *Announcements -->
    <?= $this->render('announcement', ['assetDir' => $assetDir]) ?>
    <!-- *Content -->
    <section class="content row content unselecttable" style="margin-left: 100px; margin-right: 50px;">
        <?= $this->render('navigation', ['assetDir' => $assetDir]) ?>
        <?= $this->render('sidebar', ['assetDir' => $assetDir]) ?>
        <?= $this->render('content', ['content' => $content, 'assetDir' => $assetDir]) ?>
    </section>
    <!-- *Footer -->
    <?= $this->render('footer', ['assetDir' => $assetDir]) ?>

    <?php $this->endBody() ?>
</body>
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"></script>
<script src="../dist/js/firebase.js"></script>
<script src="../dist/js/popper.min.js"></script>
<script src="../dist/js/plugins/bootstrap/bootstrap.min.js"></script>
<script src="../dist/js/plugins/slick/slick.min.js"></script>
<script src="../dist/js/script.js"></script>
<script src="../dist/js/base.js"></script>
</script>

</html>
<?php $this->endPage() ?>