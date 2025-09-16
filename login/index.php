<?php
include __DIR__ . '/../lib/connect.php';
include __DIR__ . '/../lib/session.php';

if (isset($_SESSION['in_phone'])) {
  header('Location: /home');
} else {
  include __DIR__ . '/../lib/header.php';
?>

  <body class="bg-blue-gra">
    <!-- Form Login -->
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4 p-3 mt-3">
          <div class="text-center">
            <a href="/">
              <img src="/img/logo-white.png?v=2" width="120" class="mt-3 mb-5">
            </a>
            <!-- <p class="mb-5 text-20 text-light">ADMIN</p> -->
          </div>
          <div class="shadow-gg bg-white rounded-20 p-3">
            <form action="/login/do.php" method="post">
              <div class="d-grid gap-3">
                <input type="hidden" name="action" value="login">

                <!-- Wrong Pass -->
                <?php if (isset($_GET['message']) && ($_GET['message'] == "wrongpass")) { ?>
                  <div class="alert alert-info" role="alert">
                    <p class="text-12 mb-2">Mật khẩu không đúng với tài khoản <b><?= $_GET['phone']; ?></b>!</p>
                  </div>
                <?php } ?>

                <?php if (isset($_GET['message']) && ($_GET['message'] == "noacc")) { ?>
                  <!-- No Account -->
                  <div class="alert alert-info" role="alert">
                    <p class="text-12 mb-2">Số điện thoại <b><?= $_GET['phone']; ?></b> chưa có tài khoản!</p>
                  </div>
                <?php } ?>

                <?php if (!isset($_GET['message'])) { ?>
                  <!-- Spacer -->
                  <div class="my-2"></div>
                <?php } ?>

                <!-- Phone -->
                <div class="">
                  <label for="phone" class="mb-3 form-label">Số điện thoại</label>
                  <input type="tel" id="phone" name="phone" class="form-control bg-blur-white border-none" placeholder="Ví dụ: 0909111888" minlength="10" maxlength="10" value="<?= (isset($_GET['phone'])) ? $_GET['phone'] : ""; ?>" required>
                </div>

                <!-- CMND/CCCD -->
                <div class="mb-3">
                  <label for="password" class="mb-3 form-label">Mật khẩu</label>
                  <input type="password" name="password" id="password" class="form-control bg-blur-white border-none" placeholder="Mật khẩu" required>
                </div>

                <!-- Submit -->
                <div class="d-grid">
                  <input type="submit" value="Tiếp tục" class="btn btn-sm btn-dark">
                </div>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>

  </html>
<?php } ?>