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
                <a href="/nxt" class="btn btn-sm">
                    <span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span>
                </a>
                <div class="d-flex align-items-center">
                    <p class="fw-bold text-14">CẬP NHẬT BẢNG NHẬP XUẤT TỒN</p>
                </div>
                <div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="px-3">
                <div class="bg-white shadow-gg rounded p-3">
                    <form action="/nxt/do.php" method="post">
                        <input type="hidden" name="action" value="sync">

                        <label class="form-label mb-2" for="product_code">Cập nhật từ 2025Q1 đến kỳ</label>
                        <div class="d-flex align-items-center mb-3">
                            <span class="material-symbols-outlined text-20 me-3 text-theme">event</span>
                            <select class="form-select" id="until" name="until" required>
                                <?php
                                $sql_period = "SELECT * FROM period";
                                $res_period = mysqli_query($connect, $sql_period);
                                while ($row_period = mysqli_fetch_assoc($res_period)) {
                                ?>
                                    <option value="<?= $row_period['date_start']; ?>" <?= ($row_period['date_start'] == $current_quarter_date) ? "selected" : ""; ?>><?= $row_period['name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-sm btn-dark fw-bold">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>