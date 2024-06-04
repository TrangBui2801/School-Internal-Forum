<?php

use backend\models\Category;
use common\helpers\DateTimeHelper;
use common\helpers\ImageUrlHelper;
use common\models\constants\StatusConstant;
use frontend\controllers\NotificationController;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<!-- Navbar -->
<header class="header sticky-top" id="header">
    <ul class="header_top">
        <li class="nav-item header_top-order3">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block bg-color_orange">
                <form class="form-inline" action="<?= Url::to(['post/search']) ?>" method="GET">
                    <div class="input-group input-group-sm w-100">
                        <input class="form-control form-control-navbar main-search" name="search" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>
        <li class="nav-item dropdown header_top-order2">
            <?php $notifications = Yii::$app->user->identity->notifications; ?>
            <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)">
                <i class="far fa-bell"></i>
                <?php $notificationCount =  count($notifications); ?>
                <?php if ($notificationCount > 0) : ?>
                    <span class="badge badge-warning navbar-badge"><?= $notificationCount ?></span>
                <?php endif; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right" style="width: 420px !important; max-height: 420px;overflow-y: scroll; overflow-x: hidden;">
                <?php if ($notificationCount > 0) : ?>
                    <?php foreach ($notifications as $key => $notification) : ?>
                        <a href="<?= $notification->url . "&notificationId=" . $notification->id ?>" class="dropdown-item" class="show-notification" style="margin-left: 10px;">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="<?= ImageUrlHelper::getImageUrl($notification->actor->avatar) ?>" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        <?= $notification->actor->full_name ?>
                                    </h3>
                                    <p class="text-sm"><?= $notification->content ?></p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i><?= DateTimeHelper::getDateTimeString($notification->created_at) ?></p>
                                </div>
                                <?php if ($notification->isSeen == StatusConstant::NOTIFICAION_SEEN): ?>
                                    <i class="far fa-eye"></i>
                                <?php else: ?>
                                    <i class="far fa-eye-slash"></i>
                                <?php endif; ?>
                            </div>
                            <!-- Message End -->
                        </a>
                        <?php if (($key + 1) < $notificationCount): ?>
                            <div class="dropdown-divider"></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else : ?>
                        <!-- Message Start -->
                        <div class="media">
                            <div class="media-body text-center">
                                <p class="text-sm text-muted">No new notification</p>
                            </div>
                        </div>
                <?php endif; ?>
            </div>
        </li>
        <li class="header_top-user nav-link">
            <div class="dropdown show dropdown_color">
                <a class="btn btn-secondary btn-sm btn_user-color dropdown-toggle user_flex" href="javascript:void(0)" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="box-shadow: none;">
                    <div class="header_top-user-bg">
                        <img src="<?= ImageUrlHelper::getImageUrl(Yii::$app->user->identity->avatar) ?>" alt="" class="header_top-user-img">
                    </div>
                    <span class="header_top-user-name"><?= Yii::$app->user->identity->full_name ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-right edit_dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="<?= Url::to(['post/index']) ?>">
                        <span class="fas fa-home dropdown-menu-icon-list"></span>
                        Home
                    </a>
                    <a class="dropdown-item" href="javascript:void(0)">
                        <span class="fas fa-list-alt dropdown-menu-icon-list"></span>
                        Forum
                    </a>
                    <a class="dropdown-item" href="javascript:void(0)">
                        <span class="fas fa-book-open dropdown-menu-icon-list"></span>
                        Homework
                    </a>
                    <a class="dropdown-item" href="javascript:void(0)">
                        <span class="fas fa-calendar dropdown-menu-icon-list"></span>
                        Schedule
                    </a>
                    <a class="dropdown-item dropdown-item-profile" data-toggle="modal" data-target="#profile-modal" href="javascript:void(0)">
                        <span class="fas fa-user dropdown-menu-icon-list"></span>
                        Prolife
                    </a>
                    <a class="dropdown-item" href="javascript:void(0)">
                        <span class="fas fa-lock dropdown-menu-icon-list"></span>
                        Password
                    </a>
                    <a class="dropdown-item" href="javascript:void(0)">
                        <span class="fas fa-cog dropdown-menu-icon-list"></span>
                        My Posts
                    </a>
                    <a class="dropdown-item" href="<?= Url::to(['site/logout']) ?>" data-method="post">
                        <span class="fas fa-sign-out-alt dropdown-menu-icon-list"></span>
                        Log Out
                    </a>
                </ul>
            </div>
        </li>
    </ul>
    <nav class="header_bottom navbar navbar-expand-lg navbar-light nav">
        <div class="container-fluid">
            <a href="<?= Url::to(['post/']) ?>">
                <img src="<?= stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://' . 'admin.ep.com' ?>/dist/img/Logo-icon.png" alt="" class="header_bottom-logo">
            </a>
            <button class="navbar-toggler edit_navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
                <span class="edit_navbar-toggler-icon">
                    <i class="fas fa-stream"></i>
                </span>
            </button>
            <div class="collapse navbar-collapse header_bottom-drop-down" id="navbarResponsive">
                <ul class="header_bottom-main-nav navbar-nav ml-auto">
                    <li class="nav-item">
                        <a href="<?= Url::to(['post/index']) ?>" class="active">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= Url::to(['category/index']) ?>">Category (<?= count(Category::find()->where(['=', 'status', StatusConstant::ACTIVE])->all()) ?>)</a>
                    </li>
                    <li class="nav-item">
                        <a href="#">About us</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= Url::to(['test/create']) ?>">Test</a>
                    </li>
                    <li class="nav-item">
                        <a href="#">FAQ</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<!-- /.navbar -->