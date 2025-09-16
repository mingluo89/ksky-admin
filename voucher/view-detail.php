<?php
$sql_order = "SELECT * FROM orders_voucher WHERE vouchers_id ='" . $_GET['id'] . "' ORDER BY created DESC";
$res_order = mysqli_query($connect, $sql_order);
$redeem_order = mysqli_num_rows($res_order);

$sql_redeem_amount = "SELECT SUM(value) as sum FROM orders_voucher WHERE vouchers_id ='" . $_GET['id'] . "'";
$res_redeem_amount = mysqli_query($connect, $sql_redeem_amount);
$count_redeem_amount = mysqli_num_rows($res_redeem_amount);
if ($count_redeem_amount == 0) {
    $redeem_amount = 0;
} else {
    while ($row_redeem_amount = mysqli_fetch_array($res_redeem_amount)) {
        $redeem_amount = $row_redeem_amount['sum'];
    }
}

$sql_redeem_customer = "SELECT DISTINCT(`orders`.`customer_id`) FROM `orders_voucher` LEFT JOIN `orders` ON `orders_voucher`.`orders_id`=`orders`.`id` WHERE `orders_voucher`.`vouchers_id`='" . $_GET['id'] . "';";
$res_redeem_customer = mysqli_query($connect, $sql_redeem_customer);
$redeem_customer = mysqli_num_rows($res_redeem_customer);

$sql = "SELECT * FROM vouchers WHERE id ='" . $_GET['id'] . "'";
$res = mysqli_query($connect, $sql);
$count = mysqli_num_rows($res);
if ($count > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
?>
        <div class="d-flex align-items-center justify-content-between py-3 bg-white">
            <a href="./" class="btn"><span class="material-icons text-20 text-dark">arrow_back_ios</span></a>
            <div class="d-flex align-items-center justify-content-between">
                <span class="material-icons text-30 me-3">account_circle</span>
                <div class="me-3">
                    <p class="fw-bold text-16"><?= $row['name']; ?></p>
                </div>
            </div>
            <div></div>
            <!-- <a href="./?view=delete&id=<?= $row['id']; ?>" class="btn btn-outline-danger me-2">
                <p class="text-14">Xóa</p>
            </a> -->
        </div>
        <div class="bg-theme row mx-0">
            <div class="col-12 col-md-4 order-1 order-md-1">
                <!-- Thông Tin -->
                <div class="d-flex align-items-center justify-content-between mt-3">
                    <p class="fw-bold text-white">Thông tin</p>
                    <a href="./?view=edit&id=<?= $row['id']; ?>" class="btn btn-sm">
                        <span class="material-icons text-white text-16">edit</span>
                    </a>
                </div>
                <div class="rounded-20 bg-white mt-1 mb-3 p-3">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <p class="fw-bold text-12 text-dark">ID</p>
                            <p>#<?= $row['id']; ?></p>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <p class="fw-bold text-12 text-dark">Tên</p>
                            <p><?= $row['name']; ?></p>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <p class="fw-bold text-12 text-dark">Mã</p>
                            <p class="btn btn-success btn-sm"><?= $row['code']; ?></p>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <p class="fw-bold text-12 text-dark">Giảm</p>
                            <p class="fw-bold"><?= $row['discount_value'] . "% " . $row['discount_object']; ?></p>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <p class="fw-bold text-12 text-dark">Tối đa</p>
                            <p class="fw-bold"><?= number_format($row['discount_cap'], 0); ?> đ</p>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <p class="fw-bold text-12 text-dark">Số lượng</p>
                            <p class="btn btn-sm btn-outline-secondary"><?= number_format($row['stock'], 0); ?> mã</p>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <p class="fw-bold text-12 text-dark">Còn lại</p>
                            <p class="text-danger fw-bold"><?= number_format($row['stock'] - $redeem_order, 0); ?> mã</p>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <p class="fw-bold text-12 text-dark">Bắt đầu</p>
                            <p class="text-secondary"><?= $row['start_date']; ?></p>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <p class="fw-bold text-12 text-dark">Kết thúc</p>
                            <p class="text-secondary"><?= $row['end_date']; ?></p>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <p class="fw-bold text-12 text-dark">Status</p>
                            <?php if ($row['status'] == "ACTIVE") { ?>
                                <p class="text-success fw-bold"><?= $row['status']; ?></p>
                            <?php } else { ?>
                                <p class="text-secondary fw-bold"><?= $row['status']; ?></p>
                            <?php } ?>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-12 col-md-8 order-2 order-md-2">
                <!-- Redeem -->
                <p class="fw-bold text-white mb-2 mt-3">Đơn hàng đã sử dụng</p>
                <div class="rounded-20 bg-white px-3 mb-3" style="padding-bottom: 20px; padding-top: 20px;">

                    <div class="row">

                        <div class="row mx-0 pt-3">
                            <div class="col-3 mb-3">
                                <div class="p-3 border rounded">
                                    <p class="text-20 fw-bold"><?= number_format($redeem_order, 0); ?></p>
                                    <p class="text-12 text-secondary">đơn</p>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="p-3 border rounded">
                                    <p class="text-20 fw-bold"><?= number_format($redeem_amount, 0); ?> đ</p>
                                    <p class="text-12 text-secondary">đã giảm</p>
                                </div>
                            </div>
                            <div class="col-3 mb-3">
                                <div class="p-3 border rounded">
                                    <p class="text-20 fw-bold"><?= number_format($redeem_customer, 0); ?></p>
                                    <p class="text-12 text-secondary">khách</p>
                                </div>
                            </div>
                        </div>
                        <?php
                        if ($redeem_order > 0) {
                        ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hovered">
                                    <thead>
                                        <th class="text-center">NGÀY</th>
                                        <th class="text-center">MÃ ĐH</th>
                                        <th class="text-end">SỐ TIỀN</th>
                                        <th class="">KHÁCH HÀNG</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row_order = mysqli_fetch_array($res_order)) {
                                        ?>
                                            <tr style="cursor: pointer;" onclick="location.href='../banhang/?view=detail&id=<?= $row_order['orders_id']; ?>'">
                                                <td class="align-middle text-center">
                                                    <p><?= $row_order['created']; ?></p>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <p class="fw-bold">#<?= $row_order['orders_id']; ?></p>
                                                </td>
                                                <td class="text-end align-middle">
                                                    <p class="fw-bold text-success"><?= number_format($row_order['value'], 0); ?> đ</p>
                                                </td>
                                                <?php
                                                $sql_customer = "SELECT `customers`.`name` as name FROM `orders` LEFT JOIN `customers` ON `orders`.`customer_id`=`customers`.`id` WHERE `orders`.`id` = '" . $row_order['orders_id'] . "'";
                                                $res_customer = mysqli_query($connect, $sql_customer);
                                                $count_customer = mysqli_num_rows($res_customer);
                                                while ($row_customer = mysqli_fetch_array($res_customer)) {
                                                ?>
                                                    <td>
                                                        <p class="fw-bold"><?= $row_customer['name']; ?></p>
                                                    </td>
                                                <?php
                                                }
                                                ?>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                        } else {
                        ?>
                            <p class="text-center">Không có đơn hàng</p>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="gpsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="do.php" method="post">
                            <input type="hidden" name="action" value="edit-gps">
                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="gpsModalLabel">Ghim GPS</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-12 text-secondary my-2">Di chuyển điểm ghim đến tọa độ giao hàng của khách</p>
                                <div id="map" class="mb-3" style="width:100%; height:300px"></div>
                                <div class="row">
                                    <div class="col-6">
                                        <p class="text-12 text-secondary my-2">Lat</p>
                                        <input class="form-control" type="text" name="latitude" id="latitude" value="<?= $row['latitude']; ?>" required>
                                    </div>
                                    <div class="col-6">
                                        <p class="text-12 text-secondary my-2">Long</p>
                                        <input class="form-control" type="text" name="longitude" id="longitude" value="<?= $row['longitude']; ?>" required>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success btn-sm">Xác nhận</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php
    }
} else {
        ?>
        <p class="text-center">Không tìm thấy khách hàng #<?= $_GET['id']; ?></p>
    <?php
}
    ?>