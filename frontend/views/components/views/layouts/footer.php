<?php

use yii\helpers\Url;
?>
<footer class="section-sm pb-0border-top border-default" style="margin-left: 100px; margin-right: 50px; border-top: 1px solid black;">
      <div class="container-footer">
         <div class="row justify-content-between">
            <div class="col-md-3 mb-4">
               <a class="mb-4 d-block  " href="<?= Url::to(['post/index']) ?>" style="text-align: center;">
                  <img class="img-fluid" width="150px" src="<?= stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://' . 'admin.ep.com' ?>/dist/img/Logo-icon.png" alt="SForum">
               </a>
               <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
            </div>

            <div class="col-lg-2 col-md-3 col-6 mb-4">
               <h6 class="mb-4">Quick Links</h6>
               <ul class="list-unstyled footer-list">
                  <li><a href="javascript:void(0)">About</a></li>
                  <li><a href="javascript:void(0)">Contact</a></li>
                  <li><a href="javascript:void(0)">Privacy Policy</a></li>
                  <li><a href="javascript:void(0)">Terms Conditions</a></li>
               </ul>
            </div>

            <div class="col-lg-2 col-md-3 col-6 mb-4">
               <h6 class="mb-4">Social Links</h6>
               <ul class="list-unstyled footer-list">
                  <li><a href="javascript:void(0)">facebook</a></li>
                  <li><a href="javascript:void(0)">twitter</a></li>
                  <li><a href="javascript:void(0)">linkedin</a></li>
                  <li><a href="javascript:void(0)">github</a></li>
               </ul>
            </div>

            <div class="col-md-3 mb-4">
               <h6 class="mb-4">Subscribe Newsletter</h6>
               <form class="subscription" action="javascript:void(0)" method="post">
                  <div class="position-relative">
                     <i class="fa fa-envelope email-icon"></i>
                     <input type="email" class="form-control" placeholder="Your Email Address">
                  </div>
                  <button class="btn btn-primary btn-block rounded" type="submit">Subscribe now</button>
               </form>
            </div>
         </div>
         <div class="scroll-top">
            <a href="javascript:void(0);" id="scrollTop"><i class="ti-angle-up"></i></a>
         </div>
      </div>
   </footer>