<?php
function getFirstDateOfQuarter($date = 'now')
{
    $dt = new DateTime($date);
    $month = (int)$dt->format('n');
    $year = $dt->format('Y');
    $quarterStartMonth = (floor(($month - 1) / 3) * 3) + 1;
    $firstDate = (new DateTime("$year-$quarterStartMonth-01"))->format('Y-m-d');
    return $firstDate;
}
$current_quarter_date = getFirstDateOfQuarter();
?>
<div class="container-fluid bg-blue-gra vh-100">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="d-flex align-items-center justify-content-between p-3">
                <a href="javascript:history.back()" class="btn btn-sm">
                    <span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span>
                </a>
                <div class="d-flex align-items-center">
                    <p class="fw-bold text-14">TẠO MỚI SẢN PHẨM</p>
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
                        <input type="hidden" name="action" value="add">

                        <!-- Mã Sản phẩm -->
                        <label class="form-label mb-2" for="product_code">Mã sản phẩm</label>
                        <div class="d-flex align-items-center mb-3">
                            <span class="material-symbols-outlined text-20 me-3 text-theme">barcode</span>
                            <input class="form-control" id="product_code" name="product_code" placeholder="" type="text" required>
                        </div>

                        <!-- Tên sản phẩm -->
                        <label class="form-label mb-2" for="product_name">Tên sản phẩm</label>
                        <div class="d-flex align-items-center mb-3">
                            <span class="material-symbols-outlined text-20 me-3 text-theme">package_2</span>
                            <textarea class="form-control" id="product_name" name="product_name" required></textarea>
                        </div>

                        <!-- Đơn vị -->
                        <label class="form-label mb-2" for="unit">ĐVT</label>
                        <div class="d-flex align-items-center mb-3">
                            <span class="material-symbols-outlined text-20 me-3 text-theme">category</span>
                            <input class="form-control" id="unit" name="unit" placeholder="" type="text" required>
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
                                    <option value="<?= $row_period['date_start']; ?>" <?= ($row_period['date_start'] == $current_quarter_date) ? "selected" : ""; ?>><?= $row_period['name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <!-- SL đầu -->
                        <label class="form-label mb-2" for="start_qty">SL tồn đầu tiên</label>
                        <div class="d-flex align-items-center mb-3">
                            <span class="material-symbols-outlined text-20 me-3 text-theme">123</span>
                            <input class="form-control" id="start_qty" name="start_qty" placeholder="" type="number" required>
                        </div>

                        <!-- Giá trị đầu -->
                        <label class="form-label mb-2" for="start_value">TT tồn đầu tiên</label>
                        <div class="d-flex align-items-center mb-3">
                            <span class="material-symbols-outlined text-20 me-3 text-theme">money</span>
                            <input class="form-control" id="start_value" name="start_value" placeholder="" type="number" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-sm btn-dark fw-bold">Tạo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>