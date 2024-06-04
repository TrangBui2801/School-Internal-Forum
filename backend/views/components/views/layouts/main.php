<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

\backend\views\components\assets\FontAwesomeAsset::register($this);
\backend\views\components\assets\AdminLteAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');

$assetDir = Yii::$app->assetManager->getPublishedUrl('@backend/web/dist');

$publishedRes = Yii::$app->assetManager->publish('@backend/views/components/web/js');
$this->registerJsFile($publishedRes[1] . '/control_sidebar.js', ['depends' => '\backend\views\components\assets\AdminLteAsset']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script src="..\plugins\jquery\jquery.min.js"></script>
    <?php $this->head() ?>
</head>

<body class="sidebar-mini control-sidebar-slide-open layout-fixed layout-navbar-fixed">
    <?php $this->beginBody() ?>

    <div class="wrapper">
        <?= \diecoding\toastr\ToastrFlash::widget(); ?>
        <!-- Navbar -->
        <?= $this->render('navbar', ['assetDir' => $assetDir]) ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <div class="app_content" style="margin: 10px">
            <?= $this->render('sidebar', ['assetDir' => $assetDir]) ?>

            <!-- Content Wrapper. Contains page content -->
            <?= $this->render('content', ['content' => $content, 'assetDir' => $assetDir]) ?>
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <?= $this->render('footer') ?>
    </div>

    <?php $this->endBody() ?>
    <?php $this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js'); ?>
</body>

</html>
<?php $this->endPage() ?>