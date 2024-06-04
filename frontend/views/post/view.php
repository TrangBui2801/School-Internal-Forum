<?php

use common\helpers\DateTimeHelper;
use common\helpers\ImageUrlHelper;
use common\models\constants\FileTypeConstant;
use frontend\models\Reaction;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var frontend\models\Post $model */

$this->title = $model->title;
\yii\web\YiiAsset::register($this);
?>
<link rel="stylesheet" href="../dist/css/postdetail.css">
<?php $threadId = Yii::$app->getRequest()->getQueryParam('threadId'); ?>
<div class="post-view">

    <div class="post-container">
        <!-- Post Header -->
        <div class="post__header container">
            <div class="post__header-title">
                <h4>
                    <?= $model->title ?>
                </h4>
            </div>
            <div class="post__header-action">
                <div class="btn-group">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn-jump btn-post__header-action dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="sr-only">Toggle Dropleft</span>
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu edit-dropdown-menu">
                            <?php if ($model->authorId == Yii::$app->user->identity->id) : ?>
                                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'dropdown-item edit-dropdown-item']) ?>
                                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                                    'class' => 'dropdown-item edit-dropdown-item',
                                    'data' => [
                                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            <?php else : ?>
                                <a class="dropdown-item edit-dropdown-item" href="#">Report</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Post Body -->
        <div class="post__body container">
            <div class="post__body-author">
                <div class="post__body-author-bg-img">
                    <a data-toggle="modal" data-target="#profile-modal">
                        <img src="<?= ImageUrlHelper::getImageUrl($model->author->avatar); ?>" alt="">
                    </a>
                </div>
                <ul class="post__body-author-text">
                    <li>
                        <a href="<?php
                                    if ($threadId) {
                                        echo Url::to(['user/get-author', 'id' => $model->authorId, 'threadId' => $threadId]);
                                    } else {
                                        echo Url::to(['user/get-author', 'id' => $model->authorId]);
                                    }
                                    ?>"><span class="post__body-author-name"><?= $model->author->full_name ?></span></a>
                    </li>
                    <li>
                        <span class="post__body-author-time">
                            Posted on&nbsp;<span>
                                <?php
                                $posted_at = strtotime($model->created_at);
                                $date = date('Y-m-d', $posted_at);
                                $time = date('H:m', $posted_at);
                                echo "$date $time";
                                ?>
                            </span>
                        </span>
                    </li>
                </ul>
            </div>
            <div class="post__body-content">
                <?= $model->content ?>
            </div>
            <?php if ($model->files) : ?>
                <div class="line-divider"></div>
                <div class="post__body-attachments">
                    <p class="mb-0">Attachments:</p>
                    <div class="download-attachment" style="margin-left: 15px;">
                        <?php foreach($model->files as $attachment): ?>
                            <?php if ($attachment->file_type == FileTypeConstant::POST_ATTACHMENT_FILE): ?>
                                <div class="w-100"><a target="_blank" href="<?= ImageUrlHelper::getImageUrl($attachment->url); ?>"><?= $attachment->original_name ?></a></div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif ?>
            <div class="post__body-action unselecttable">
                <div class="post__body-action-like">
                    <button type="button" class="btn-like btn-jump 
                    <?php
                    $reaction = Reaction::find()->where(['userId' => Yii::$app->user->identity->id])->andWhere(['postId' => $model->id])->one();
                    if ($reaction) {
                        echo "liked";
                    } ?>" data="<?= $model->id ?>"><i class="far fa-thumbs-up"></i>
                        <span>Like</span>
                        <span id="like_count_<?= $model->id ?>"><?= $model->like_count ?></span>
                    </button>
                </div>
                <div class="post__body-action-comment">
                    <button type="button" class="btn-cmt btn-jump">
                        <i class="far fa-comment-dots"></i>
                        <span>Comment</span>
                        <span><?= $model->reply_count ?></span>
                    </button>
                </div>
                <div class="post__body-action-share">
                    <button type="button" class="btn-jump">
                        <i class="far fa-share-square"></i>
                        <span>Share</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Post Footer -->
        <div class="post__footer container" id="post__footer">
            <!-- form post comment -->
            <div class="post__footer-post-comment">
                <div class="cmt__action-container">
                    <div class="cmt__action-container-avatar">
                        <img class="cmt__action-avatar" src="<?= ImageUrlHelper::getImageUrl(Yii::$app->user->identity->avatar); ?>" alt="">
                    </div>
                    <div class="cmt__action-post-area">
                        <?php $form = ActiveForm::begin([
                            'action' => Url::to(['post/comment', 'postId' => $model->id, 'threadId' => $threadId]),
                            'method' => 'POST'
                        ]); ?>
                        <?= $form->field($new_comment, 'parentId')->hiddenInput([
                            'value' => $model->id
                        ])->label(false); ?>
                        <?= $form->field($new_comment, 'level')->hiddenInput([
                            'value' => 1
                        ])->label(false); ?>
                        <div class="cmt__action-post-area-header">
                            <?= $form->field($new_comment, 'content')->textarea([
                                'class' => 'form-control auto-textarea edit-txta',
                                'placeholder' => 'Write your comment here'
                            ])->label(false); ?>
                        </div>
                        <div class=" cmt__action-post-area-bottom">
                            <?= Html::submitButton(Yii::t('app', 'Post'), ['class' => 'btn-post-cmt btn-jump']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
            <!-- form show comment -->
            <?php if ($comments) : ?>
                <div class="post__footer-comment">
                    <!-- form show comment comtainer -->
                    <div class="comments-container">
                        <?php foreach ($comments as $comment) : ?>
                            <ul id="comments-list" class="comments-list">
                                <!-- cmt 1 and rep cmt 1 -->
                                <li>
                                    <!-- comment 1 -->
                                    <div class="comment-main-level">
                                        <!-- Avatar -->
                                        <div class="comment-avatar">
                                            <img src="<?= ImageUrlHelper::getImageUrl($comment->author->avatar); ?>" alt="">
                                        </div>
                                        <!-- Contenedor del Comentario -->
                                        <div class="comment-box">
                                            <div class="comment-head">
                                                <h6 class="comment-name by-author">
                                                    <a href="<?= "#" ?>"><?= $comment->author->full_name ?></a>
                                                </h6>
                                                <span class="posted-time">Posted on <?php
                                                                                    $posted_at = strtotime($comment->created_at);
                                                                                    $date = date('Y-m-d', $posted_at);
                                                                                    $time = date('H:m', $posted_at);
                                                                                    echo "$date $time";
                                                                                    ?>
                                                </span>
                                                <div class="comment-head-more-action">
                                                    <button type="button" class="btn-jump btn-post__header-action dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="sr-only">Toggle Dropleft</span>
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu edit-dropdown-menu">
                                                        <?php if ($model->authorId == Yii::$app->user->identity->id) : ?>
                                                            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $comment->id], ['class' => 'dropdown-item edit-dropdown-item']) ?>
                                                            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $comment->id, 'parentId' => $model->id, 'threadId' => $threadId], [
                                                                'class' => 'dropdown-item edit-dropdown-item',
                                                                'data' => [
                                                                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                                    'method' => 'post',
                                                                ],
                                                            ]) ?>
                                                        <?php else : ?>
                                                            <a class="dropdown-item edit-dropdown-item" href="#">Report</a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="comment-content">
                                                <?= $comment->content ?>
                                                <div class="comment-open">
                                                    <a class="btn-like btn-like-ml <?php
                                                                                    $reaction = Reaction::find()->where(['userId' => Yii::$app->user->identity->id])->andWhere(['postId' => $comment->id])->one();
                                                                                    if ($reaction) {
                                                                                        echo "liked";
                                                                                    } ?>" style="color: #B2BEC3;" data="<?= $comment->id ?>">
                                                        <i class="far fa-thumbs-up"></i>
                                                        <span id="like_count_<?= $comment->id ?>"><?= $comment->like_count ?></span>
                                                    </a>
                                                    <a class="btn-reply">
                                                        <i class="fa fa-reply"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="comment-footer">
                                                <div class="comment-form">
                                                    <?php $form = ActiveForm::begin([
                                                        'action' => Url::to(['post/comment', 'postId' => $model->id, 'threadId' => $threadId]),
                                                        'method' => 'POST'
                                                    ]); ?>
                                                    <?= $form->field($new_comment, 'parentId')->hiddenInput([
                                                        'value' => $comment->id
                                                    ])->label(false); ?>
                                                    <?= $form->field($new_comment, 'level')->hiddenInput([
                                                        'value' => 2
                                                    ])->label(false); ?>
                                                    <div class="cmt__action-post-area-header">
                                                        <?= $form->field($new_comment, 'content')->textarea([
                                                            'class' => 'form-control auto-textarea',
                                                            'placeholder' => 'Write your comment here'
                                                        ])->label(false); ?>
                                                    </div>
                                                    <div class="pull-right send-button">
                                                        <?= Html::submitButton(Yii::t('app', 'Post'), ['class' => 'btn-send btn-jump']) ?>
                                                    </div>
                                                    <?php ActiveForm::end(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($comment->subcomments) : ?>
                                        <!-- rep comment 1 -->
                                        <ul class="comments-list reply-list" style="list-style-type: none !important;">
                                            <?php foreach ($comment->subcomments as $subcomment) : ?>
                                                <li>
                                                    <div class="comment-avatar">
                                                        <img src="<?= ImageUrlHelper::getImageUrl($subcomment->author->avatar); ?>" alt="">
                                                    </div>
                                                    <div class="comment-box">
                                                        <div class="comment-head">
                                                            <h6 class="comment-name">
                                                                <a href="#"><?= $subcomment->author->full_name ?></a>
                                                            </h6>
                                                            <span class="posted-time">Posted on <?php
                                                                                                $posted_at = strtotime($subcomment->created_at);
                                                                                                $date = date('Y-m-d', $posted_at);
                                                                                                $time = date('H:m', $posted_at);
                                                                                                echo "$date $time";
                                                                                                ?></span>
                                                            <div class="comment-head-more-action">
                                                                <button type="button" class="btn-jump btn-post__header-action dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <span class="sr-only">Toggle Dropleft</span>
                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                </button>
                                                                <div class="dropdown-menu edit-dropdown-menu">
                                                                    <?php if ($model->authorId == Yii::$app->user->identity->id) : ?>
                                                                        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $subcomment->id], ['class' => 'dropdown-item edit-dropdown-item']) ?>
                                                                        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $subcomment->id, 'parentId' => $model->id, 'threadId' => $threadId], [
                                                                            'class' => 'dropdown-item edit-dropdown-item',
                                                                            'data' => [
                                                                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                                                                'method' => 'post',
                                                                            ],
                                                                        ]) ?>
                                                                    <?php else : ?>
                                                                        <a class="dropdown-item edit-dropdown-item" href="#">Report</a>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="comment-content">
                                                            <?= $subcomment->content ?>
                                                            <div class="comment-open">
                                                                <a class="btn-like btn-like-ml <?php
                                                                                                $reaction = Reaction::find()->where(['userId' => Yii::$app->user->identity->id])->andWhere(['postId' => $subcomment->id])->one();
                                                                                                if ($reaction) {
                                                                                                    echo "liked";
                                                                                                } ?>" style="color: #B2BEC3;" data="<?= $subcomment->id ?>">
                                                                    <i class="far fa-thumbs-up"></i>
                                                                    <span id="like_count_<?= $subcomment->id ?>"><?= $subcomment->like_count ?></span>
                                                                </a>
                                                                <a class="btn-reply">
                                                                    <i class="fa fa-reply"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="comment-footer">
                                                            <div class="comment-form">
                                                                <?php $form = ActiveForm::begin([
                                                                    'action' => Url::to(['post/comment', 'postId' => $model->id, 'threadId' => $threadId]),
                                                                    'method' => 'POST'
                                                                ]); ?>
                                                                <?= $form->field($new_comment, 'parentId')->hiddenInput([
                                                                    'value' => $comment->id
                                                                ])->label(false); ?>
                                                                <?= $form->field($new_comment, 'level')->hiddenInput([
                                                                    'value' => 2
                                                                ])->label(false); ?>
                                                                <div class="cmt__action-post-area-header">
                                                                    <?= $form->field($new_comment, 'content')->textarea([
                                                                        'class' => 'form-control auto-textarea',
                                                                        'placeholder' => 'Write your comment here'
                                                                    ])->label(false); ?>
                                                                </div>
                                                                <div class="pull-right send-button">
                                                                    <?= Html::submitButton(Yii::t('app', 'Post'), ['class' => 'btn-send btn-jump']) ?>
                                                                </div>
                                                                <?php ActiveForm::end(); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            </ul>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <!-- scroll to post comment -->
            <div class="post__footer-write-cmt">
                <a href="#" class="write_cmt unselecttable">Write your comment</a>
            </div>
        </div>
    </div>

</div>
<script src="../dist/js/postdetail.js"></script>