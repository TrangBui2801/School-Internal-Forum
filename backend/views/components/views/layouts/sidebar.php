<?php

use yii\helpers\Url;
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= Url::to('/site/index'); ?>" class="brand-link">
        <img src="/dist/img/Logo-icon.png" alt="Admin Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">SForum</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= Yii::$app->user->identity->full_name; ?></a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php

            use backend\controllers\MenuController;

            $data = MenuController::getMenus();
            $menu_items = array();
            foreach ($data as $menu) {
                $submenu_items = array();
                foreach ($menu->submenus as $submenu) {
                    if ($submenu->parentId != NULL) {
                        $sub_temp = array([
                            'label' => $submenu->label,
                            'icon' => $submenu->icon,
                            'iconStyle' => $submenu->icon_style,
                            'url' => $submenu->url
                        ]);
                    }
                    $submenu_items = array_merge($submenu_items, $sub_temp);
                }
                $temp = array([
                    'label' => $menu->label,
                    'icon' => $menu->icon,
                    'iconStyle' => $menu->icon_style,
                    'items' => $submenu_items,
                ]);
                $menu_items = array_merge($menu_items, $temp);
            }
            

            echo \backend\views\components\widgets\Menu::widget([
                'items' => $menu_items,
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>