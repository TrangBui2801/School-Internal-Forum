<div class="modal fade edit-modal-profile" id="profile-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg edit-modal-dialog-profile" role="document">
        <div class="modal-content edit-modal-content">
            <div class="modal-header edit-modal-header">
                <button type="button" class="edit-modal-show-more">
                    <i class="fas fa-angle-double-right" onclick="change_profile()"></i>
                </button>
                <button type="button" class="close edit-close-profile" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal-body-profile">
                <div class="modal-body-profile-left" id="modal-body-profile-left">
                    <div class="modal-body-profile-pic">
                        <img src="/resources/img/pic.png" alt="">
                    </div>
                    <div class="modal-body-profile-name">
                        <span><?= Yii::$app->user->identity->full_name ?></span>
                    </div>
                    <div class="modal-body-profile-desc">
                        <span><?= Yii::$app->user->identity->short_introduction ?></span>
                    </div>
                    <div class="modal-body-profile-sm">
                        <a href="<?= Yii::$app->user->identity->facebook_link ?>" target="_blank" class="fab fa-facebook-f"></a>
                        <a href="<?= Yii::$app->user->identity->skype_link ?>" target="_blank" class="fab fa-skype <?php

                                    use yii\helpers\Url;

 if (Yii::$app->user->identity->skype_link == "") {
                                                                                                                        echo 'disabled';
                                                                                                                    } ?>"></a>
                        <a href="<?= Yii::$app->user->identity->github_link ?>" target="_blank" class="fab fa-github <?php if (!Yii::$app->user->identity->github_link) {
                                                                                                                            echo 'disabled';
                                                                                                                        } ?>"></a>
                        <a href="<?= Yii::$app->user->identity->youtube_link ?>" target="_blank" class="fab fa-youtube <?php if (!Yii::$app->user->identity->youtube_link) {
                                                                                                                            echo 'disabled';
                                                                                                                        } ?>"></a>
                    </div>
                    <a href="#" class="modal-body-profile-contact-btn">Contact Me</a>
                </div>
                <div class="modal-body-profile-right" id="modal-body-profile-right">
                    <ul>
                        <li>
                            <h6>Name</h6>
                            <span><?= Yii::$app->user->identity->full_name ?></span>
                        </li>
                        <li>
                            <h6>Date of birth</h6>
                            <span><?= Yii::$app->user->identity->birthday ?></span>
                        </li>
                        <li>
                            <h6>Gender</h6>
                            <span><?= Yii::$app->user->identity->gender ?></span>
                        </li>
                        <li>
                            <h6>Address</h6>
                            <span><?= Yii::$app->user->identity->address ?></span>
                        </li>
                        <li>
                            <h6>Phone Number</h6>
                            <span><?= Yii::$app->user->identity->phone_number ?></span>
                        </li>
                        <li>
                            <button type="button" data-dismiss="modal" data-toggle="modal" data-target="#update-profile-modal">
                                <i class="fas fa-user-edit"></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer  modal-footer-profile">
                <div class="modal-footer-profile-numbers">
                    <a class="modal-footer-profile-item" href="<?= Url::to(['user/get-author', 'id' => Yii::$app->user->identity->id]) ?>">
                        <span>120</span>
                        <span>Posts</span>
                    </a>
                    <div class="modal-footer-profile-border-card"></div>
                    <div class="modal-footer-profile-item">
                        <span>127</span>
                        <span>Scores</span>
                    </div>
                    <div class="modal-footer-profile-border-card"></div>
                    <div class="modal-footer-profile-item">
                        <span>120K</span>
                        <span>Other</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>