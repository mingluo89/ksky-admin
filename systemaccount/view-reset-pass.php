<?php
$sql = "SELECT * FROM ops_user WHERE id = " . $_GET['id'];
$res = mysqli_query($connect, $sql);
$count = mysqli_num_rows($res);
while ($row = mysqli_fetch_array($res)) {
?>
    <div class="container-fluid bg-blue-gra vh-100">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <div class="d-flex align-items-center justify-content-between p-3">
                    <a href="/systemaccount/" class="btn btn-sm">
                        <span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span>
                    </a>
                    <div class="d-flex align-items-center">
                        <p class="fw-bold text-14">Đặt lại mật khẩu</p>
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
                            <input type="hidden" name="action" value="reset-pass">
                            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
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
                            <!-- Password -->
                            <div class="mb-4 row">
                                <label for="password" class="col-3 text-sub">Password</label>
                                <div class="col-9">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>

                            <div class="d-grid"><input type="submit" class="btn btn-sm btn-dark" value="Đặt lại"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>