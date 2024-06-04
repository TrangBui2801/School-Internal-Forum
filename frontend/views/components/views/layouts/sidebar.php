<?php

use common\helpers\ImageUrlHelper;
use common\models\constants\FileTypeConstant;
use frontend\models\File;
use frontend\models\Post;
use frontend\models\Thread;
use yii\helpers\Url;

?>
<div class="content_right">
    <div class="w-100">
        <a href="<?= Url::to(['thread/create-private-group']) ?>" class="btn btn-outline-primary w-100 mb-10 mt-10">Create group</a>
    </div>
    <div class="newPost widget">
        <!-- new post title -->
        <div class="widget_title-span">
            <h4>Private group</h4>
        </div>
        <?php $private_groups = Thread::getPrivateGroup(); ?>
        <div class="newPost_container_list">
            <?php if ($private_groups) : ?>
                <?php foreach ($private_groups as $group) : ?>
                    <div class="newPost_container">
                        <img src="<?= ImageUrlHelper::getImageUrl($group->image); ?>" alt="">
                        <div class="newPost_content">
                            <div class="newPost_content_header">
                                <a href="<?= Url::to(['thread/view-group', 'threadId' => $group->id]) ?>"><?= $group->name; ?></a>
                            </div>
                            <div class="newPost_content_bottom newPost_content_bottom_desc">
                                <div class="desc_author desc_author_newPost">
                                    <i class="far fa-user"></i> <?= count($group->getGroupMembers()->asArray()->all()); ?> users

                                </div>
                                <div class="desc_cmt desc_cmt_newPost">
                                    <i class="far fa-comment"></i>
                                    <?= count($group->getPosts()->asArray()->all()); ?> new posts
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="newPost_container">
                    <div class="newPost_content text-center">
                        <p>No group found</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="newPost">
        <!-- new post title -->
        <div class="widget_title-span">
            <h4>Lastest post</h4>
        </div>
        <?php $lastest_posts = Post::getLastestPost(); ?>
        <!-- new post content -->
        <div class="newPost_container_list">
            <?php if ($lastest_posts) : ?>
                <?php foreach ($lastest_posts as $post) : ?>
                    <div class="newPost_container">
                        <?php
                        $cover = File::find()->where(['=', 'parentId', $post->id])->andWhere(['=', 'file_type', FileTypeConstant::POST_IMAGE_COVER])->one();
                        if ($cover) {
                            $url = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://' . 'admin.ep.com' . substr($cover->url, strripos($cover->url, '/uploads'), strlen($cover->url));
                            echo '<img src="' . $url . '" alt="post-thumb">';
                        } else {
                            echo '<img src="../images/post/post-1.jpg" alt="post-thumb">';
                        }
                        ?>
                        <div class="newPost_content">
                            <div class="newPost_content_header">
                                <a href="<?= Url::to(['post/view', 'id' => $post->id]) ?>"><?= $post->title ?></a>
                            </div>
                            <div class="newPost_content_bottom newPost_content_bottom_desc">
                                <div class="desc_author desc_author_newPost">
                                    <i class="far fa-user"></i>
                                    <?= $post->author->full_name ?>
                                </div>
                                <div class="desc_cmt desc_cmt_newPost">
                                    <i class="far fa-comment"></i>
                                    <?= $post->reply_count ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="newPost_container">
                    <div class="newPost_content text-center">
                        <p>No post found</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="topScore">
        <div class="widget_title-span">
            <h4>Top score</h4>
        </div>
        <div class="topScore_container_list">
            <div class="topScore_people_container">
                <div class="topScore_people_container_left">
                    <div class="topScore_people_container_left_avatar modal-body-profile-pic">
                        <img src="https://hedieuhanh.com/wp-content/uploads/2019/08/anh-avatar-buc-minh-cuc-hai-huoc.jpg" alt="">
                    </div>
                </div>
                <div class="topScore_people_container_right">
                    <div class="topScore_people_container_right_header">
                        <p>
                            +
                            <span>396</span>
                        </p>
                        <p>vip</p>
                    </div>
                    <div class="topScore_people_container_right_bottom">
                        <span>Nguyen Van A (BTEC_HN)</span>
                    </div>
                </div>
            </div>
            <div class="topScore_people_container">
                <div class="topScore_people_container_left">
                    <div class="topScore_people_container_left_avatar modal-body-profile-pic">
                        <img src="https://hedieuhanh.com/wp-content/uploads/2019/08/anh-avatar-buc-minh-cuc-hai-huoc.jpg" alt="">
                    </div>
                </div>
                <div class="topScore_people_container_right">
                    <div class="topScore_people_container_right_header">
                        <p>
                            +
                            <span>130</span>
                        </p>
                        <p>high grade</p>
                    </div>
                    <div class="topScore_people_container_right_bottom">
                        Nguyen Thi Lmao (BTEC_HCM) ád ád sad á
                    </div>
                </div>
            </div>
            <div class="topScore_people_container">
                <div class="topScore_people_container_left">
                    <div class="topScore_people_container_left_avatar modal-body-profile-pic">
                        <img src="/resources/img/pic.png" alt="" data-toggle="modal" data-target="#profile-modal">
                    </div>
                </div>
                <div class="topScore_people_container_right">
                    <div class="topScore_people_container_right_header">
                        <p>
                            +
                            <span>450</span>
                        </p>
                        <p>vip</p>
                    </div>
                    <div class="topScore_people_container_right_bottom">
                        <span data-toggle="modal" data-target="#profile-modal">John Doe</span>
                    </div>
                </div>
            </div>
            <div class="topScore_people_container">
                <div class="topScore_people_container_left">
                    <div class="topScore_people_container_left_avatar modal-body-profile-pic">
                        <img src="https://anhdepfree.com/wp-content/uploads/2019/01/avatar-den-bo-vest-dep_015639142.jpg" alt="">
                    </div>
                </div>
                <div class="topScore_people_container_right">
                    <div class="topScore_people_container_right_header">
                        <p>
                            +
                            <span>65</span>
                        </p>
                        <p>high grade</p>
                    </div>
                    <div class="topScore_people_container_right_bottom">
                        <span>Mr. John Wich</span>
                    </div>
                </div>
            </div>
            <div class="topScore_people_container">
                <div class="topScore_people_container_left">
                    <div class="topScore_people_container_left_avatar modal-body-profile-pic">
                        <img src="https://taoanhonline.com/wp-content/uploads/2019/08/hinh-anh-avatar-96.jpg" alt="">
                    </div>
                </div>
                <div class="topScore_people_container_right">
                    <div class="topScore_people_container_right_header">
                        <p>
                            +
                            <span>30</span>
                        </p>
                        <p>Positive</p>
                    </div>
                    <div class="topScore_people_container_right_bottom">
                        <span>Tran Duc Bo</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>