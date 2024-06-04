<?php

use common\helpers\ImageUrlHelper;
use common\models\constants\FileTypeConstant;
use frontend\models\File;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var frontend\models\User $model */

$this->title = $author->full_name;
\yii\web\YiiAsset::register($this);
?>
<section class="section-sm border-bottom">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="title-bordered mb-5 d-flex align-items-center">
					<h1 class="h4"><?= $author->full_name ?></h1>
					<ul class="list-inline social-icons ml-auto mr-3 d-none d-sm-block">
						<li class="list-inline-item"><a href="<?= $author->facebook_link ?>" <?php if ($author->facebook_link == "") {
																									echo "class='disabled'";
																								} ?>><i class="ti-facebook"></i></a>
						</li>
						<li class="list-inline-item"><a href="<?= $author->skype_link ?>" <?php if ($author->skype_link == "") {
																								echo "class='disabled'";
																							} ?>><i class="ti-skype"></i></a>
						</li>
						<li class="list-inline-item"><a href="<?= $author->github_link ?>" <?php if ($author->github_link == "") {
																								echo "class='disabled'";
																							} ?>><i class="ti-github"></i></a>
						</li>
						<li class="list-inline-item"><a href="<?= $author->youtube_link ?>" <?php if ($author->youtube_link == "") {
																								echo "class='disabled'";
																							} ?>><i class="ti-youtube"></i></a>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 mb-4 mb-md-0 text-center text-md-left">
				<img loading="lazy" class="rounded-lg img-fluid" src="<?= ImageUrlHelper::getImageUrl($author->avatar); ?>">
			</div>
			<div class="col-lg-9 col-md-8 text-center text-md-left">
				<span><?= $author->introduction ?></span>
			</div>
		</div>
	</div>
</section>

<section class="section-sm">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="title-bordered mb-5 d-flex align-items-center">
					<h1 class="h4">Posted by this author</h1>
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
								?>
							</div>
						</div>
						<div class="col-md-8">
							<h3 class="h5"><a class="post-title" href="<?= Url::to(['post/view', 'id' => $post->id]) ?>"><?= $post->title ?></a></h3>
							<ul class="list-inline post-meta mb-2">
								<li class="list-inline-item"><i class="ti-user mr-2"></i><a href="javascript:void(0)"><?= $post->author->full_name ?></a>
								</li>
								<li class="list-inline-item">Date : <?php
																	$posted_at = strtotime($post->created_at);
																	$date = date('Y-m-d', $posted_at);
																	$time = date('H:m', $posted_at);
																	echo "$date $time";
																	?></li>
								<li class="list-inline-item">Thread : <a href="#!" class="ml-1"><?= $post->thread->name ?></a>
								</li>
								<li class="list-inline-item">Tags : <a href="#!" class="ml-1">Photo </a> ,<a href="#!" class="ml-1">Image </a>
								</li>
							</ul>
							<p><?= $post->short_description ?></p> <a href="<?= Url::to(['post/view', 'id' => $post->id]) ?>" class="btn btn-outline-primary">Continue Reading</a>
						</div>
					</article>
				<?php endforeach; ?>
				<?= LinkPager::widget(['pagination' => $pages]); ?>
			<?php else : ?>
				<div class="col-lg-4 col-sm-6 mb-4 text-center">
					No post available
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>