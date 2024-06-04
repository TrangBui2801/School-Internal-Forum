<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<div class="modal fade edit-modal-profile" id="update-profile-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog  edit-modal-dialog-profile" role="document">
        <div class="modal-content edit-modal-content">
            <div class="modal-header edit-modal-header">
                <button type="button" class="close edit-close-profile" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal-body-profile modal-body-profile-update">
                <?php $form = ActiveForm::begin([
                    'action' => Url::to(['user/update', 'id' => Yii::$app->user->identity->id]),
                    'method' => 'POST'
                ]); ?>
                    <div class="form-row-update-profile">
                        <div class="form-row">
                            <div class="col-md-5 mb-3">
                                <label for="validationDefault01">Name</label>
                                <input type="text" class="form-control form-control-sm" id="validationDefault01" value="" minlength="6" maxlength="50" required>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="validationDefault02">Date of birth</label>
                                <input type="date" class="form-control form-control-sm" id="validationDefault02" value="" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="validationDefault03">Gender</label>
                                <select class="form-control form-control-sm" id="validationDefault03" required>
                                    <option selected disabled value="">Choose...</option>
                                    <option value="">Male</option>
                                    <option value="">Female</option>
                                    <option value="">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-5 mb-3">
                                <label for="validationDefault07">Avatar</label>
                                <input type="file" class="form-control form-control-sm form-control-file-avatar" id="validationDefault07">
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="validationDefault04">Major</label>
                                <input type="text" class="form-control form-control-sm" id="validationDefault04" value="" minlength="6" maxlength="50" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="validationDefault05">Phone</label>
                                <input type="number" class="form-control form-control-sm" id="validationDefault05" min="100000000" onKeyPress="if(this.value.length==10) return false;">
                            </div>
                        </div>
                        <div class="form-row ">
                            <div class="col-md-12 mb-3">
                                <label for="validationDefault06">Address</label>
                                <input type="text" class="form-control form-control-sm" id="validationDefault06" minlength="10" maxlength="100">
                            </div>
                        </div>
                    </div>
                    <div class="btn-action-update-profile">
                        <button class="btn btn-warning btn-jump btn-update-profile btn-update-profile-back" type="button" data-dismiss="modal" data-toggle="modal" data-target="#profile-modal">Back</button>
                        <button class="btn btn-success btn-jump btn-update-profile btn-update-profile-save" type="submit">Save</button>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer  modal-footer-profile">
                <div class="modal-footer-profile-numbers">
                    <div class="modal-footer-profile-item">
                        <span>120</span>
                        <span>Posts</span>
                    </div>
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