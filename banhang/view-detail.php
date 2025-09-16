<?php
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

function show_status_card($status)
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
?>
    <div class="rounded border shadow-gg rounded mb-3 p-3 py-2 bg-<?= $status_bg; ?> text-center">
        <p class="text-12 text-white">TRẠNG THÁI</p>
        <p class="text-18 text-white"><?= $status_text; ?></p>
    </div>
    <?php
}

$sql1 = "SELECT * FROM orders WHERE id ='" . $_GET['id'] . "'";
$res1 = mysqli_query($connect, $sql1);
$count1 = mysqli_num_rows($res1);
if ($count1 > 0) {
    while ($row1 = mysqli_fetch_array($res1)) {
        if (empty($row1['discount_option'])) {
            $discount_option = "absolute";
        } else {
            $discount_option = $row1['discount_option'];
        }
        switch ($row1['status']) {
            case 'WAIT':
                $icon_status_kho = "order-status-kho-progress";
                $icon_status_ship = "order-status-ship-inactive";
                $icon_status_finish = "order-status-done-inactive";
                $icon_status_kho_caption = "Đang ở kho";
                $icon_status_ship_caption = "";
                $icon_status_finish_caption = "";
                break;
            case 'SHIP':
                $icon_status_kho = "order-status-kho-active";
                $icon_status_ship = "order-status-ship-progress";
                $icon_status_finish = "order-status-done-inactive";
                $icon_status_kho_caption = "Đã xuất kho";
                $icon_status_ship_caption = "Đang ship";
                $icon_status_finish_caption = "";
                break;
            case 'COMPLETE':
                $icon_status_kho = "order-status-kho-active";
                $icon_status_ship = "order-status-ship-active";
                $icon_status_finish = "order-status-done-active";
                $icon_status_kho_caption = "Đã xuất kho";
                $icon_status_ship_caption = "Đã ship";
                $icon_status_finish_caption = "Giao xong";
                break;
            case 'CANCEL':
                $icon_status_kho = "order-status-kho-active";
                $icon_status_ship = "order-status-ship-active";
                $icon_status_finish = "order-status-cancel-active";
                $icon_status_kho_caption = "Đã xuất kho";
                $icon_status_ship_caption = "";
                $icon_status_finish_caption = "Hủy";
                break;
        }
    ?>
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center justify-content-between py-3">
                    <a href="./" class="btn btn-sm border bg-white me-2"><span class="material-icons text-14 lh-base">arrow_back_ios</span></a>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="material-icons text-20 me-2">shopping_cart</span>
                        <p class="fw-bold text-14">Đơn Bán #<?= $row1['id']; ?></p>
                    </div>
                </div>
                <div class="d-flex align-items-center">

                    <a type="button" onclick="PrintPreview()" class="btn btn-sm btn-outline-success me-2">
                        <span class="material-icons text-14">print</span>
                    </a>

                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="material-icons text-14">more_vert</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="./?view=delete&id=<?= $row1['id']; ?>" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <span class="material-icons text-14 me-2 text-danger">delete</span>
                                    <p class="text-danger">Xóa đơn</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="./?view=cancel&id=<?= $row1['id']; ?>" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <span class="material-icons text-14 me-2 text-dark">cancel</span>
                                    <p class="text-dark">Hủy đơn</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row px-0">
                <!-- Left -->
                <div class="col-12 col-md-8 offset-md-2 col-xl-4 offset-xl-0">
                    <!-- Thông tin cơ bản -->
                    <div class="rounded border shadow-gg rounded mb-3 p-3 py-2 bg-white">
                        <ul class="nav nav-underline">
                            <li class="nav-item">
                                <button class="nav-link text-grey active" id="underline-info-tab" data-bs-toggle="pill" data-bs-target="#underline-info" type="button" role="tab" aria-controls="underline-info" aria-selected="true">
                                    <p class="text-12">THÔNG TIN</p>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-grey" id="underline-payment-tab" data-bs-toggle="pill" data-bs-target="#underline-payment" type="button" role="tab" aria-controls="underline-payment" aria-selected="false">
                                    <p class="text-12">THANH TOÁN</p>
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="underline-tabContent">
                            <!-- Khách hàng -->
                            <div class="tab-pane fade show active" id="underline-info" role="tabpanel" aria-labelledby="underline-info-tab" tabindex="0">
                                <table class="table table-borderless table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <p class="text-secondary mb-1">Ngày tạo</p>
                                            </td>
                                            <td>
                                                <p class="fw-bold"><?= date("Y-m-d", strtotime($row1['log_time'])); ?> <span class="text-secondary"><?= date("H:i", strtotime($row1['log_time'])); ?></span></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>

                                                <p class="text-secondary mb-1">Người tạo</p>
                                            </td>
                                            <td>
                                                <?php
                                                $sql_employee = "SELECT * FROM ops_user WHERE id ='" . $row1['employee_id'] . "'";
                                                $res_employee = mysqli_query($connect, $sql_employee);
                                                while ($row_employee = mysqli_fetch_assoc($res_employee)) {
                                                ?>
                                                    <p class=""><?= $row_employee['name']; ?></p>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Thanh toán -->
                            <div class="tab-pane fade" id="underline-payment" role="tabpanel" aria-labelledby="underline-payment-tab" tabindex="0">
                                <?php
                                $remain = $row1['total'];
                                $sql_payment = "SELECT * FROM orders_payment WHERE orders_id ='" . $_GET['id'] . "'";
                                $res_payment = mysqli_query($connect, $sql_payment);
                                $count_payment = mysqli_num_rows($res_payment);
                                if ($count_payment > 0) {
                                ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hovered">
                                            <thead>
                                                <th class="text-center">Ngày</th>
                                                <th class="text-end">Số tiền</th>
                                                <th class="text-center">Phương thức</th>
                                                <th class="text-center"></th>
                                            </thead>
                                            <tbody>
                                                <?php
                                                while ($row_payment = mysqli_fetch_array($res_payment)) {
                                                    $remain -= $row_payment['amount'];
                                                ?>
                                                    <tr>
                                                        <td class="text-center align-middle">
                                                            <p class="fw-bold"><?= date("d/m", strtotime($row_payment['log_time'])); ?></p>
                                                            <p class="text-grey"><?= date("H:i", strtotime($row_payment['log_time'])); ?></p>
                                                        </td>
                                                        <td class="text-end align-middle">
                                                            <p class="fw-bold text-success"><?= number_format($row_payment['amount'], 0); ?></p>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <p><?= $row_payment['method']; ?></p>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <a href="./?view=edit-payment&orderid=<?= $_GET['id']; ?>&paymentid=<?= $row_payment['id']; ?>"><span class="material-icons text-14 me-2">edit</span></a>
                                                            <a href="./?view=delete-payment&orderid=<?= $_GET['id']; ?>&paymentid=<?= $row_payment['id']; ?>&method=<?= $row_payment['method']; ?>&amount=<?= $row_payment['amount']; ?>"><span class="material-icons text-14 me-2 text-danger">delete</span></a>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php
                                }
                                ?>
                                <div class="text-center">
                                    <a href="./?view=add-payment&orderid=<?= $row1['id']; ?>" class="btn btn-link btn-sm mb-1 mt-2">
                                        <p>+ Thêm khoản thanh toán</p>
                                    </a>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <p class="fw-bold">CÒN LẠI</p>
                                    <div>
                                        <p class="text-end fw-bold text-success mb-1"><?= number_format($remain, 0); ?></p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Địa chỉ -->
                    <div class="rounded border shadow-gg rounded mb-3 p-3 py-2 bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="fw-bold">ĐỊA CHỈ</p>
                        </div>

                        <table class="table table-borderless table-sm mb-0">
                            <tbody>
                                <?php
                                $sql_customer = "SELECT * FROM customers a 
                                    LEFT JOIN customers_group b 
                                    ON a.customer_group_id = b.id 
                                    WHERE a.id ='" . $row1['customer_id'] . "'";
                                $res_customer = mysqli_query($connect, $sql_customer);
                                while ($row_customer = mysqli_fetch_array($res_customer)) {
                                    $group_id = $row_customer['customer_group_id'];
                                    $customer_name = $row_customer[2];
                                    $customer_phone = $row_customer[3];
                                ?>
                                    <tr>
                                        <td>
                                            <p class="text-secondary mb-1">Khách hàng</p>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <a target="_blank" href=" ../customers/?view=detail&id=<?= $row_customer[0]; ?>">
                                                    <p class="text-12 fw-bold"><?= $row_customer[2]; ?></p>
                                                </a>

                                                <a href="tel:<?= $row_customer[3]; ?>" class="btn btn-outline-primary btn-sm rounded-20">
                                                    <span class="material-icons text-14">phone</span>
                                                </a>

                                                <a href="./?view=edit&id=<?= $row1['id']; ?>" class="btn btn-sm">
                                                    <span class="material-icons text-14">edit</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="text-secondary mb-1">Nguồn khách</p>
                                        </td>
                                        <td>
                                            <p class="btn btn-sm btn-outline-secondary"><?= $row_customer[14]; ?></p>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td class="align-top">
                                        <p class="text-secondary mb-1">Địa chỉ</p>
                                    </td>
                                    <td>
                                        <?php
                                        if (empty($row1['street'])) {
                                        ?>
                                            <p>Chưa nhập địa chỉ giao</p>
                                        <?php
                                        } else {
                                        ?>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <p class="text-wrap text-12 fw-bold"><?= $row1['address'] . " " . $row1['street']; ?></p>
                                                    <p class="text-wrap text-12 text-secondary"><?= $row1['ward'] . ", " . $row1['district']; ?></p>
                                                    <p class="text-wrap text-12 text-success"><b><?= $row1['city']; ?></b></p>
                                                </div>

                                                <a target="_blank" href="https://www.google.com/maps/search/?api=1&query=<?= $row1['address']; ?> <?= $row1['street']; ?>,<?= $row1['ward']; ?>,<?= $row1['district']; ?>" class="btn btn-outline-primary btn-sm rounded-20">
                                                    <span class="material-icons text-14">map</span>
                                                </a>

                                                <a href="./?view=edit-address&id=<?= $row1['id']; ?>" class="btn btn-sm">
                                                    <span class="material-icons text-14">edit</span>
                                                </a>
                                            </div>

                                        <?php } ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="text-secondary mb-1">Giao trước</p>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center justify-content-between">

                                            <p class="fw-bold"><?= date("Y-m-d", strtotime($row1['ship_time'])); ?> <span class="text-secondary"><?= date("H:i", strtotime($row1['ship_time'])); ?></span></p>

                                            <a href="./?view=edit-ship-time&id=<?= $row1['id']; ?>" class="btn btn-sm">
                                                <span class="material-icons text-14">edit</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Status -->
                    <?= show_status_card($row1['status']); ?>

                    <!-- Status Log -->
                    <div class="rounded border shadow-gg rounded mb-3 px-3 py-2 bg-white">
                        <div class="d-flex align-items-center justify-content-between my-2">
                            <p class="fw-bold">XỬ LÝ</p>
                        </div>
                        <div class="row pb-2 mb-0">
                            <div class="col-4 px-0">
                                <img src="../img/order-status/<?= $icon_status_kho; ?>.svg" width="100%" alt="Status Kho">
                                <p class="text-12 fw-bold text-center mt-0 mb-1"><?= $icon_status_kho_caption; ?></p>
                            </div>
                            <div class="col-4 px-0">
                                <img src="../img/order-status/<?= $icon_status_ship; ?>.svg" width="100%" alt="Status Ship">
                                <p class="text-12 fw-bold text-center mt-0 mb-1"><?= $icon_status_ship_caption; ?></p>
                            </div>
                            <div class="col-4 px-0">
                                <img src="../img/order-status/<?= $icon_status_finish; ?>.svg" width="100%" alt="Status Finish">
                                <p class="text-12 fw-bold text-center mt-0 mb-1"><?= $icon_status_finish_caption; ?></p>
                            </div>
                        </div>

                        <!-- Button Show More -->
                        <div class="text-center">
                            <button type="button" class="btn btn-link" id="logShowMore">
                                <p>Xem log</p>
                            </button>
                        </div>

                        <!-- Log -->
                        <div id="logDetail" class="border-top" style="display:none">
                            <table class="table table-hovered">
                                <tbody>
                                    <!-- Log Action -->
                                    <tr>
                                        <td colspan="2" class="bg-light">
                                            <p class="fw-bold mb-2 text-20">Kho</p>
                                        </td>
                                    </tr>
                                    <?php
                                    $sql_pxk = "SELECT * FROM xuat WHERE order_id ='" . $_GET['id'] . "'";
                                    $res_pxk = mysqli_query($connect, $sql_pxk);
                                    $count_pxk = mysqli_num_rows($res_pxk);
                                    if ($count_pxk == 0) {
                                    ?>
                                        <tr>
                                            <td class="text-end border-end">
                                                <span class="material-icons text-center text-20 text-grey">pending</span>
                                            </td>
                                            <td class="">
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#donghangModal">
                                                    <p>Đóng hàng</p>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-end border-end">
                                                <span class="material-icons text-center text-20 text-grey">pending</span>
                                            </td>
                                            <td class="">
                                                <p class="text-12 text-grey fst-italic">Chờ đóng hàng</p>
                                            </td>
                                        </tr>
                                        <?php
                                    } else {
                                        while ($row_pxk = mysqli_fetch_array($res_pxk)) {
                                        ?>
                                            <tr>
                                                <td class="text-end border-end">
                                                    <span class="material-icons text-center text-20 text-success">check_circle</span>
                                                </td>
                                                <td class="">
                                                    <p class="fw-bold text-12">Tạo phiếu xuất kho #<?= $row_pxk['id']; ?> - Kho <?= $row_pxk['hub_id']; ?></p>
                                                    <?php
                                                    $sql_nvkho = "SELECT * FROM ops_user WHERE id = '" . $row_pxk['employee_id'] . "'";
                                                    $res_nvkho = mysqli_query($connect, $sql_nvkho);
                                                    while ($row_nvkho = mysqli_fetch_array($res_nvkho)) {
                                                    ?>
                                                        <p class="text-primary text-12"><?= $row_nvkho['name']; ?></p>
                                                    <?php } ?>
                                                    <p>
                                                        <span class="text-12 text-secondary"><?= date("d/m H:i", strtotime($row_pxk['created'])); ?></span>
                                                    </p>
                                                </td>
                                            </tr>
                                            <?php if ($row_pxk['status'] == "PACK") { ?>
                                                <tr>
                                                    <td class="text-end border-end">
                                                        <span class="material-icons text-center text-20 text-grey">pending</span>
                                                    </td>
                                                    <td class="">
                                                        <p class="text-12 text-grey fst-italic">Chờ Đóng Hàng</p>
                                                    </td>
                                                </tr>
                                            <?php } else if ($row_pxk['status'] == "OUT") { ?>
                                                <tr>
                                                    <td class="text-end border-end">
                                                        <span class="material-icons text-center text-20 text-success">check_circle</span>
                                                    </td>
                                                    <td class="">
                                                        <p class="fw-bold text-12">Đã đóng hàng</p>
                                                        <p>
                                                            <span class="text-12 text-secondary"><?= date("d/m H:i", strtotime($row_pxk['pack_time_done'])); ?></span>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end border-end">
                                                        <span class="material-icons text-center text-20 text-grey">pending</span>
                                                    </td>
                                                    <td class="">
                                                        <p class="text-12 text-grey fst-italic">Chờ Xuất Kho</p>
                                                    </td>
                                                </tr>
                                            <?php } else { ?>
                                                <tr>
                                                    <td class="text-end border-end">
                                                        <span class="material-icons text-center text-20 text-success">check_circle</span>
                                                    </td>
                                                    <td class="">
                                                        <p class="fw-bold text-12">Đã đóng hàng</p>
                                                        <p>
                                                            <span class="text-12 text-secondary"><?= date("d/m H:i", strtotime($row_pxk['pack_time_done'])); ?></span>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end border-end">
                                                        <span class="material-icons text-center text-20 text-success">check_circle</span>
                                                    </td>
                                                    <td class="">
                                                        <p class="fw-bold text-12">Đã xuất kho</p>

                                                        <?php
                                                        $sql_kho_done_shipper = "SELECT * FROM ops_user WHERE id = '" . $row1['shipper_id'] . "'";
                                                        $res_kho_done_shipper = mysqli_query($connect, $sql_kho_done_shipper);
                                                        while ($row_kho_done_shipper = mysqli_fetch_array($res_kho_done_shipper)) {
                                                        ?>
                                                            <p class="text-primary text-12">Shipper <?= $row_kho_done_shipper['name']; ?></p>
                                                        <?php } ?>

                                                        <p>
                                                            <span class="text-12 text-secondary"><?= date("d/m H:i", strtotime($row_pxk['pack_time_done'])); ?></span>
                                                        </p>
                                                    </td>
                                                </tr>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>

                                    <tr>
                                        <td colspan="2" class="bg-light">
                                            <p class="fw-bold mb-2 text-20">Ship</p>
                                        </td>
                                    </tr>

                                    <?php
                                    $sql_ship = "SELECT * FROM vandon WHERE order_id ='" . $_GET['id'] . "'";
                                    $res_ship = mysqli_query($connect, $sql_ship);
                                    $count_ship = mysqli_num_rows($res_ship);
                                    if ($count_ship == 0) {
                                    ?>
                                        <tr>
                                            <td class="text-end border-end">
                                                <span class="material-icons text-center text-20 text-grey">pending</span>
                                            </td>
                                            <td class="">
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#donghangModal">
                                                    <p>Gán shipper</p>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    } else {
                                        while ($row_ship = mysqli_fetch_array($res_ship)) {
                                        ?>
                                            <tr>
                                                <td class="text-end border-end">
                                                    <span class="material-icons text-center text-20 text-success">check_circle</span>
                                                </td>
                                                <td class="">
                                                    <p class="fw-bold text-12">Tạo vận đơn số #<?= $row_ship['id']; ?></p>

                                                    <?php
                                                    $sql_ship_create_shipper = "SELECT * FROM ops_user WHERE id = '" . $row_ship['shipper_id'] . "'";
                                                    $res_ship_create_shipper = mysqli_query($connect, $sql_ship_create_shipper);
                                                    while ($row_ship_create_shipper = mysqli_fetch_array($res_ship_create_shipper)) {
                                                    ?>
                                                        <p class="text-primary text-12">Shipper <?= $row_ship_create_shipper['name']; ?></p>
                                                    <?php } ?>

                                                    <p>
                                                        <span class="text-12 text-secondary"><?= date("d/m H:i", strtotime($row_ship['created'])); ?></span>
                                                    </p>
                                                </td>
                                            </tr>
                                            <?php if ($row_ship['status'] == "OUT") { ?>
                                                <tr>
                                                    <td class="text-end border-end">
                                                        <span class="material-icons text-center text-20 text-grey">pending</span>
                                                    </td>
                                                    <td class="">
                                                        <p class="text-12 text-grey fst-italic">Chờ xuất kho</p>
                                                    </td>
                                                </tr>
                                            <?php } else if ($row_ship['status'] == "SHIP") { ?>
                                                <tr>
                                                    <td class="text-end border-end">
                                                        <span class="material-icons text-center text-20 text-success">check_circle</span>
                                                    </td>
                                                    <td class="">
                                                        <p class="fw-bold text-12">Đã Xuất Kho</p>

                                                        <p>
                                                            <span class="text-12 text-secondary"><?= date("d/m H:i", strtotime($row_ship['out_time_done'])); ?></span>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end border-end">
                                                        <span class="material-icons text-center text-20 text-grey">pending</span>
                                                    </td>
                                                    <td class="">
                                                        <p class="text-12 text-grey fst-italic">Chờ giao hàng</p>
                                                    </td>
                                                </tr>
                                            <?php } else { ?>
                                                <tr>
                                                    <td class="text-end border-end">
                                                        <span class="material-icons text-center text-20 text-success">check_circle</span>
                                                    </td>
                                                    <td class="">
                                                        <p class="fw-bold text-12">Đã Xuất Kho</p>
                                                        <p>
                                                            <span class="text-12 text-secondary"><?= date("d/m H:i", strtotime($row_ship['out_time_done'])); ?></span>
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end border-end">
                                                        <span class="material-icons text-center text-20 text-success">check_circle</span>
                                                    </td>
                                                    <td class="">
                                                        <p class="fw-bold text-12 mb-1">Giao hàng thành công (<?= $row_ship['ship_distance']; ?> m)</p>
                                                        <div class="d-flex">
                                                            <a target="_blank" href="https://www.google.com/maps/search/?api=1&query=<?= $row_ship['ship_lat']; ?>,<?= $row_ship['ship_long']; ?>" class="btn btn-sm btn-outline-secondary mb-1">
                                                                <p class="text-12">Xem tọa độ giao</p>
                                                            </a>
                                                        </div>

                                                        <?php if (!empty($row_ship['ship_bill_img'])) { ?>
                                                            <a target="_blank" href="<?= $row_ship['ship_bill_img']; ?>">
                                                                <img src="<?= $row_ship['ship_bill_img']; ?>" width="50%" alt="" class="rounded mb-1">
                                                            </a>
                                                        <?php } ?>

                                                        <p class="my-2"><?php
                                                                        if ($row_ship['ship_collect_has']) {
                                                                            echo "Thu COD " . $ship_collect_amount . "đ";
                                                                        } else {
                                                                            echo "Không thu tiền";
                                                                        }
                                                                        ?></p>
                                                        <p>
                                                            <span class="text-12 text-secondary"><?= date("d/m H:i", strtotime($row_ship['ship_time_done'])); ?></span>
                                                        </p>
                                                    </td>
                                                </tr>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                    <!-- <tr>
                                            <td class="text-end border-end">
                                                <span class="material-icons text-center text-20 text-danger">cancel</span>
                                            </td>
                                            <td class="">
                                                <p class="fw-bold text-12">Đã hủy</p>
                                                <p class="text-primary text-12">Bởi Minh La</p>
                                                <p>
                                                    <span class="text-12 text-secondary"><?= date("d/m H:i", strtotime($row1['log_time'])); ?></span>
                                                </p>
                                            </td>
                                        </tr> -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right -->
                <div class="col-12 col-md-12 col-xl-8">

                    <!-- Item -->
                    <div class="border shadow-gg rounded bg-white p-2 mb-3" style="padding-bottom: 20px; padding-top: 20px;">
                        <p class="text-12 fw-bold mb-2">ĐƠN HÀNG</p>
                        <?php
                        $sql_item = "SELECT * FROM orders_detail WHERE orders_id ='" . $_GET['id'] . "'";
                        $res_item = mysqli_query($connect, $sql_item);
                        $count_item = mysqli_num_rows($res_item);
                        if ($count_item > 0) {
                        ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hovered mb-2">
                                    <thead class="table-secondary">
                                        <th class="text-10" colspan="2"></th>
                                        <th class="text-10 table-secondary" colspan="4">ĐV Quy Cách</th>
                                        <th class="text-10 table-secondary" colspan="3">ĐV Gốc</th>
                                        <th class="text-10" colspan="2"></th>
                                    </thead>
                                    <thead class="table-secondary">
                                        <th class="text-10">Mã SP</th>
                                        <th class="text-10" rowspan="2">SẢN PHẨM</th>
                                        <th class="text-10 text-end">SL</th>
                                        <th class="text-10">ĐVT</th>
                                        <th class="text-10 text-end">Giá</th>
                                        <th class="text-10 text-end">TT</th>
                                        <th class="text-10 text-end">SL Gốc</th>
                                        <th class="text-10">ĐVT Gốc</th>
                                        <th class="text-10 text-end">Giá Gốc</th>
                                        <th class="text-10 text-end">Hệ số</th>
                                        <th class=""></th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row_item = mysqli_fetch_array($res_item)) {
                                        ?>
                                            <tr>
                                                <td class="text-center align-middle">
                                                    <p><?= $row_item['product_id']; ?></p>
                                                </td>
                                                <td class="align-middle">
                                                    <?php
                                                    $sql_product = "SELECT * FROM products WHERE id ='" . $row_item['product_id'] . "'";
                                                    $res_product = mysqli_query($connect, $sql_product);
                                                    while ($row_product = mysqli_fetch_assoc($res_product)) {
                                                        $product_name = $row_product['name'];
                                                    ?>
                                                        <p class="fw-bold text-nowrap text-12"><?= $row_product['name']; ?></p>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-end align-middle">
                                                    <div class="d-grid">
                                                        <p class="fw-bold btn btn-sm btn-outline-dark text-12"><?= number_format($row_item['qty'], 0); ?></p>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <?php
                                                    $sql_unit = "SELECT * FROM products WHERE id = '" . $row_item['product_id'] . "'";
                                                    $res_unit = mysqli_query($connect, $sql_unit);
                                                    while ($row_unit = mysqli_fetch_array($res_unit)) {
                                                    ?>
                                                        <p><?= $row_unit['package_unit']; ?></p>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-end align-middle">
                                                    <p class="text-12"><?= number_format($row_item['price'], 0); ?> đ</p>
                                                </td>
                                                <td class="text-end align-middle">
                                                    <p class="fw-bold text-success text-12"><?= number_format($row_item['total'], 0); ?> đ</p>
                                                </td>
                                                <td class="text-end align-middle">
                                                    <div class="d-grid">
                                                        <p class="fw-bold btn btn-sm btn-outline-dark text-12"><?= number_format($row_item['qty_base'], 0); ?></p>
                                                    </div>
                                                </td>
                                                <td class="align-middle">
                                                    <p><?= $row_item['unit_base']; ?></p>
                                                </td>
                                                <td class="text-end align-middle">
                                                    <p class="text-12"><?= number_format($row_item['price_base'], 0); ?></p>
                                                </td>
                                                <td class="text-end align-middle">
                                                    <p class="text-12"><?= (floor($row_item['factor']) == $row_item['factor']) ? number_format($row_item['factor'], 0) : number_format($row_item['factor'], 1); ?></p>
                                                </td>
                                                <td class="align-middle">
                                                    <a class="btn btn-link" href="./?view=edit-item&itemid=<?= $row_item['id']; ?>&orderid=<?= $_GET['id']; ?>&groupid=<?= $group_id; ?>&productid=<?= $row_item['product_id']; ?>&productname=<?= $product_name; ?>"><span class="material-icons text-14 text-dark">edit</span></a>
                                                    <a class="btn btn-link" href="./?view=delete-item&itemid=<?= $row_item['id']; ?>&orderid=<?= $_GET['id']; ?>&qty=<?= $row_item['qty']; ?>&price=<?= $row_item['price']; ?>&name=<?= $product_name; ?>"><span class="material-icons text-14 text-danger">delete</span></a>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php
                        }
                        ?>

                        <div>
                            <a href="./?view=add-item&orderid=<?= $row1['id']; ?>&groupid=<?= $group_id; ?>" class="btn btn-link btn-sm mb-1">
                                <p class="text-12">+ Thêm Dòng</p>
                            </a>
                        </div>

                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="80%">
                                        <p class="text-end">Tạm Tính</p>
                                    </td>
                                    <td width="20%">
                                        <p class="text-end"><?= number_format($row1['subtotal'], 0); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="80%">
                                        <p class="text-end">Vận Chuyển</p>
                                    </td>
                                    <td>
                                        <p type="button" data-bs-toggle="modal" data-bs-target="#discountModal" id="shipModalLabel" class="text-end border-bottom ps-3"><?= number_format($row1['ship'], 0); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="80%">
                                        <p class="text-end">Giảm giá</p>
                                    </td>
                                    <td width="20%">
                                        <p type="button" data-bs-toggle="modal" data-bs-target="#discountModal" id="discountModalLabel" class="text-end border-bottom ps-3"><? (empty($row1['discount'])) ? "" : "- "; ?><?= number_format($row1['discount'], 0); ?></p>
                                        <?php
                                        if ($row1['discount_option'] == "voucher") {
                                            $sql_order_voucher = "SELECT * FROM orders_voucher WHERE orders_id = '" . $_GET['id'] . "'";
                                            $res_order_voucher = mysqli_query($connect, $sql_order_voucher);
                                            $count_order_voucher = mysqli_num_rows($res_order_voucher);
                                            while ($row_order_voucher = mysqli_fetch_array($res_order_voucher)) {
                                        ?>
                                                <div class="btn btn-sm btn-outline-success mt-2">
                                                    <div class="d-flex align-items-center">
                                                        <span class="material-icons me-2">redeem</span>
                                                        <p class="fw-bold text-uppercase"><?= $row_order_voucher['vouchers_code']; ?></p>
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="80%">
                                        <p class="text-end fw-bold">TỔNG CỘNG</p>
                                    </td>
                                    <td width="20%" class="text-end">
                                        <p class="text-14 fw-bold btn btn-success"><?= number_format($row1['total'], 0); ?></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Print Bill -->
            <div class="d-none bg-white" style="padding-bottom: 10px; padding-top: 10px;">
                <div id="printarea">
                    <div class="p-0">
                        <table class="table table-sm table-borderless border-white mt-0">
                            <tbody>
                                <tr>
                                    <td style="width:20%" class="text-center">
                                        <img src="../img/logo.png" class="text=center" width="60%" alt="">
                                    </td>
                                    <td style="width:80%">
                                        <div>
                                            <p class="fw-bold mb-2" style="font-size:11px;">HỘ KINH DOANH DIỆU THẢO</p>
                                            <p class="mb-1" style="font-size:11px;">Chủ hộ kinh doanh: Phạm Nguyễn Luân Viên</p>
                                            <p class="mb-1" style="font-size:11px;">MST: 8307587287-001 | Địa chỉ: 304/12/9 Bùi Đình Túy, Phường 12, Quận Bình Thạnh, TPHCM</p>
                                            <p class="mb-1" style="font-size:11px;">Điện thoại: 0944897711 - 0764686029 | Website: https://dieuthao.vn</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="fw-bold text-center" style="font-size:18px !important; margin-top:0px !important; margin-bottom:10px !important;">PHIẾU GIAO HÀNG</p>
                        <table class="table table-sm table-borderless border-white mt-0">
                            <tbody>
                                <tr>
                                    <td style="width:40%">
                                        <p class="mb-2 text-end" style="font-size:11px;">Người mua hàng:</p>
                                        <p class="mb-2 text-end" style="font-size:11px;">Điện thoại:</p>
                                        <p class="mb-2 text-end" style="font-size:11px;">Địa chỉ nhận hàng:</p>
                                    </td>
                                    <td style="width:60%">
                                        <p class="mb-2 fw-bold" style="font-size:11px;"><?= $customer_name; ?></p>
                                        <p class="mb-2 fw-bold" style="font-size:11px;"><?= $customer_phone; ?></p>
                                        <p class="mb-2 fw-bold" style="font-size:11px;"><?= $row1['address']; ?><?= (empty($row1['address'])) ? "" : ", "; ?><?= $row1['street']; ?></p>
                                        <p class="mb-2 fw-bold" style="font-size:11px;"><?= $row1['ward'] . ", " . $row1['district']; ?></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php
                        $sql_item = "SELECT * FROM orders_detail WHERE orders_id ='" . $_GET['id'] . "'";
                        $res_item = mysqli_query($connect, $sql_item);
                        $count_item = mysqli_num_rows($res_item);
                        if ($count_item > 0) {
                        ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-secondary">
                                        <th class="text-center" style="font-size:11px;">STT</th>
                                        <th class="" style="font-size:11px;">Tên Hàng</th>
                                        <th class="text-end" style="font-size:11px;">SL</th>
                                        <th class="text-end" style="font-size:11px;">ĐVT</th>
                                        <th class="text-end" style="font-size:11px;">Đơn Giá</th>
                                        <th class="text-end text-nowrap" style="font-size:12px;">Thành Tiền</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stt = 0;
                                        while ($row_item = mysqli_fetch_array($res_item)) {
                                            $stt++;
                                        ?>
                                            <tr>
                                                <td class="align-middle">
                                                    <p class="text-center mb-0" style="font-size:11px;"><?= $stt; ?></p>
                                                </td>
                                                <td class="align-middle">
                                                    <?php
                                                    $sql_product = "SELECT * FROM products WHERE id ='" . $row_item['product_id'] . "'";
                                                    $res_product = mysqli_query($connect, $sql_product);
                                                    while ($row_product = mysqli_fetch_assoc($res_product)) {
                                                        $product_name = $row_product['name'];
                                                    ?>
                                                        <p class="mb-0 fw-bold text-wrap" style="font-size:11px;"><?= $row_product['name']; ?></p>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-end align-middle">
                                                    <div class="d-grid">
                                                        <p class="mb-0 fw-bold" style="font-size:11px;"><?= number_format($row_item['qty'], 0); ?></p>
                                                    </div>
                                                </td>
                                                <td class="text-end align-middle">
                                                    <div class="d-grid">
                                                        <?php
                                                        $sql_unit_quote = "SELECT * FROM products WHERE id = '" . $row_item['product_id'] . "'";
                                                        $res_unit_quote = mysqli_query($connect, $sql_unit_quote);
                                                        while ($row_unit_quote = mysqli_fetch_array($res_unit_quote)) {
                                                        ?>
                                                            <p class="mb-0" style="font-size:11px;"><?= $row_unit_quote['package_unit']; ?></p>
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                                <td class="text-end align-middle">
                                                    <p class="mb-0" style="font-size:11px;"><?= number_format($row_item['price'], 0); ?> đ</p>
                                                </td>
                                                <td class="text-end align-middle">
                                                    <p class="mb-0 fw-bold text-nowrap" style="font-size:11px;"><?= number_format($row_item['total'], 0); ?> đ</p>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="5" class="border-bottom" style="width:75%">
                                                <p class="mb-0 text-end" style="font-size:11px;">Tổng tiền hàng</p>
                                            </td>
                                            <td class="border-bottom" style="width:25%">
                                                <p class="mb-0 text-end" style="font-size:11px;"><?= number_format($row1['subtotal'], 0); ?> đ</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="border-bottom" style="width:75%">
                                                <p class="mb-0 text-end" style="font-size:11px;">Phí vận chuyển</p>
                                            </td>
                                            <td class="border-bottom" style="width:25%">
                                                <p class="mb-0 text-end" style="font-size:11px;"><?= number_format($row1['ship'], 0); ?> đ</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="border-bottom" style="width:75%">
                                                <p class="mb-0 text-end" style="font-size:11px;">Chiết khấu</p>
                                            </td>
                                            <td class="border-bottom" style="width:25%">
                                                <p class="mb-0 text-end" style="font-size:11px;"><?= (empty($row1['discount'])) ? "" : "- "; ?><?= number_format($row1['discount'], 0); ?> đ</p>
                                            </td>
                                        </tr>
                                        <tr class="table-secondary">
                                            <td colspan="5" class="border-bottom" style="width:75%">
                                                <p class="mb-0 text-end fw-bold" style="font-size:11px;">TỔNG CỘNG</p>
                                            </td>
                                            <td class="border-bottom" style="width:25%; text-align:right;">
                                                <p class="mb-0 text-end fw-bold" style="font-size:11px;"><?= number_format($row1['total'], 0); ?> đ</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-sm table-borderless border-white mt-3">
                                    <tbody>
                                        <tr>
                                            <td style="width:50%">
                                                <br>
                                                <br>
                                                <p class="mb-0 text-center fw-bold" style="font-size:11px;">NGƯỜI MUA HÀNG</p>
                                            </td>
                                            <td style="width:50%">
                                                <p class="mb-3 text-center" style="font-size:11px;">Tp.HCM, ngày <?= date("d", strtotime($row1['log_time'])); ?> tháng <?= date("m"); ?> năm <?= date("Y"); ?></p>
                                                <p class="mb-0 text-center fw-bold" style="font-size:11px;">CHỦ HỘ KINH DOANH</p>
                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                                <p class="mb-0 text-center fw-bold" style="font-size:11px;">PHẠM NGUYỄN LUÂN VIÊN</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Discount Modal -->
        <div class="modal fade" id="discountModal" tabindex="1000" aria-labelledby="discountModalLabel" aria-hidden="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body pt-3">
                        <form action="do.php" method="post">
                            <input type="hidden" name="action" value="ship-discount">
                            <input type="hidden" name="order_id" value="<?= $_GET['id']; ?>" required>
                            <input type="hidden" name="subtotal" value="<?= $row1['subtotal']; ?>" required>
                            <input type="hidden" name="voucher_id" id="formVoucherId" value="">
                            <input type="hidden" name="voucher_code" id="formVoucherCode" value="">

                            <p class="text-center text-20 fw-bold text-theme my-3">Phí ship & Giảm Giá</p>

                            <div class="row">
                                <div class="col-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="material-icons text-20 me-3 text-theme">sell</span>
                                        <p class="mb-2">Tạm tính</p>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <span class="text-success fw-bold" id="formSubtotal"><?= number_format($row1['subtotal'], 0); ?> đ</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="material-icons text-20 me-3 text-theme">local_shipping</span>
                                        <p class="mb-2">Phí ship</p>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <input type="number" class="form-control" name="ship" id="formShip" value="<?= $row1['ship']; ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="material-icons text-20 me-3 text-theme">sell</span>
                                        <p class="mb-2">Cách giảm</p>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="mb-1">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="discount_option" id="discountOption1" value="absolute" <?= ($discount_option == "absolute") ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="discountOption1">
                                                Giảm thẳng
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="discount_option" id="discountOption2" value="percent" <?= ($discount_option == "percent") ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="discountOption2">
                                                Giảm %
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="discount_option" id="discountOption3" value="voucher" <?= ($discount_option == "voucher") ? "checked" : ""; ?>>
                                            <label class="form-check-label" for="discountOption3">
                                                Mã
                                            </label>
                                        </div>
                                    </div>


                                    <input type="number" class="form-control mb-3" name="discount_value" id="formDiscountValue" value="<?= $row1['discount_value']; ?>" style="display:<?= ($row1['discount_option'] == "absolute" || $row1['discount_option'] == "percent") ? "block" : "none" ?>">

                                    <div id="formVoucher" style="display:<?= ($row1['discount_option'] == "voucher") ? "block" : "none" ?>">
                                        <select name="discount_voucher" id="formVoucherCode" class="form-select mb-3">
                                            <option>Chọn mã</option>
                                            <?php
                                            $sql_voucher = "SELECT * FROM vouchers WHERE status='ACTIVE' AND DATE_ADD(NOW(), INTERVAL 7 HOUR) BETWEEN start_date AND end_date";
                                            echo $sql_voucher;
                                            $res_voucher = mysqli_query($connect, $sql_voucher);
                                            $count_voucher = mysqli_num_rows($res_voucher);
                                            while ($row_voucher = mysqli_fetch_array($res_voucher)) {
                                                $sql_current_voucher = "SELECT * FROM orders_voucher WHERE orders_id = '" . $row1['id'] . "'";
                                                $res_current_voucher = mysqli_query($connect, $sql_current_voucher);
                                                $count_current_voucher = mysqli_num_rows($res_current_voucher);
                                                while ($row_current_voucher = mysqli_fetch_array($res_current_voucher)) {
                                                    $current_voucher_id = $row_current_voucher['id'];
                                                }
                                            ?>
                                                <option value="<?= $row_voucher['id']; ?>" <?= ($current_voucher_id == $row_voucher['id']) ? "selected" : ""; ?>><?= $row_voucher['code']; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <p style="display:none" id="formVoucherStatus" class="fw-bold mb-3"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="material-icons text-20 me-3 text-theme">style</span>
                                        <p class=" mb-2">Số tiền giảm giá</p>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <span class="text-success fw-bold" id="formDiscount"><?= number_format($row1['discount'], 0); ?> đ</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="material-icons text-20 me-3 text-theme">trip_origin</span>
                                        <label class="mb-2" for="qty">Tổng cộng</label>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <span class="text-success fw-bold" id="formTotal"><?= number_format($row1['total'], 0); ?> đ</span>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <button type="submit" class="btn btn-primary">Lưu</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Đóng Hàng Modal -->
        <div class="modal fade" id="donghangModal" tabindex="-1" aria-labelledby="donghangModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="do.php" method="post">
                        <input type="hidden" name="action" value="log-donghang">
                        <input type="hidden" name="order_id" value="<?= $row1['id']; ?>">
                        <input type="hidden" name="customer_id" value="<?= $row1['customer_id']; ?>">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="donghangModalLabel">Đóng hàng & Gán ship</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-12 text-secondary my-2">Kho</p>
                            <?php
                            $sql_donghangmodal_kho = "SELECT * FROM hubs ORDER BY name";
                            $res_donghangmodal_kho = mysqli_query($connect, $sql_donghangmodal_kho);
                            while ($row_donghangmodal_kho = mysqli_fetch_array($res_donghangmodal_kho)) {
                            ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="hub_id" id="donghangModalKho<?= $row_donghangmodal_kho['id']; ?>" value="<?= $row_donghangmodal_kho['id']; ?>" checked required>
                                    <label class="form-check-label" for="donghangModalKho<?= $row_donghangmodal_kho['id']; ?>">
                                        <p><?= $row_donghangmodal_kho['name']; ?></p>
                                    </label>
                                </div>
                            <?php } ?>

                            <p class="text-12 text-secondary my-2">Chọn NV đóng hàng</p>
                            <select name="employee_id" class="form-select" required>
                                <?php
                                $sql_donghangmodal_nv = "SELECT * FROM ops_user ORDER BY name";
                                $res_donghangmodal_nv = mysqli_query($connect, $sql_donghangmodal_nv);
                                while ($row_donghangmodal_nv = mysqli_fetch_array($res_donghangmodal_nv)) {
                                ?>
                                    <option value="<?= $row_donghangmodal_nv['id']; ?>"><?= $row_donghangmodal_nv['name']; ?></option>
                                <?php } ?>
                            </select>

                            <p class="text-12 text-secondary my-2">Đóng vào lúc</p>
                            <input class="form-control" type="datetime" name="out_time_assign" value="<?= date("Y-m-d H:i:s");; ?>" required>

                            <p class="text-12 text-secondary my-2">Chọn Shipper</p>
                            <select name="shipper_id" class="form-select" required>
                                <?php
                                $sql_ganshipmodal_nv = "SELECT * FROM ops_user ORDER BY name";
                                $res_ganshipmodal_nv = mysqli_query($connect, $sql_ganshipmodal_nv);
                                while ($row_ganshipmodal_nv = mysqli_fetch_array($res_ganshipmodal_nv)) {
                                ?>
                                    <option value="<?= $row_ganshipmodal_nv['id']; ?>"><?= $row_ganshipmodal_nv['name']; ?></option>
                                <?php } ?>
                            </select>

                            <p class="text-12 text-secondary my-2">Ship vào lúc</p>
                            <input class="form-control" type="datetime" name="ship_time_assign" value="<?= date("Y-m-d H:i:s");; ?>" required>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success btn-sm">Xác nhận</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#logShowMore").click(function() {
                    $("#logDetail").show();
                    $(this).hide();
                });
            });
        </script>

        <!-- Modal Script -->
        <script type="text/javascript">
            $("#formShip").keyup(function() {
                subtotal = <?= $row1['subtotal']; ?>;
                ship = parseInt($("#formShip").val());
                discountOption = $('input[name="discount_option"]:checked').val();
                discountValue = $("#formDiscountValue").val();
                if (discountOption == "absolute") {
                    discount = parseInt(discountValue);
                } else if (discountOption == "percent") {
                    discount = parseInt(subtotal * discountValue / 100);
                } else if (discountOption == "voucher") {
                    discount = parseInt(subtotal * discountValue / 100);
                }
                total = subtotal + ship - discount;
                var num = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                $("#formTotal").html(num);
            });
            $("#formDiscountValue").keyup(function() {
                subtotal = <?= $row1['subtotal']; ?>;
                ship = parseInt($("#formShip").val());
                discountOption = $('input[name="discount_option"]:checked').val();
                discountValue = $("#formDiscountValue").val();
                if (discountOption == "absolute") {
                    discount = parseInt(discountValue);
                } else if (discountOption == "percent") {
                    discount = parseInt(subtotal * discountValue / 100);
                } else if (discountOption == "voucher") {
                    discount = parseInt(subtotal * discountValue / 100);
                }
                total = subtotal + ship - discount;
                var numDiscount = discount.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                $("#formDiscount").html(numDiscount);
                var numTotal = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                $("#formTotal").html(numTotal);
            });
            $('input[name="discount_option"]').change(function() {
                subtotal = <?= $row1['subtotal']; ?>;
                ship = parseInt($("#formShip").val());
                discountOption = $('input[name="discount_option"]:checked').val();
                discountValue = $("#formDiscountValue").val();
                if (discountOption == "absolute") {
                    $("#formDiscountValue").show();
                    $("#formVoucher").hide();
                    discount = parseInt(discountValue);
                    total = subtotal + ship - discount;
                    var numDiscount = discount.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                    $("#formDiscount").html(numDiscount);
                    var numTotal = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                    $("#formTotal").html(numTotal);
                } else if (discountOption == "percent") {
                    $("#formDiscountValue").show();
                    $("#formVoucher").hide();
                    discount = parseInt(subtotal * discountValue / 100);
                    total = subtotal + ship - discount;
                    var numDiscount = discount.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                    $("#formDiscount").html(numDiscount);
                    var numTotal = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                    $("#formTotal").html(numTotal);
                } else if (discountOption == "voucher") {
                    $("#formDiscountValue").hide();
                    $("#formVoucher").show();
                    total = subtotal + ship;
                    $("#formDiscount").html("0 đ");
                    var numTotal = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                    $("#formTotal").html(numTotal);
                }
            });
            $('select[name="discount_voucher"]').change(function() {
                voucherCode = $(this).find('option:selected').text();
                voucherId = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "get.php",
                    data: {
                        action: "voucher-lookup",
                        s: voucherId,
                    },
                    success: function(data) {
                        json = JSON.parse(data);
                        $("#formVoucherStatus").show()
                        $("#formVoucherStatus").html(json.message);
                        discountValue = json.discount;
                        discount = parseInt(subtotal * discountValue / 100);
                        discountCap = parseInt(json.cap);
                        if (discount > discountCap) {
                            discount = discountCap;
                        }
                        $("#formDiscountValue").val(discount);
                        total = subtotal + ship - discount;
                        var numDiscount = discount.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                        $("#formDiscount").html(numDiscount);
                        var numTotal = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                        $("#formTotal").html(numTotal);
                        $("#formVoucherId").val(voucherId)
                        $("#formVoucherCode").val(voucherCode)
                    }
                });
            });
        </script>

        <!-- Print Script -->
        <script type="text/javascript">
            /*--This JavaScript method for Print command--*/
            function PrintDoc() {
                var toPrint = document.getElementById('printarea');
                var popupWin = window.open('', '_blank', 'width=1000,height=700,location=no');
                popupWin.document.open();
                popupWin.document.write('<html><title></title><link rel="stylesheet" type="text/css" href="print.css" /></head><body onload="window.print()">')
                popupWin.document.write(toPrint.innerHTML);
                popupWin.document.write('</html>');
                popupWin.document.close();
            }
            /*--This JavaScript method for Print Preview command--*/
            function PrintPreview() {
                var toPrint = document.getElementById('printarea');
                var popupWin = window.open('', '_blank');
                popupWin.document.open();
                popupWin.document.write('<html><head><title></title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" /></head><body style="font-family: Arial" onload="window.print()">')
                popupWin.document.write(toPrint.innerHTML);
                popupWin.document.write('</body></html>');
                popupWin.document.close();
            }
        </script>

    <?php
    }
} else {
    ?>
    <p class="text-center">Không tìm thấy Đơn bán #<?= $_GET['id']; ?></p>
<?php
}
?>