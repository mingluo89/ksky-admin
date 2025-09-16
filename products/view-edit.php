<?php
$sql1 = "SELECT * FROM products WHERE id ='" . $_GET['id'] . "'";
$res1 = mysqli_query($connect, $sql1);
$count1 = mysqli_num_rows($res1);
if ($count1 > 0) {
    while ($row1 = mysqli_fetch_assoc($res1)) {
?>
        <div class="container-fluid bg-blue-gra vh-100">
            <div class="row">
                <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                    <div class="d-flex align-items-center justify-content-between p-3">
                        <a href="javascript:history.back()" class="btn btn-sm">
                            <span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span>
                        </a>
                        <div class="d-flex align-items-center">
                            <p class="fw-bold text-14">SỬA SẢN PHẨM</p>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                    <!-- Add Form -->
                    <div class="px-3">
                        <div class="bg-white shadow-gg rounded p-3">
                            <form action="/products/do.php" method="post">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="id" value="<?= $row1['id']; ?>">

                                <!-- Mã Sản phẩm -->
                                <label class="form-label mb-2" for="product_code">Mã sản phẩm</label>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">barcode</span>
                                    <input class="form-control" id="product_code" name="product_code" placeholder="" type="text" value="<?= $row1['product_code']; ?>" required>
                                </div>

                                <!-- Tên sản phẩm -->
                                <label class="form-label mb-2" for="product_name">Tên sản phẩm</label>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">package_2</span>
                                    <textarea class="form-control" id="product_name" name="product_name" required><?= $row1['product_name']; ?></textarea>
                                </div>

                                <!-- Đơn vị -->
                                <label class="form-label mb-2" for="unit">ĐVT</label>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">category</span>
                                    <input class="form-control" id="unit" name="unit" placeholder="" type="text" value="<?= $row1['unit']; ?>" required>
                                </div>

                                <!-- Kỳ bắt đầu -->
                                <label class="form-label mb-2" for="start_period">Kỳ bắt đầu</label>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">event</span>
                                    <select name="start_period" id="start_period" class="form-select" required>
                                        <?php
                                        $sql_period = "SELECT * FROM period";
                                        $res_period = mysqli_query($connect, $sql_period);
                                        while ($row_period = mysqli_fetch_array($res_period)) {
                                        ?>
                                            <option value="<?= $row_period['date_start']; ?>" <?= ($row_period['date_start'] == $row1['start_period']) ? "selected" : ""; ?>><?= $row_period['name']; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- SL đầu -->
                                <label class="form-label mb-2" for="start_qty">SL tồn đầu tiên</label>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">123</span>
                                    <input class="form-control" id="start_qty" name="start_qty" placeholder="" type="number" value="<?= $row1['start_qty']; ?>" required>
                                </div>

                                <!-- Giá trị đầu -->
                                <label class="form-label mb-2" for="start_value">TT tồn đầu tiên</label>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="material-symbols-outlined text-20 me-3 text-theme">money</span>
                                    <input class="form-control" id="start_value" name="start_value" placeholder="" type="number" value="<?= $row1['start_value']; ?>" required>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-sm btn-dark fw-bold">Sửa</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
} else {
    ?>
    <p class="text-center">Không tìm thấy sản phẩm #<?= $_GET['productid']; ?></p>
<?php
}
?>