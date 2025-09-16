<div class="container-fluid bg-blue-gra vh-100">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="d-flex align-items-center justify-content-between p-3">
                <a href="/systemaccount/" class="btn btn-sm">
                    <span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span>
                </a>
                <div class="d-flex align-items-center">
                    <p class="fw-bold text-14">TẠO TÀI KHOẢN MỚI</p>
                </div>
                <div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <!-- Add Form -->
            <div class="px-3">
                <div class="bg-white shadow-gg rounded p-3">
                    <form action="/systemaccount/do.php" method="post">
                        <input type="hidden" name="action" value="add">
                        <!-- Message -->
                        <?php
                        if (isset($_GET['mess'])) {
                        ?>
                            <div class="alert alert-success" role="alert">
                                <?= $_GET['mess']; ?>
                            </div>
                        <?php
                        }
                        ?>
                        <!-- Name -->
                        <div class="mb-3 row">
                            <label for="name" class="col-3 text-sub">Tên</label>
                            <div class="col-9">
                                <input type="text" class="form-control" id="name" name="name" placeholder="" required>
                            </div>
                        </div>
                        <!-- Phone -->
                        <div class="mb-3 row">
                            <label for="phone" class="col-3 text-sub">Phone</label>
                            <div class="col-9">
                                <input type="tel" minlength="10" maxlength="10" class="form-control" id="phone" name="phone" placeholder="" required>
                            </div>
                        </div>
                        <!-- Gmail -->
                        <div class="mb-3 row">
                            <label for="name" class="col-3 text-sub">Gmail</label>
                            <div class="col-9">
                                <input type="email" class="form-control" id="gmail" name="gmail" placeholder="" required>
                            </div>
                        </div>
                        <!-- Password -->
                        <div class="mb-4 row">
                            <label for="password" class="col-3 text-sub">Password</label>
                            <div class="col-9">
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>

                        <div class="d-grid"><input type="submit" class="btn btn-sm btn-dark" value="Tạo"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>