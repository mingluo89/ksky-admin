<body>
    <?php include __DIR__ . '/../lib/nav.php'; ?>
    <section>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . '/../lib/nav-side.php'; ?>
                <div class="col-12 col-lg-9 col-xl-10 bg-light" style="height:100vh; overflow-y: auto;">
                    <div class="row p-3 bg-blue-gra">
                        <div class="col-12">
                            <!-- Info -->
                            <div class="d-flex align-items-center justify-content-center">
                                <img src="../img/account-placeholder.svg" width="50" class="me-3" alt="">
                                <div class="pt-1">
                                    <p class="fw-bold text-main text-20"><?php echo $in_name; ?></p>
                                    <p class="text-sub"><?php echo $in_phone; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 offset-md-3 col-lg-4 offset-lg-4 pt-3 px-3 vh-100" style="padding-bottom: 60px;">
                            <p class="fw-bold mb-3">Thông tin tài khoản</p>
                            <ul class="list-group mb-3">
                                <li class="list-group-item">
                                    <a href="/account/?action=change-pass">
                                        <div class="d-flex justify-content-between my-2">
                                            <div class="d-flex align-items-center">
                                                <span class="material-symbols-outlined me-2 text-14">vpn_key</span>
                                                <p>Đổi mật khẩu</p>
                                            </div>
                                            <span class="material-symbols-outlined text-14">arrow_forward_ios</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="list-group-item">
                                    <a href="/account/?action=personal-info">
                                        <div class="d-flex justify-content-between my-2">
                                            <div class="d-flex align-items-center">
                                                <span class="material-symbols-outlined me-2 text-14">
                                                    account_circle
                                                </span>
                                                <p>Thông tin cá nhân</p>
                                            </div>
                                            <span class="material-symbols-outlined text-14">arrow_forward_ios</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>

                            <ul class="list-group">
                                <li class="list-group-item">
                                    <a href="/logout">
                                        <p class="text-danger text-center my-2 text-14 fw-bold">Đăng Xuất</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>