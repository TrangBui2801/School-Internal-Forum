<?php

use common\models\constants\PostFilterTypeConstant;
use yii\helpers\Url;

?>
<!-- navigation -->
<nav class="navigation">
    <!-- *nav test -->
    <div class="main-menu-nav">
        <section class="nav-links">
            <ul>
                <li class="menu-item menu-item-nav">
                    <?php $id = Yii::$app->getRequest()->getQueryParam('threadId') ?>
                    <?php if ($id) : ?>
                        <a href="<?= Url::to(['post/get-posts', 'filterType' => PostFilterTypeConstant::LASTEST_POST_FILTER, 'threadId' => $id]) ?>"><i class="fas fa-paint-brush"></i><span>Lastest posts</span></a>
                    <?php else : ?>
                        <a href="<?= Url::to(['post/get-posts', 'filterType' => PostFilterTypeConstant::LASTEST_POST_FILTER]) ?>"><i class="fas fa-paint-brush"></i><span>Lastest posts</span></a>
                    <?php endif; ?>
                </li>
                <li class="menu-item menu-item-nav">
                    <?php if (Yii::$app->controller->id === 'thread' && Yii::$app->controller->action->id === 'view-group') : ?>
                        <a href="<?= Url::to(['post/get-posts', 'filterType' => PostFilterTypeConstant::LASTEST_ACTIVITY_FILTER, 'threadId' => $id]) ?>"><i class="fas fa-comment"></i><span>Last activity</span></a>
                    <?php else : ?>
                        <a href="<?= Url::to(['post/get-posts', 'filterType' => PostFilterTypeConstant::LASTEST_ACTIVITY_FILTER]) ?>"><i class="fas fa-comment"></i><span>Last activity</span></a>
                    <?php endif; ?>
                </li>
                <li class="menu-item menu-item-nav">
                    <?php if (Yii::$app->controller->id === 'thread' && Yii::$app->controller->action->id === 'view-group') : ?>
                        <a href="<?= Url::to(['post/get-posts', 'filterType' => PostFilterTypeConstant::MOST_VIEW_POST_FILTER, 'threadId' => $id]) ?>"><i class="fas fa-comment"></i><span>Most view</span></a>
                    <?php else : ?>
                        <a href="<?= Url::to(['post/get-posts', 'filterType' => PostFilterTypeConstant::MOST_VIEW_POST_FILTER]) ?>"><i class="fas fa-eye"></i><span>Most view</span></a>
                    <?php endif; ?>
                </li>
                <li class="menu-item menu-item-nav">
                    <?php if (Yii::$app->controller->id === 'thread' && Yii::$app->controller->action->id === 'view-group') : ?>
                        <a href="<?= Url::to(['post/get-posts', 'filterType' => PostFilterTypeConstant::MOST_COMMENT_POST_FILTER, 'threadId' => $id]) ?>"><i class="fas fa-comment-dots"></i><span>Most comment</span></a>
                    <?php else : ?>
                        <a href="<?= Url::to(['post/get-posts', 'filterType' => PostFilterTypeConstant::MOST_COMMENT_POST_FILTER]) ?>"><i class="fas fa-comment-dots"></i><span>Most comment</span></a>
                    <?php endif; ?>
                </li>
                <li class="menu-item menu-item-nav">
                    <?php if (Yii::$app->controller->id === 'thread' && Yii::$app->controller->action->id === 'view-group') : ?>
                        <a href="<?= Url::to(['post/get-posts', 'filterType' => PostFilterTypeConstant::MOST_LIKE_POST_FILTER, 'threadId' => $id]) ?>"><i class="fas fa-thumbs-up"></i><span>Most like</span></a>
                    <?php else : ?>
                        <a href="<?= Url::to(['post/get-posts', 'filterType' => PostFilterTypeConstant::MOST_LIKE_POST_FILTER]) ?>"><i class="fas fa-thumbs-up"></i><span>Most like</span></a>
                    <?php endif; ?>
                </li>
            </ul>
        </section>
    </div>
</nav>
<!-- end navigation -->