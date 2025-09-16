<?php
function getQuarterStartDate($dateString)
{
    $date = new DateTime($dateString);
    $month = (int)$date->format('n');
    $year = $date->format('Y');

    // Determine the first month of the quarter
    if ($month >= 1 && $month <= 3) {
        $quarterStartMonth = 1;
    } elseif ($month >= 4 && $month <= 6) {
        $quarterStartMonth = 4;
    } elseif ($month >= 7 && $month <= 9) {
        $quarterStartMonth = 7;
    } else {
        $quarterStartMonth = 10;
    }

    return sprintf('%s-%02d-01', $year, $quarterStartMonth);
}
$sql = "SELECT * FROM xuat_detail WHERE id ='" . $_GET['detailid'] . "'";
$res = mysqli_query($connect, $sql);
$count = mysqli_num_rows($res);
if ($count > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        $period_current = getQuarterStartDate($row['accounting_date']);

        // Before
        $sql_price_main = "SELECT * FROM nxt WHERE product_id = '" . $row['product_id'] . "' AND period ='$period_current'";
        $res_price_main = mysqli_query($connect, $sql_price_main);
        $count_price_main = mysqli_num_rows($res_price_main);
        if ($count_price_main > 0) {
            while ($row_price_main = mysqli_fetch_assoc($res_price_main)) {
                $price_nhap_main = (is_null($row_price_main['price_weighted'])) ? 0 : $row_price_main['price_weighted'];
            }
            if ($price_nhap_main == 0) {
                $profit_before = 0;
            } else {
                $profit_before = ($row['price'] - $price_nhap_main) / $price_nhap_main * 100;
            }
            $text_before = number_format($price_nhap_main, 0);
        } else {
            $price_nhap_main = 0;
            $profit_before = 0;
            $text_before = "Không tìm ra giá nhập";
        }


        // After
        $sql_price_nhap_ao = "SELECT SUM(total_before_vat) as ao FROM xuat_detail WHERE is_it = 1 AND is_it_for = '" . $_GET['detailid'] . "'";
        $res_price_nhap_ao = mysqli_query($connect, $sql_price_nhap_ao);
        $count_price_nhap_ao = mysqli_num_rows($res_price_nhap_ao);
        if ($count_price_nhap_ao > 0) {
            while ($row_price_nhap_ao = mysqli_fetch_assoc($res_price_nhap_ao)) {
                $price_nhap_ao = (is_null($row_price_nhap_ao['ao'])) ? 0 : $row_price_nhap_ao['ao'];
            }
        } else {
            $price_nhap_ao = 0;
        }
        $price_nhap_after = $price_nhap_main + $price_nhap_ao;

        if ($price_nhap_after == 0) {
            $profit_after = 0;
        } else {
            $profit_after = ($row['price'] - $price_nhap_after) / $price_nhap_after * 100;
        }

?>
        <div class="container-fluid bg-blue-gra vh-100" style="overflow:auto">
            <div class="row">
                <div class="col-12 col-md-8 offset-md-2 col-xl-6 offset-xl-3">
                    <div class="d-flex align-items-center justify-content-between py-3">
                        <a href="./?view=detail&id=<?= $row['xuat_id']; ?>" class="btn btn-sm">
                            <span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span>
                        </a>
                        <div class="text-center">
                            <p class="fw-bold text-16">TÍNH TOÁN</p>
                        </div>
                        <div>
                        </div>
                    </div>

                    <div class="rounded shadow-gg bg-white mb-3 p-3">
                        <p class="fw-bold mb-2">Mặt hàng tính toán</p>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hovered mb-2">
                                <thead class="table-secondary">
                                    <th class="text-10">ID SP</th>
                                    <th class="text-10 d-none d-md-table-cell">Mã SP</th>
                                    <th class="text-10">TÊN SP</th>
                                    <th class="text-10">ĐVT</th>
                                    <th class="text-10 text-end">SL</th>
                                    <th class="text-10 text-end">Giá xuất</th>
                                    <th class="text-10 text-end">TT</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <p class="text-10"><?= $row['product_id']; ?></p>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <p class="text-10"><?= $row['product_code']; ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10 fw-bold text-wrap"><?= $row['product_name']; ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10"><?= $row['unit']; ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10 text-end"><?= number_format($row['qty'], 0); ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10 text-end"><?= number_format($row['price'], 0); ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10 text-end"><?= number_format($row['total_before_vat'], 0); ?></p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="rounded shadow-gg bg-white mb-3 p-3">
                                <p class="fw-bold text-20"><?= number_format($profit_before, 0); ?>%</p>
                                <p class="text-secondary text-10">Lợi nhuận trước</p>
                                <p class="text-dark text-10">(<?= number_format($row['price'], 0) . " / <b>" . $text_before; ?></b>)</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="rounded shadow-gg bg-white mb-3 p-3">
                                <p class="fw-bold text-20"><?= number_format($profit_after, 0); ?>%</p>
                                <p class="text-secondary text-10">Lợi nhuận sau</p>
                                <p class="text-dark text-10">(<?= number_format($row['price'], 0) . " / <b>" . number_format($price_nhap_after, 0); ?></b>)</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded shadow-gg bg-white mb-3 p-3">
                        <p class="fw-bold mb-2">Tất cả sản phẩm cấu thành cho mặt hàng này</p>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hovered mb-2">
                                <thead class="table-secondary">
                                    <th class="text-10">ID SP</th>
                                    <th class="text-10 d-none d-md-table-cell">Mã SP</th>
                                    <th class="text-10">TÊN SP</th>
                                    <th class="text-10">ĐVT</th>
                                    <th class="text-10 text-end">SL</th>
                                    <th class="text-10 text-end">Giá nhập</th>
                                    <th class="text-10 text-end">TT</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <p class="text-10"><?= $row['product_id']; ?></p>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <p class="text-10"><?= $row['product_code']; ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10 fw-bold text-wrap"><?= $row['product_name']; ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10"><?= $row['unit']; ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10 text-end"><?= number_format($row['qty'], 0); ?></p>
                                        </td>
                                        <?php
                                        ?>
                                        <td>
                                            <p class="text-10 text-end"><?= number_format($price_nhap_main, 0); ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10 text-end"><?= number_format($row['qty'] * $price_nhap_main, 0); ?></p>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <?php
                                    $sql_ao = "SELECT * FROM xuat_detail WHERE is_it = 1 AND is_it_for ='" . $_GET['detailid'] . "'";
                                    $res_ao = mysqli_query($connect, $sql_ao);
                                    $count_ao = mysqli_num_rows($res_ao);
                                    if ($count_ao > 0) {
                                        while ($row_ao = mysqli_fetch_assoc($res_ao)) {
                                    ?>
                                            <tr>
                                                <td>
                                                    <p class="text-10"><?= $row_ao['product_id']; ?></p>
                                                </td>
                                                <td class="d-none d-md-table-cell">
                                                    <p class="text-10"><?= $row_ao['product_code']; ?></p>
                                                </td>
                                                <td>
                                                    <p class="text-10 fw-bold text-wrap"><?= $row_ao['product_name']; ?></p>
                                                </td>
                                                <td>
                                                    <p class="text-10"><?= $row_ao['unit']; ?></p>
                                                </td>
                                                <td>
                                                    <p class="text-10 text-end"><?= number_format($row_ao['qty'], 0); ?></p>
                                                </td>
                                                <td>
                                                    <p class="text-10 text-end"><?= number_format($row_ao['price'], 0); ?></p>
                                                </td>
                                                <td>
                                                    <p class="text-10 text-end"><?= number_format($row_ao['total_before_vat'], 0); ?></p>
                                                </td>
                                                <td class="align-middle">
                                                    <a class="btn btn-sm btn-link" href="./?view=edit-item&itemid=<?= $row_ao['id']; ?>"><span class="material-symbols-outlined text-14 text-dark">edit</span></a>
                                                    <a class="btn btn-sm btn-link" href="./?view=delete-item&itemid=<?= $row_ao['id']; ?>&xuatid=<?= $row['xuat_id']; ?>&productid=<?= $row_ao['product_id']; ?>"><span class="material-symbols-outlined text-14 text-danger">delete</span></a>
                                                </td>
                                            </tr>
                                    <?php }
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <a href="./?view=add-item-ao&xuatid=<?= $row['xuat_id']; ?>&isitfor=<?= $row['id']; ?>&period=<?= $period_current; ?>" class="btn btn-link btn-sm mb-2">
                                <p class="text-12">+ Thêm ảo</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>