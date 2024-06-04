<?php

use common\helpers\DateTimeHelper;
use common\helpers\ImageUrlHelper;
use common\models\constants\FileTypeConstant;
use frontend\models\File;
use frontend\models\Thread;
use frontend\models\User;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var frontend\models\ThreadSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Posts');
?>
<section class="section-sm border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="title-bordered mb-5 d-flex align-items-center">
                    <h1 class="h4"><?= $thread->name ?></h1>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 mb-4 mb-md-0 text-center text-md-left">
                <img loading="lazy" class="rounded-lg img-fluid" src="<?= ImageUrlHelper::getImageUrl($thread->image); ?>">
            </div>
            <div class="col-lg-9 col-md-8 text-center text-md-left">
                <h4>Topic name: <?= $thread->name ?></h4>
                <ul class="list-inline post-meta mb-2">
                    <li class="list-inline-item">Create at : <?= DateTimeHelper::getDateString($thread->created_at); ?></li>
                    <li class="list-inline-item">Create by : <a href="<?= Url::to(['user/get-author', 'id' => $thread->created_by]) ?>" class="ml-1"><?= User::find()->where(['=', 'id', $thread->created_by])->one()->full_name; ?></a></li>
                </ul>
                <p><span style="font-weight: bold;">Description: </span><?= $thread->description ?></p>
            </div>
        </div>
    </div>
</section>

<section class="section-sm">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="title-bordered mb-5 d-flex align-items-center">
                    <h1 class="h4">List of posts (<?= $count = count($posts); ?>)</h1>
                </div>
            </div>
            <?php Pjax::begin(); ?>
            <?php if ($posts && count($posts) > 0) : ?>
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
                                ?>
                                <!-- <img loading="lazy" src="../images/post/post-1.jpg" class="img-fluid" alt="post-thumb" style="height:200px; object-fit: contain;">
                            <img loading="lazy" src="../images/post/post-2.jpg" class="img-fluid" alt="post-thumb" style="height:200px; object-fit: contain;">
                            <img loading="lazy" src="../images/post/post-4.jpg" class="img-fluid" alt="post-thumb" style="height:200px; object-fit: contain;"> -->
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h3 class="h5"><a class="post-title" href="<?= Url::to(['post/view', 'id' => $post->id]) ?>"><?= $post->title ?></a></h3>
                            <ul class="list-inline post-meta mb-2">
                                <li class="list-inline-item"><i class="ti-user mr-2"></i><a href="<?= Url::to(['user/get-author', 'id' => $post->authorId]) ?>"><?= $post->getAuthor()->one()->full_name ?></a>
                                </li>
                                <li class="list-inline-item">Date : <?php
                                                                    $posted_at = strtotime($post->created_at);
                                                                    $date = date('Y-m-d', $posted_at);
                                                                    $time = date('H:m', $posted_at);
                                                                    echo "$date $time";
                                                                    ?></li>
                                <li class="list-inline-item">Thread : <a href="<?= Url::to(['get-posts-by-thread', 'threadId' => $post->thread->id]) ?>" data-pjax="0" class="ml-1"><?= $post->thread->name ?></a>
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
            <?php else : ?>
                <div class="text-center">
                    <span>No post found</span>
                </div>
            <?php endif; ?>
            <?php Pjax::end(); ?>
        </div>
        <?= LinkPager::widget(['pagination' => $pages]); ?>
        <div class="btn_cmt_action">
            <a href="<?= Url::to(['post/create']) ?>">
                <img src="../images/btnPost2.png" alt="" class="btn-jump" title="Post">
            </a>
        </div>
    </div>
</section>