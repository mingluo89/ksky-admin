<body>
    <?php include __DIR__ . '/../lib/nav.php'; ?>
    <section>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . '/../lib/nav-side.php'; ?>
                <div class="col-12 col-lg-9 col-xl-10 bg-blue-gra vh-100" style="overflow: auto;">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                                <div class="d-flex align-items-center justify-content-between p-3">
                                    <a href="javascript:history.back()" class="btn btn-sm">
                                        <span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span>
                                    </a>
                                    <div class="d-flex align-items-center">
                                        <p class="fw-bold text-14">ĐỔI MẬT KHẨU</p>
                                    </div>
                                    <div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                                <!-- Main Form -->
                                <div class="bg-white shadow-gg rounded p-3">
                                    <form action="do.php" method="post" class="mt-3">
                                        <input type="hidden" name="action" value="change-pass">
                                        <input type="hidden" name="id" value="<?= $in_id; ?>">
                                        <div class="mb-3">
                                            <label for="newPass1" class="mb-2">
                                                <p>Mật khẩu mới</p>
                                            </label>
                                            <input type="password" class="form-control" required name="new_pass_1" id="newPass1">
                                        </div>
                                        <div class="mb-4">
                                            <label for="newPass2" class="mb-2">
                                                <p>Nhập lại mật khẩu mới</p>
                                            </label>
                                            <input type="password" class="form-control" required name="new_pass_2" id="newPass2">
                                        </div>

                                        <div class="d-grid">
                                            <input type="submit" class="btn btn-sm btn-dark" value="Đổi">
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>