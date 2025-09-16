<?php
$menu_json = file_get_contents("../lib/configMenu.json");
$menu = json_decode($menu_json, true);
?>
<div class="d-none d-lg-block col-lg-3 col-xl-2 nav-side px-2 bg-white" style="min-height: 100vh; border-right: 0.5px #EEEEEE solid; overflow:auto;">
    <a href="../account">
        <div class="d-flex py-4">
            <div class="px-2">
                <img src="../img/icon.png" class="rounded-circle mb-2 mr-3" width="40">
            </div>
            <div class="pt-1">
                <p class="fw-bold"><?= $in_name; ?></p>
                <p class="text-sub"><?= $in_phone; ?></p>
            </div>
        </div>
    </a>

    <div>
        <a href="../home" class="admin_sidenav_list_tile <?= (basename(dirname($_SERVER['PHP_SELF'])) == "home") ? "active" : ""; ?>">
            <span class="material-symbols-outlined admin_sidenav_list_tile_icon">home</span>
            <p class="">Trang Chủ</p>
        </a>
        <!-- Quick Links -->
        <div class="mb-2">
            <div class="d-grid">
                <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#sideCollapse0" aria-expanded="false" aria-controls="sideCollapse0" id="sideCollapseBtn0">
                    <p class="text-main-inv fw-bold text-start my-1">Quick</p>
                </button>
            </div>
            <div id="sideCollapse0" class="collapse show" data-bs-parent="#sideCollapseBtn0">
                <a href="../nhaphang" class="admin_sidenav_list_tile <?= (basename(dirname($_SERVER['PHP_SELF'])) == "nhaphang") ? "active" : ""; ?>">
                    <span class="material-symbols-outlined admin_sidenav_list_tile_icon">download</span>
                    <p class="">Nhập hàng</p>
                </a>
                <a href="../xuathang" class="admin_sidenav_list_tile <?= (basename(dirname($_SERVER['PHP_SELF'])) == "xuathang") ? "active" : ""; ?>">
                    <span class="material-symbols-outlined admin_sidenav_list_tile_icon">upload</span>
                    <p class="">Xuất hàng</p>
                </a>
                <a href="../chiphi" class="admin_sidenav_list_tile <?= (basename(dirname($_SERVER['PHP_SELF'])) == "chiphi") ? "active" : ""; ?>">
                    <span class="material-symbols-outlined admin_sidenav_list_tile_icon">remove_circle</span>
                    <p class="">Chi phí</p>
                </a>
                <a href="../luong" class="admin_sidenav_list_tile <?= (basename(dirname($_SERVER['PHP_SELF'])) == "luong") ? "active" : ""; ?>">
                    <span class="material-symbols-outlined admin_sidenav_list_tile_icon">payments</span>
                    <p class="">Lương</p>
                </a>
            </div>
        </div>
        <!-- MENU -->
        <?php
        foreach ($menu as $i => $part) {
        ?>
            <!-- <?php echo $part['name']; ?> -->
            <div class="mb-2">
                <div class="d-grid">
                    <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#sideCollapse<?php echo $i; ?>" aria-expanded="false" aria-controls="sideCollapse<?php echo $i; ?>" id="sideCollapseBtn<?php echo $i; ?>">
                        <p class="text-main-inv fw-bold text-start my-1"><?php echo $part['name']; ?></p>
                    </button>
                </div>
                <div id="sideCollapse<?php echo $i; ?>" class="collapse show" data-bs-parent="#sideCollapseBtn<?php echo $i; ?>">
                    <?php foreach ($part["submenu"] as $j => $link) { ?>
                        <a href="../<?php echo $link['link']; ?>" class="admin_sidenav_list_tile <?= (basename(dirname($_SERVER['PHP_SELF'])) == $link['link']) ? "active" : ""; ?>">
                            <span class="material-symbols-outlined admin_sidenav_list_tile_icon"><?php echo $link['icon']; ?></span>
                            <p class=""><?php echo $link['name']; ?></p>
                        </a>
                    <?php } ?>
                </div>
            </div>
        <?php

        } ?>
    </div>

    <!-- Logout -->
    <div class="d-grid">
        <a href="../logout" class="btn btn-sm btn-outline-danger my-3">Đăng xuất</a>
    </div>
</div>