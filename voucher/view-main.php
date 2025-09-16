    <?php include("../lib/nav.php"); ?>
    <?php
    $sql = "SELECT * FROM vouchers ORDER BY start_date DESC";
    $res = mysqli_query($connect, $sql);
    $count = mysqli_num_rows($res);

    ?>
    <div>
        <div class="px-3 py-5">
            <div class="row">
                <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="text-white fw-bold text-30">Mã giảm giá</p>
                        <a href="./?view=add" class="btn btn-sm btn-light">
                            <p>Thêm</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="rounded-top-30 bg-white vh-100">
        <?php
        $sql_redeem = "SELECT SUM(value) as redeem_value, COUNT(DISTINCT(vouchers_id)) as redeem_qty, COUNT(id) as redeem_order FROM orders_voucher";
        $res_redeem = mysqli_query($connect, $sql_redeem);
        while ($row_redeem = mysqli_fetch_array($res_redeem)) {
            $count_redeem_qty = $row_redeem['redeem_qty'];
            $count_redeem_order = $row_redeem['redeem_order'];
            $count_redeem_value = $row_redeem['redeem_value'];
        }
        ?>
        <div class="row mx-0 pt-3">
            <div class="col-6 col-md-3 col-xl-2 offset-xl-2 mb-3">
                <div class="p-3 border rounded">
                    <p class="text-20 fw-bold"><?= number_format($count, 0); ?></p>
                    <p class="text-12 text-secondary">mã</p>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl-2 mb-3">
                <div class="p-3 border rounded">
                    <p class="text-20 fw-bold"><?= number_format($count_redeem_qty, 0); ?></p>
                    <p class="text-12 text-secondary">mã đã xài</p>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl-2 mb-3">
                <div class="p-3 border rounded">
                    <p class="text-20 fw-bold"><?= number_format($count_redeem_order, 0); ?></p>
                    <p class="text-12 text-secondary">đơn hàng</p>
                </div>
            </div>
            <div class="col-6 col-md-3 col-xl-2 mb-3">
                <div class="p-3 border rounded">
                    <p class="text-20 fw-bold"><?= number_format($count_redeem_value, 0); ?> đ</p>
                    <p class="text-12 text-secondary">đã giảm</p>
                </div>
            </div>
            <div class="col-12 mb-3" style="padding-bottom: 80px;">
                <div class="table-responsive">
                    <table class="table table-striped dtTable">
                        <thead>
                            <th>
                                <p class="text-center">ID</p>
                            </th>
                            <th>
                                <p>Tên</p>
                            </th>
                            <th>
                                <p>Mã</p>
                            </th>
                            <th>
                                <p class="text-end">Giảm</p>
                            </th>
                            <th>
                                <p>Trên</p>
                            </th>
                            <th>
                                <p class="text-end">Tối đa</p>
                            </th>
                            <th>
                                <p class="text-center">Bắt đầu</p>
                            </th>
                            <th>
                                <p class="text-center">Kết thúc</p>
                            </th>
                            <th>
                                <p class="text-end">Đơn hàng</p>
                            </th>
                            <th>
                                <p class="text-end">Giá trị giảm</p>
                            </th>
                            <th>
                                <p class="text-end">Khách hàng</p>
                            </th>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_array($res)) {
                            ?>
                                <tr style="cursor:pointer" onclick="location.href='./?view=detail&id=<?= $row['id']; ?>'">
                                    <td>
                                        <p class="text-center">#<?= $row['id']; ?></p>
                                    </td>
                                    <td>
                                        <p class="fw-bold"><?= $row['name']; ?></p>
                                    </td>
                                    <td>
                                        <p class="btn btn-success"><?= $row['code']; ?></p>
                                    </td>
                                    <td>
                                        <p class="fw-bold text-end"><?= $row['discount_value']; ?>%</p>
                                    </td>
                                    <td>
                                        <p><?= $row['discount_object']; ?></p>
                                    </td>
                                    <td>
                                        <p class="text-end"><?= number_format($row['discount_cap'], 0); ?> đ</p>
                                    </td>
                                    <td>
                                        <p class="text-center"><?= date("Y-m-d", strtotime($row['start_date'])); ?></p>
                                    </td>
                                    <td>
                                        <p class="text-center"><?= date("Y-m-d", strtotime($row['end_date'])); ?></p>
                                    </td>

                                    <?php
                                    $sql_redeem_order = "SELECT * FROM orders_voucher WHERE vouchers_id ='" . $row['id'] . "'";
                                    $res_redeem_order = mysqli_query($connect, $sql_redeem_order);
                                    $redeem_order = mysqli_num_rows($res_redeem_order);

                                    $sql_redeem_amount = "SELECT SUM(value) as sum FROM orders_voucher WHERE vouchers_id ='" . $row['id'] . "'";
                                    $res_redeem_amount = mysqli_query($connect, $sql_redeem_amount);
                                    $count_redeem_amount = mysqli_num_rows($res_redeem_amount);
                                    if ($count_redeem_amount == 0) {
                                        $redeem_amount = 0;
                                    } else {
                                        while ($row_redeem_amount = mysqli_fetch_array($res_redeem_amount)) {
                                            $redeem_amount = $row_redeem_amount['sum'];
                                        }
                                    }

                                    $sql_redeem_customer = "SELECT DISTINCT(`orders`.`customer_id`) FROM `orders_voucher` LEFT JOIN `orders` ON `orders_voucher`.`orders_id`=`orders`.`id` WHERE `orders_voucher`.`vouchers_id`='" . $row['id'] . "';";
                                    $res_redeem_customer = mysqli_query($connect, $sql_redeem_customer);
                                    $redeem_customer = mysqli_num_rows($res_redeem_customer);
                                    ?>

                                    <td>
                                        <p class="text-end fw-bold"><?= number_format($redeem_order, 0); ?></p>
                                    </td>
                                    <td>
                                        <p class="text-end fw-bold"><?= number_format($redeem_amount, 0); ?> đ</p>
                                    </td>
                                    <td>
                                        <p class="text-end fw-bold"><?= number_format($redeem_customer, 0); ?></p>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/v/dt/dt-1.13.6/datatables.min.js"></script>

    <script>
        new DataTable('.dtTable', {
            "lengthChange": false,
            "lengthMenu": [50, 100, "All"],
            "searching": false,
            "bInfo": false,
            "order": []
        });
    </script>