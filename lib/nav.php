<!-- Bottom Nav - Show on Mobile Only -->
<nav class="d-lg-none fixed-bottom shadow-gg">
  <div class="bottom_nav_container">
    <a href="../home" class="bottom_nav_item <?= (basename(dirname($_SERVER['PHP_SELF'])) == "home") ? " active" : ""; ?>">
      <span class="material-symbols-outlined bottom_nav_icon">home</span>
      <p class="bottom_nav_title">Trang Chủ</p>
    </a>

    <a href="../nhaphang" class="bottom_nav_item <?= (basename(dirname($_SERVER['PHP_SELF'])) == "nhaphang") ? " active" : ""; ?>">
      <span class="material-symbols-outlined bottom_nav_icon">download</span>
      <p class="bottom_nav_title">Nhập Hàng</p>
    </a>

    <a href="../xuathang" class="bottom_nav_item <?= (basename(dirname($_SERVER['PHP_SELF'])) == "xuathang") ? " active" : ""; ?>">
      <span class="material-symbols-outlined bottom_nav_icon">upload</span>
      <p class="bottom_nav_title">Xuất Hàng</p>
    </a>

    <a href="../chiphi" class="bottom_nav_item <?= (basename(dirname($_SERVER['PHP_SELF'])) == "chiphi") ? " active" : ""; ?>">
      <span class="material-symbols-outlined bottom_nav_icon">shelves</span>
      <p class="bottom_nav_title">Chi Phí</p>
    </a>

    <a href="../account" class="bottom_nav_item <?= (basename(dirname($_SERVER['PHP_SELF'])) == "account") ? " active" : ""; ?>">
      <span class="material-symbols-outlined bottom_nav_icon">face</span>
      <p class="bottom_nav_title">Tui</p>
    </a>
  </div>
</nav>