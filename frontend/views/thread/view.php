<?php

use common\helpers\ImageUrlHelper;
use common\models\constants\FileTypeConstant;
use frontend\models\File;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var frontend\models\Thread $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Threads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<section class="section-sm border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="title-bordered mb-5 d-flex align-items-center">
                    <h1 class="h4"><?= $model->name ?></h1>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 mb-4 mb-md-0 text-center text-md-left">
                <img loading="lazy" class="rounded-lg img-fluid" src="<?= ImageUrlHelper::getImageUrl($model->image); ?>">
            </div>
            <div class="col-lg-9 col-md-8 text-center text-md-left">
                <div class="group_description">
                    <span><?= $model->description ?></span>
                </div>
                <div class="group_members">
                    <span>Members</span>
                    <div class="members">
                        <?php foreach ($model->getGroupMembers()->all() as $member) : ?>
                            <div class="group_member card" style="width: fit-content; padding: 5px; display: inline-block; margin: 5px;">
                                <span>
                                    <a href="<?= Url::to(['user/get-author', 'id' => $member->member->id, 'threadId' => $model->id]) ?>"><?= $member->member->full_name ?></a>
                                    <?php if (Yii::$app->user->identity->id == $model->moderatorId && Yii::$app->user->identity->id != $member->memberId) : ?>
                                        <a href="<?= Url::to(['thread/remove-user-from-group', 'groupId' => $model->id, 'memberId' => $member->memberId]) ?>" style="color: red"> x</a>
                                    <?php endif; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                        <?php if (Yii::$app->user->identity->id == $model->moderatorId) : ?>
                            <?php $form = ActiveForm::begin([
                                'action' => Url::to(['thread/add-users-to-group', 'id' => $model->id])
                            ]); ?>
                            <?=
                            $form->field($model, 'memberId')->widget(Select2::classname(), [
                                'data' => $userNotInGroup,
                                'maintainOrder' => true,
                                'toggleAllSettings' => [
                                    'selectLabel' => '<i class="fas fa-check-circle"></i> Tag All',
                                    'unselectLabel' => '<i class="fas fa-times-circle"></i> Untag All',
                                    'selectOptions' => ['class' => 'text-success'],
                                    'unselectOptions' => ['class' => 'text-danger'],
                                ],
                                'options' => ['placeholder' => 'Select users ...', 'multiple' => true],
                                'pluginOptions' => [
                                    'tags' => true,
                                    'maximumInputLength' => 10
                                ],
                            ])->label(false);
                            ?>
                            <button type="submit" class="btn btn-outline-primary">Add</button>
                            <?php ActiveForm::end(); ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<section class="section-sm">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="title-bordered mb-5 d-flex align-items-center">
                    <h1 class="h4">Post in this group</h1>
                </div>
            </div>
            <?php if ($posts) : ?>
                <?php foreach ($posts as $post) : ?>
                    <article class="row mb-5">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="post-slider slider-sm">
                                <?php
                                $cover = File::find()->where(['=', 'parentId', $post->id])->andWhere(['=', 'file_type', FileTypeConstant::POST_IMAGE_COVER])->one();
                                if ($cover) {
                                    $url = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://' . 'admin.ep.com' . substr($cover->url, strripos($cover->url, '/uploads'), strlen($cover->url));
                                    echo '<img loading="lazy" src="' . $url . '" class="img-fluid" alt="post-thumb" style="height:200px; object-fit: contain;">';
                                } else {
                                    echo '<img loading="lazy" src="../images/post/post-1.jpg" class="img-fluid" alt="post-thumb" style="height:200px; object-fit: contain;">';
                                    echo '<img loading="lazy" src="../images/post/post-2.jpg" class="img-fluid" alt="post-thumb" style="height:200px; object-fit: contain;">';
                                    echo '<img loading="lazy" src="../images/post/post-4.jpg" class="img-fluid" alt="post-thumb" style="height:200px; object-fit: contain;">';
                                }
                                ?>"
                                <!-- <img loading="lazy" src="../images/post/post-1.jpg" class="img-fluid" alt="post-thumb" style="height:200px; object-fit: contain;">
                                <img loading="lazy" src="../images/post/post-2.jpg" class="img-fluid" alt="post-thumb" style="height:200px; object-fit: contain;">
                                <img loading="lazy" src="../images/post/post-4.jpg" class="img-fluid" alt="post-thumb" style="height:200px; object-fit: contain;"> -->
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h3 class="h5"><a class="post-title" href="<?= Url::to(['post/view', 'id' => $post->id, 'threadId' => $model->id]) ?>"><?= $post->title ?></a></h3>
                            <ul class="list-inline post-meta mb-2">
                                <li class="list-inline-item"><i class="ti-user mr-2"></i><a href="<?= Url::to(['user/get-author', 'id' => $post->authorId, 'threadId' => $model->id]) ?>"><?= $post->getAuthor()->one()->full_name ?></a>
                                </li>
                                <li class="list-inline-item">Date : <?php
                                                                    $posted_at = strtotime($post->created_at);
                                                                    $date = date('Y-m-d', $posted_at);
                                                                    $time = date('H:m', $posted_at);
                                                                    echo "$date $time";
                                                                    ?></li>
                                <li class="list-inline-item">Thread : <a href="javascript:void(0)" data-pjax="0" class="ml-1"><?= $post->thread->name ?></a>
                                </li>
                            </ul>
                            <ul class="list-inline post-meta mb-2">
                                <li class="list-inline-item"><i class="ti-comments mr-2"></i><?= $post->reply_count ?></li>
                                <li class="list-inline-item"><i class="ti-heart mr-2"></i><?= $post->like_count ?></li>
                            </ul>
                            <p><?= $post->short_description ?></p> <a href="<?= Url::to(['post/view', 'id' => $post->id]) ?>" class="btn btn-outline-primary">Continue Reading</a>
                        </div>
                    </article>

                <?php endforeach; ?>
                <?= LinkPager::widget(['pagination' => $pages]); ?>
            <?php else : ?>
                <div class="col-lg-12 col-sm-12 mb-4 text-center">
                    No post available
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<div class="btn_cmt_action">
    <a href="<?= Url::to(['post/create', 'threadId' => $model->id]) ?>">
        <img src="../images/btnPost2.png" alt="" class="btn-jump" title="Post">
    </a>
</div>
<script>
    $(document).ready(function() {
        $('.select2-search--inline').attr('style', 'float: none !important;')
    });
</script>