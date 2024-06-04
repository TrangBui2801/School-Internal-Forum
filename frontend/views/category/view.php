<?php

use common\helpers\DateTimeHelper;
use common\helpers\ImageUrlHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var frontend\models\Category $model */

$this->title = $model->name;
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
				<span><?= $model->description ?></span>
			</div>
		</div>
	</div>
</section>

<section class="section-sm">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="title-bordered mb-5 d-flex align-items-center">
					<h1 class="h4">List of topic (<?= $count = count($model->topics); ?>)</h1>
				</div>
			</div>
			<?php if ($model->topics) : ?>
				<?php foreach ($model->topics as $topic) : ?>
					<article class="row mb-5">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="post-slider slider-sm">
                                <img loading="lazy" src="<?= ImageUrlHelper::getImageUrl($topic->image) ?>" class="img-fluid" alt="post-thumb" style="height:200px; object-fit: contain;">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h3 class="h5"><a class="post-title" href="<?= Url::to(['thread/get-threads-by-topic', 'topicId' => $topic->id]) ?>"><?= $topic->name ?></a></h3>
                            <ul class="list-inline post-meta mb-2">
                                <!-- <li class="list-inline-item"><i class="fas fa-comment"></i><a href="javascript:void(0)"></a>
                                </li>
                                <li class="list-inline-item">Date : <?php
                                // DateTimeHelper::getDateString($topic->created_at);
                                                                    ?></li>
                                <li class="list-inline-item">Thread : <a href="#!" class="ml-1"></a>
                                </li>
                                <li class="list-inline-item">Tags : <a href="#!" class="ml-1">Photo </a> ,<a href="#!" class="ml-1">Image </a>
                                </li> -->
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
