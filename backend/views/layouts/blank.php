<?php

/** @var yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="icon" type="image/png" href="../dist/img/icons/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="../dist/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../dist/vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="../dist/vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="../dist/vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../dist/css/util.css">
    <link rel="stylesheet" type="text/css" href="../dist/css/main.css">
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <main role="main">
        <div class="container">
            <?= $content ?>
        </div>
    </main>

    <?php $this->endBody() ?>
    <script src="../dist/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="../dist/vendor/bootstrap/js/popper.js"></script>
    <script src="../dist/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../dist/vendor/select2/select2.min.js"></script>
    <script src="../dist/vendor/tilt/tilt.jquery.min.js"></script>
    <script>
        $('.js-tilt').tilt({
            scale: 1.1
        })
    </script>
    <script src="js/main.js"></script>
</body>

</html>