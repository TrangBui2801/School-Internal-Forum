<?php

use common\helpers\DateTimeHelper;
use common\helpers\ImageUrlHelper;
use frontend\models\Topic;
use frontend\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var frontend\models\TopicSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Topics');
?>
<section class="section-sm border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="title-bordered mb-5 d-flex align-items-center">
                    <h1 class="h4"><?= $category->name ?></h1>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 mb-4 mb-md-0 text-center text-md-left">
                <img loading="lazy" class="rounded-lg img-fluid" src="<?= ImageUrlHelper::getImageUrl($category->image); ?>">
            </div>
            <div class="col-lg-9 col-md-8 text-center text-md-left">
                <h4>Category name: <?= $category->name ?></h4>
                <ul class="list-inline post-meta mb-2">
                    <li class="list-inline-item">Create at : <?= DateTimeHelper::getDateString($category->created_at); ?></li>
                    <li class="list-inline-item">Create by : <a href="<?= Url::to(['user/get-author', 'id' => $category->created_by]) ?>" class="ml-1"><?= User::find()->where(['=', 'id', $category->created_by])->one()->full_name; ?></a></li>
                </ul>
                <p><span style="font-weight: bold;">Description: </span><?= $category->description ?></p>
            </div>
        </div>
    </div>
</section>

<section class="section-sm">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="title-bordered mb-5 d-flex align-items-center">
                    <h1 class="h4">List of topics (<?= $count = count($category->topics); ?>)</h1>
                </div>
            </div>
            <?php if ($category->topics) : ?>
                <?php foreach ($category->topics as $topic) : ?>
                    <article class="row mb-5">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="post-slider slider-sm">
                                <img loading="lazy" src="<?= ImageUrlHelper::getImageUrl($topic->image) ?>" class="img-fluid" alt="post-thumb" style="height:200px; object-fit: contain;">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h3 class="h5"><a class="post-title" href="<?= Url::to(['thread/get-threads-by-topic', 'topicId' => $topic->id]) ?>"><?= $topic->name ?> (<?= count($topic->threads) ?>)</a></h3>
                            <ul class="list-inline post-meta mb-2">
                                <li class="list-inline-item"><i class="fas fa-calendar"></i><a href="javascript:void(0)"></a>
                                </li>
                                <li class="list-inline-item">Create at : <?= DateTimeHelper::getDateString($topic->created_at); ?></li>
                                </li>
                            </ul>
                            <p><?= $topic->short_description ?></p> <a href="<?= Url::to(['thread/get-threads-by-topic', 'topicId' => $topic->id]) ?>" class="btn btn-outline-primary">View topic</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col-lg-4 col-sm-6 mb-4 text-center">
                    No topic available
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>