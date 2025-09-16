    <?php include("../lib/nav.php"); ?>
    <?php
    // Month Variable
    $month = (isset($_GET['month'])) ? $_GET['month'] : date("Y-m");
    $month_no = date("m", strtotime($month));
    $month_year =  date("Y", strtotime($month));
    $prev_month = date("Y-m", strtotime("$month -1 month"));
    $next_month = date("Y-m", strtotime("$month +1 month"));
    $month_first_day = date('Y-m-01', strtotime($month));
    $month_last_day = date('Y-m-t', strtotime($month));

    // Status Variable
    $status = (isset($_GET['status'])) ? $_GET['status'] : "ALL";
    function show_status_badge($status)
    {
        switch ($status) {
            case 'WAIT':
                $status_text = "CHỜ";
                $status_bg = "danger";
                break;

            case 'SHIP':
                $status_text = "ĐANG GIAO";
                $status_bg = "primary";
                break;
            case 'COMPLETE':
                $status_text = "XONG";
                $status_bg = "success";
                break;
            case 'CANCEL':
                $status_text = "HỦY";
                $status_bg = "dark";
                break;
        }
        echo "<p class='btn btn-sm btn-" . $status_bg . " rounded-50 text-10 fw-bold'>" . $status_text . "</p>";
    }

    // Main Table SQL
    $sql = "SELECT * FROM orders WHERE 1";

    // SQL Filter Status
    if ($status == "ALL") {
        $sql_filter_status = "";
    } else {
        $sql_filter_status = " AND status = '" . $status . "'";
    }
    $sql .= $sql_filter_status;

    // SQL Filter Time
    $sql_filter_time = " AND DATE(ship_time) BETWEEN '" . $month_first_day . "' AND '" . $month_last_day . "'";
    $sql .= $sql_filter_time;

    // SQL Sort
    if (isset($_GET['object'])) {
        $sortobj = $_GET['object'];
    } else {
        $sortobj = "ship_time";
    }
    if (isset($_GET['sort'])) {
        if ($_GET['sort'] == "decrease") {
            $sortorder = "DESC";
        } else {
            $sortorder = "";
        }
    } else {
        $sortorder = "DESC";
    }
    $sql_sort = " ORDER BY " . $sortobj . " " . $sortorder;
    $sql .= $sql_sort;

    //Query
    $res = mysqli_query($connect, $sql);
    $count = mysqli_num_rows($res);

    // Count 5 status
    $sql_all = "SELECT * FROM orders WHERE 1" . $sql_filter_time . $sql_sort;
    $res_all = mysqli_query($connect, $sql_all);
    $count_all = mysqli_num_rows($res_all);

    $sql_wait = "SELECT * FROM orders WHERE 1 AND status = 'WAIT'" . $sql_filter_time . $sql_sort;
    $res_wait = mysqli_query($connect, $sql_wait);
    $count_wait = mysqli_num_rows($res_wait);

    $sql_ship = "SELECT * FROM orders WHERE 1 AND status = 'SHIP'" . $sql_filter_time . $sql_sort;
    $res_ship = mysqli_query($connect, $sql_ship);
    $count_ship = mysqli_num_rows($res_ship);

    $sql_complete = "SELECT * FROM orders WHERE 1 AND status = 'COMPLETE'" . $sql_filter_time . $sql_sort;
    $res_complete = mysqli_query($connect, $sql_complete);
    $count_complete = mysqli_num_rows($res_complete);

    $sql_cancel = "SELECT * FROM orders WHERE 1 AND status = 'CANCEL'" . $sql_filter_time . $sql_sort;
    $res_cancel = mysqli_query($connect, $sql_cancel);
    $count_cancel = mysqli_num_rows($res_cancel);
    ?>

    <!-- Head Section -->
    <div class="d-flex align-items-center justify-content-between p-3 bg-theme">
        <div class="d-flex align-items-center">
            <span class="material-icons me-2">shopping_cart</span>
            <a href="./">
                <p class="text-dark fw-bold my-3 text-20">Bán Hàng</p>
            </a>
        </div>
        <div class="d-grid gap-1">
            <a href="../voucher" class="btn btn-light btn-sm">
                <p>QL Voucher</p>
            </a>
            <a href="./?view=add" class="btn btn-dark btn-sm">
                <p>+ Tạo mới</p>
            </a>
        </div>
    </div>

    <!-- Select Month -->
    <div class="d-flex align-items-center justify-content-between p-3">
        <div>
        </div>
        <div class="d-flex align-items-center">
            <a href="./?month=<?= $prev_month; ?>" class="btn btn-sm"><span class="material-icons text-14 text-grey lh-base">arrow_back_ios</span></a>

            <input type="month" class="form-control my-2" value="<?= $month; ?>">
            <!-- <p class="mx-1 px-3 py-2 bg-white border border-grey text-13 rounded fw-bold"><?= $quarter; ?></p> -->

            <a href="./?month=<?= $next_month; ?>" class="btn btn-sm"><span class="material-icons text-14 text-grey lh-base">arrow_forward_ios</span></a>
        </div>

        <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="material-icons text-14 lh-base">sort</span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <a href="./?sort=decrease&object=total" class="dropdown-item">
                    <div class="d-flex align-items-center">
                        <span class="material-icons text-14 me-2 text-dark">south</span>
                        <p class="text-dark">Xếp tổng tiền giảm</p>
                    </div>
                </a>
            </li>
            <li>
                <a href="./?sort=increase&object=total" class="dropdown-item">
                    <div class="d-flex align-items-center">
                        <span class="material-icons text-14 me-2 text-dark">north</span>
                        <p class="text-dark">Xếp tổng tiền tăng</p>
                    </div>
                </a>
            </li>
        </ul>
    </div>

    <!-- Select Status -->
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                <div class="container mb-3">
                    <div class="row">
                        <div class="col-6 px-1">
                            <a href="./?month=<?= $month; ?>">
                                <div class="border <?= ($status == "ALL") ? "bg-grey" : ""; ?> rounded p-2" style="height: 100%;">
                                    <p class="text-30 fw-bold <?= ($status == "ALL") ? "text-dark" : "text-dark"; ?>"><?= $count_all; ?></p>
                                    <p class="text-12 fw-bold <?= ($status == "ALL") ? "text-dark" : "text-dark"; ?>">Đơn bán</p>
                                </div>
                            </a>

                        </div>
                        <div class="col-6 px-0">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-6 px-1 mb-1">
                                        <a href="./?month=<?= $month; ?>&status=WAIT">
                                            <div class="border <?= ($status == "WAIT") ? "bg-danger" : ""; ?> rounded px-2 py-0">
                                                <p class="text-14 fw-bold <?= ($status == "WAIT") ? "text-white" : "text-danger"; ?>"><?= $count_wait; ?></p>
                                                <p class="text-10 fw-bold <?= ($status == "WAIT") ? "text-white" : "text-danger"; ?>">CHỜ</p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6 px-1 mb-1">
                                        <a href="./?month=<?= $month; ?>&status=SHIP">
                                            <div class="border <?= ($status == "SHIP") ? "bg-primary" : ""; ?> rounded px-2 py-0">
                                                <p class="text-14 fw-bold <?= ($status == "SHIP") ? "text-white" : "text-primary"; ?>"><?= $count_ship; ?></p>
                                                <p class="text-10 fw-bold <?= ($status == "SHIP") ? "text-white" : "text-primary"; ?>">ĐANG GIAO</p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6 px-1">
                                        <a href="./?month=<?= $month; ?>&status=COMPLETE">
                                            <div class="border <?= ($status == "COMPLETE") ? "bg-success" : ""; ?> rounded px-2 py-0">
                                                <p class="text-14 fw-bold <?= ($status == "COMPLETE") ? "text-white" : "text-success"; ?>"><?= $count_complete; ?></p>
                                                <p class="text-10 fw-bold <?= ($status == "COMPLETE") ? "text-white" : "text-success"; ?>">XONG</p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-6 px-1">
                                        <a href="./?month=<?= $month; ?>&status=CANCEL">
                                            <div class="border <?= ($status == "CANCEL") ? "bg-secondary" : ""; ?> rounded px-2 py-0">
                                                <p class="text-14 fw-bold <?= ($status == "CANCEL") ? "text-white" : "text-secondary"; ?>"><?= $count_cancel; ?></p>
                                                <p class="text-10 fw-bold <?= ($status == "CANCEL") ? "text-white" : "text-secondary"; ?>">HỦY</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white vh-100">
        <div style="padding-bottom: 80px;">
            <!-- <div class="ms-2 d-flex justify-content-center gap-1">
                <a href="./?status=ALL&month=<?= $month; ?>" class="btn btn-sm btn<?= ($status == "ALL") ? "" : "-outline"; ?>-dark">
                    <div class="d-flex">
                        <span class="text-nowrap text-12">Tất cả</span>
                        <span class="badge text-white bg-dark"><?= $count_all; ?></span>
                    </div>
                </a>
                <a href="./?status=WAIT&month=<?= $month; ?>" class="btn btn-sm btn<?= ($status == "WAIT") ? "" : "-outline"; ?>-danger">
                    <div class="d-flex">
                        <span class="text-nowrap text-12">Chờ</span>
                        <span class="badge text-white bg-danger"><?= $count_wait; ?></span>
                    </div>
                </a>
                <a href="./?status=SHIP&month=<?= $month; ?>" class="btn btn-sm btn<?= ($status == "SHIP") ? "" : "-outline"; ?>-primary">
                    <div class="d-flex">
                        <span class="text-nowrap text-12">Đang&nbsp;Giao</span>
                        <span class="badge text-white bg-primary"><?= $count_ship; ?></span>
                    </div>
                </a>
                <a href="./?status=COMPLETE&month=<?= $month; ?>" class="btn btn-sm btn<?= ($status == "COMPLETE") ? "" : "-outline"; ?>-success">
                    <div class="d-flex">
                        <span class="text-nowrap text-12">Xong</span>
                        <span class="badge text-white bg-success"><?= $count_complete; ?></span>
                    </div>
                </a>
                <a href="./?status=CANCEL&month=<?= $month; ?>" class="btn btn-sm btn<?= ($status == "CANCEL") ? "" : "-outline"; ?>-secondary">
                    <div class="d-flex">
                        <span class="text-nowrap text-12">Hủy</span>
                        <span class="badge text-white bg-secondary"><?= $count_cancel; ?></span>
                    </div>
                </a>
            </div> -->

            <!-- Main Table -->
            <?php
            if ($count > 0) {
            ?>
                <div class="table-responsive mt-1">
                    <table class="table table-bordered table-hovered bg-white">
                        <thead class="table-secondary">
                            <th class="text-center">
                                <p>NGÀY</p>
                            </th>
                            <th class="d-none d-md-table-cell">
                                <p>NHÓM KHÁCH</p>
                            </th>
                            <th class="d-none d-md-table-cell">
                                <p>MÃ ĐƠN</p>
                            </th>
                            <th>
                                <p>KHÁCH</p>
                            </th>
                            <th class="text-end">
                                <p>THÀNH TIỀN</p>
                            </th>
                            <th>
                                <p>Status</p>
                            </th>
                            <th class="d-none d-md-table-cell">
                                <p>CHI TIẾT</p>
                            </th>
                            <th class="d-none d-md-table-cell">
                                <p>Shipper</p>
                            </th>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysqli_fetch_array($res)) {
                                $sql_line_no = "SELECT COUNT(id) AS line_no FROM orders_detail WHERE orders_id = '" . $row['id'] . "'";
                                $line_no = $connect->query($sql_line_no)->fetch_assoc()['line_no'] ?? null;
                                $sql_item_no = "SELECT SUM(qty) AS item_no FROM orders_detail WHERE orders_id = '" . $row['id'] . "'";
                                $item_no = $connect->query($sql_item_no)->fetch_assoc()['item_no'] ?? 0;
                            ?>
                                <tr style="cursor: pointer;" onclick="location.href='./?view=detail&id=<?= $row['id']; ?>'">
                                    <td class="text-center">
                                        <p class=" text-13 fw-bold"><?= date("d/m", strtotime($row['created'])); ?></p>
                                        <p class="text-12 text-secondary"><?= date("H:i", strtotime($row['created'])); ?></p>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <?php
                                        $sql_customer_group = "SELECT b.name as customer_group_name 
                                                    FROM customers a 
                                                    LEFT JOIN customers_group b 
                                                    ON a.customer_group_id = b.id 
                                                    WHERE a.id ='" . $row['customer_id'] . "'";
                                        $res_customer_group = mysqli_query($connect, $sql_customer_group);
                                        while ($row_customer_group = mysqli_fetch_assoc($res_customer_group)) {
                                        ?>
                                            <p class="badge rounded-pill border text-dark"><?= $row_customer_group['customer_group_name']; ?></p>
                                        <?php } ?>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons text-dark text-14 me-2 lh-base">shopping_cart</span>
                                            <div>
                                                <p class="text-13 text-danger fw-bold">Đơn bán #<?= $row['id']; ?></p>
                                                <p class="text-12"><?= $line_no . " sp, " . $item_no . " kg"; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        $sql_customer = "SELECT * FROM customers WHERE id ='" . $row['customer_id'] . "'";
                                        $res_customer = mysqli_query($connect, $sql_customer);
                                        while ($row_customer = mysqli_fetch_assoc($res_customer)) {
                                        ?>

                                            <div class="d-flex align-items-center d-md-none">
                                                <span class="material-icons text-danger text-14 me-2 lh-base">shopping_cart</span>
                                                <p class="text-12 text-danger fw-bold">Đơn #<?= $row['id']; ?></p>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="material-icons text-dark text-14 me-2 lh-base">account_circle</span>
                                                <p class="text-12 fw-bold"><?= $row_customer['name']; ?></p>
                                            </div>
                                        <?php } ?>
                                    </td>
                                    <td class="text-end">
                                        <p class="text-success fw-bold"><?= number_format($row['total'], 0); ?></p>
                                    </td>
                                    <td>
                                        <?= show_status_badge($row['status']); ?>
                                        <p class="btn btn-sm btn-<?= $row['status']; ?>"></p>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <?php
                                        $sql_line = "SELECT * FROM orders_detail 
                                            LEFT JOIN products 
                                            ON orders_detail.product_id = products.id 
                                            WHERE orders_detail.orders_id ='" . $row['id'] . "'";
                                        $res_line = mysqli_query($connect, $sql_line);
                                        while ($row_line = mysqli_fetch_assoc($res_line)) {
                                        ?>
                                            <p><b><?= $row_line['qty'] . "</b> x " . $row_line['name']; ?></p>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <div class="d-flex align-items-center">
                                            <?php
                                            if (empty($row['shipper_id'])) {
                                            ?>
                                                <p class="text-secondary">Chưa gán</p>
                                                <?php
                                            } else {
                                                $sql_shipper = "SELECT * FROM ops_user WHERE id ='" . $row['shipper_id'] . "'";
                                                $res_shipper = mysqli_query($connect, $sql_shipper);
                                                while ($row_shipper = mysqli_fetch_assoc($res_shipper)) {
                                                ?>
                                                    <span class="material-icons me-1 text-16">local_shipping</span>
                                                    <p class="fw-bold text-12"><?= $row_shipper['name']; ?></p>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php
            } else {
            ?>
                <p class="text-center text-12 my-4">0 đơn hàng</p>
            <?php
            }
            ?>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script>
        $('input[type="month"]').on('change', function() {
            window.location = "./?status=<?= $status; ?>&month=" + $(this).val();
        });
    </script>