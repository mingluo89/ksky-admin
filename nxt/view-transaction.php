<?php

$pid = (int)($_GET['productid'] ?? 0);
if ($pid <= 0) json_err('invalid product_id');

// lấy start_qty, start_value
$rowP = mysqli_fetch_assoc(mysqli_query($connect, "SELECT product_name, start_period, start_qty, start_value FROM products WHERE id=$pid"));
$product_name = $rowP['product_name'];
$start_period = $rowP['start_period'];
$start_qty = (int)($rowP['start_qty'] ?? 0);
$start_value = (int)($rowP['start_value'] ?? 0);

// gom nhập
$sqlN = "SELECT accounting_date, qty, total_before_vat, nhap_id AS code, 'nhap' AS type
             FROM nhap_detail
             WHERE product_id=$pid";

// gom xuất
$sqlX = "SELECT accounting_date, qty, total_before_vat, xuat_id AS code, 'xuat' AS type
             FROM xuat_detail
             WHERE product_id=$pid";

// UNION ALL, order by date
$sql = "($sqlN) UNION ALL ($sqlX) ORDER BY accounting_date ASC";
$res = mysqli_query($connect, $sql);
?>

<div class="container-fluid bg-blue-gra vh-100 overflow-y-auto">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="d-flex align-items-center justify-content-between p-3">
                <a href="/nxt" class="btn btn-sm">
                    <span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span>
                </a>
                <div class="d-flex align-items-center">
                    <p class="fw-bold text-14">Liệt kê Nhập Xuất</p>
                </div>
                <div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <p class="my-3"><?= "[" . $pid . "] " . $product_name; ?></p>
            <div class="table-responsive vh-100">
                <table class="table table-sm table-bordered table-hovered bg-white">
                    <thead class="table-secondary">
                        <th>
                            <p class="text-10">Loại</p>
                        </th>
                        <th>
                            <p class="text-10">Đơn</p>
                        </th>
                        <th>
                            <p class="text-10">Ngày</p>
                        </th>
                        <th>
                            <p class="text-10">SL</p>
                        </th>
                        <th>
                            <p class="text-10">Tồn</p>
                        </th>
                        <th>
                            <p class="text-10">TT</p>
                        </th>
                        <th>
                            <p class="text-10">TTTL</p>
                        </th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <p>Khởi tạo</p>
                            </td>
                            <td></td>
                            <td>
                                <p><?= $start_period; ?></p>
                            </td>
                            <td>
                                <p class="fw-bold text-dark text-end"><?= number_format($start_qty, 0); ?></p>
                            </td>
                            <td>
                                <p class="fw-bold text-dark text-end"><?= number_format($start_qty, 0); ?></p>
                            </td>
                            <td>
                                <p class="fw-bold text-dark text-end"><?= ($start_value == 0) ? "" : "-"; ?><?= number_format($start_value, 0); ?></p>
                            </td>
                            <td>
                                <p class="fw-bold text-dark text-end"><?= ($start_value == 0) ? "" : "-"; ?><?= number_format($start_value, 0); ?></p>
                            </td>

                        </tr>
                        <?php
                        $acc_qty = $start_qty;
                        $acc_value = -$start_value;
                        while ($r = mysqli_fetch_assoc($res)) {
                            $sign = ($r['type'] == "nhap") ? 1 : -1;
                            $acc_qty += $sign * $r['qty'];
                            $acc_value -= $sign * $r['total_before_vat'];
                        ?>
                            <tr>
                                <td>
                                    <a href="/<?= $r['type']; ?>hang/?view=detail&id=<?= $r['code']; ?>">
                                        <p><?= ($r['type'] == "nhap") ? "Nhập" : "Xuất"; ?></p>
                                    </a>
                                </td>
                                <td>
                                    <a href="/<?= $r['type']; ?>hang/?view=detail&id=<?= $r['code']; ?>">
                                        <p>#<?= $r['code']; ?></p>
                                    </a>
                                </td>
                                <td>
                                    <p><?= $r['accounting_date']; ?></p>
                                </td>
                                <td>
                                    <?php if ($sign > 0) { ?>
                                        <p class="fw-bold text-success text-end">+ <?= number_format($r['qty'], 0); ?></p>
                                    <?php } else { ?>
                                        <p class="fw-bold text-danger text-end">- <?= number_format($r['qty'], 0); ?></p>
                                    <?php } ?>
                                </td>
                                <td>
                                    <p class="fw-bold text-end"><?= number_format($acc_qty, 0); ?></p>
                                </td>
                                <td>
                                    <?php if ($sign > 0) { ?>
                                        <p class="fw-bold text-danger text-end">- <?= number_format($r['total_before_vat'], 0); ?></p>
                                    <?php } else { ?>
                                        <p class="fw-bold text-success text-end">+ <?= number_format($r['total_before_vat'], 0); ?></p>
                                    <?php } ?>
                                </td>
                                <td>
                                    <p class="fw-bold text-end"><?= number_format($acc_value, 0); ?></p>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>