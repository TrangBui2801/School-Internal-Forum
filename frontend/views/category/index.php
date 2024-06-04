<?php

use common\helpers\DateTimeHelper;
use common\helpers\ImageUrlHelper;
use frontend\models\Category;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var frontend\models\CategorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Categories');
?>
<section class="section-sm">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="title-bordered mb-5 d-flex align-items-center">
					<h1 class="h4">List of categories (<?= $count = count($categories); ?>)</h1>
				</div>
			</div>
			<?php if ($categories && count($categories) > 0) : ?>
				<?php foreach ($categories as $category) : ?>
					<article class="row mb-5">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="post-slider slider-sm">
                                <img loading="lazy" src="<?= ImageUrlHelper::getImageUrl($category->image) ?>" class="img-fluid" alt="post-thumb" style="height:200px; object-fit: contain;">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h3 class="h5"><a class="post-title" href="<?= Url::to(['topic/get-topics-by-category', 'categoryId' => $category->id]) ?>"><?= $category->name ?> (<?= count($category->topics) ?>)</a></h3>
                            <ul class="list-inline post-meta mb-2">
                                <li class="list-inline-item"><i class="fas fa-calendar"></i><a href="javascript:void(0)"></a>
                                </li>
                                <li class="list-inline-item">Create at : <?= DateTimeHelper::getDateString($category->created_at); ?></li>
                                </li>
                            </ul>
                            <p style="bottom: 0;"><?= $category->short_description ?></p> <a href="<?= Url::to(['topic/get-topics-by-category', 'categoryId' => $category->id]) ?>" class="btn btn-outline-primary">View category</a>
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