<?php
$sql1 = "SELECT * FROM nhap WHERE id ='" . $_GET['id'] . "'";
$res1 = mysqli_query($connect, $sql1);
$count1 = mysqli_num_rows($res1);
if ($count1 > 0) {
    while ($row1 = mysqli_fetch_assoc($res1)) {
?>
        <div class="container-fluid bg-grey vh-100">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center justify-content-between py-3">
                    <a href="/nhaphang/" class="btn btn-sm border bg-white me-2"><span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span></a>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="material-symbols-outlined text-20 me-2">receipt_long</span>
                        <p class="fw-bold text-14">Đơn Nhập #<?= $row1['id']; ?></p>
                    </div>
                </div>
                <div class="d-flex align-items-center">

                    <a type="btn" onclick="PrintPreview()" class="btn btn-sm btn-outline-success me-2">
                        <span class="material-symbols-outlined text-14">print</span>
                    </a>

                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="material-symbols-outlined text-14">more_vert</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a href="/nhaphang/?view=delete&id=<?= $row1['id']; ?>" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <span class="material-symbols-outlined text-14 me-2 text-danger">delete</span>
                                    <p class="text-danger">Xóa đơn</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row px-0">
                <div class="col-12 col-md-8 offset-md-2 col-xl-4 offset-xl-0">
                    <!-- Thông Tin -->
                    <div class="rounded border shadow-gg rounded mb-3 p-3 bg-white">
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
                            <!-- Info & Status -->
                            <div class="tab-pane fade show active" id="underline-info" role="tabpanel" aria-labelledby="underline-info-tab" tabindex="0">
                                <div class="text-end">
                                    <a href="/nhaphang/?view=edit&id=<?= $row1['id']; ?>" class="btn btn-sm btn-outline-primary"><span class="material-symbols-outlined text-14">edit</span></a>
                                </div>
                                <div class="table-responsive mt-2">
                                    <table class="table table-borderless table-sm mb-0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p class="text-secondary mb-1">Ngày nhập</p>
                                                </td>
                                                <td>
                                                    <p class="click-to-copy"><?= date("Y-m-d", strtotime($row1['accounting_date'])); ?></span></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="text-secondary mb-1">Số hoá đơn</p>
                                                </td>
                                                <td>
                                                    <p class="fw-bold text-primary click-to-copy"><?= $row1['accounting_nhap_id']; ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="text-secondary mb-1">ID nội bộ</p>
                                                </td>
                                                <td>
                                                    <p class="fw-bold text-danger click-to-copy"><?= $row1['ksky_nhap_id']; ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="text-secondary mb-1">Công ty</p>
                                                </td>
                                                <td class="text-wrap">
                                                    <p class="click-to-copy"><?= $row1['company_name']; ?></p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Payment -->
                            <div class="tab-pane fade" id="underline-payment" role="tabpanel" aria-labelledby="underline-payment-tab" tabindex="0">

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Item -->
                <div class="col-12 col-md-12 col-xl-8">
                    <div class="border shadow-gg rounded bg-white p-2 mb-3" style="padding-bottom: 20px; padding-top: 20px;">
                        <p class="text-12 fw-bold mb-2">ĐƠN HÀNG</p>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hovered mb-2">
                                <thead class="table-secondary">
                                    <th class=""></th>
                                    <th class="text-10 d-none d-md-table-cell text-center">Mã SP</th>
                                    <th class="text-10">SẢN PHẨM</th>
                                    <th class="text-10">ĐVT</th>
                                    <th class="text-10 text-end">SL</th>
                                    <th class="text-10 text-end">Giá</th>
                                    <th class="text-10 text-end">TT</th>
                                    <th class="text-10 text-end"></th>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql_item = "SELECT * FROM nhap_detail WHERE nhap_id ='" . $_GET['id'] . "'";
                                    $res_item = mysqli_query($connect, $sql_item);
                                    $count_item = mysqli_num_rows($res_item);
                                    if ($count_item > 0) {
                                        while ($row_item = mysqli_fetch_array($res_item)) {
                                    ?>
                                            <tr>
                                                <td class="text-center align-middle">
                                                    <?php if ($row_item['is_it']) { ?>
                                                        <p class="btn btn-sm btn-danger text-10 fw-bold">ẢO</p>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-center align-middle d-none d-md-table-cell">
                                                    <p class="text-10 click-to-copy"><?= $row_item['product_code']; ?></p>
                                                </td>
                                                <td class="align-middle">
                                                    <p class="text-wrap text-10 fw-bold click-to-copy"><?= $row_item['product_name']; ?></p>
                                                    <p class="d-md-none text-10 text-grey click-to-copy"><?= $row_item['product_code']; ?></p>
                                                </td>
                                                <td class="align-middle">
                                                    <p class="text-10"><?= $row_item['unit']; ?></p>
                                                </td>
                                                <td class="text-end align-middle">
                                                    <div class="d-grid">
                                                        <p class="fw-bold btn btn-sm btn-outline-dark text-10"><?= number_format($row_item['qty'], 0); ?></p>
                                                    </div>
                                                </td>
                                                <td class="text-end align-middle">
                                                    <p class="text-10"><?= number_format($row_item['price'], 0); ?></p>
                                                </td>
                                                <td class="text-end align-middle">
                                                    <p class="fw-bold text-success text-10"><?= number_format($row_item['total_before_vat'], 0); ?></p>
                                                </td>
                                                <td class="align-middle">
                                                    <a class="btn btn-sm btn-link" href="/nhaphang/?view=edit-item&itemid=<?= $row_item['id']; ?>&nhapid=<?= $_GET['id']; ?>&productid=<?= $row_item['product_id']; ?>"><span class="material-symbols-outlined text-14 text-dark">edit</span></a>

                                                    <a class="btn btn-sm btn-link" href="/nhaphang/?view=delete-item&itemid=<?= $row_item['id']; ?>&nhapid=<?= $_GET['id']; ?>&productid=<?= $row_item['product_id']; ?>"><span class="material-symbols-outlined text-14 text-danger">delete</span></a>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="7">
                                                <p>Chưa có dòng nào</p>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <a href="/nhaphang/?view=add-item&nhapid=<?= $row1['id']; ?>" class="btn btn-link btn-sm mb-2">
                                <p class="text-12">+ Thêm Dòng</p>
                            </a>
                        </div>
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="80%">
                                        <p class="text-end">Tổng cộng trước VAT</p>
                                    </td>
                                    <td width="20%" class="text-end">
                                        <p class="text-14 fw-bold btn btn-outline-success"><?= number_format($row1['total_before_vat'], 0); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="80%">
                                        <p class="text-end">VAT (<?= number_format($row1['vat_rate'], 1); ?>%)</p>
                                    </td>
                                    <td width="20%" class="text-end">
                                        <p class="pe-2"><?= number_format($row1['vat'], 0); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="80%">
                                        <p class="text-end fw-bold">Tổng cộng sau VAT</p>
                                    </td>
                                    <td width="20%" class="text-end">
                                        <p class="text-14 fw-bold btn btn-success"><?= number_format($row1['total_after_vat'], 0); ?></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
                                        <br>
                                        <p class="fw-bold mb-2" style="font-size:11px;">CÔNG TY TNHH KSKY</p>
                                        <p class="mb-1" style="font-size:11px;">MST: 0318422194 | Địa chỉ: 316 Lê Văn Sỹ, Phường 1, Quận Tân Bình, Thành phố Hồ Chí Minh, Việt Nam</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="fw-bold text-center" style="font-size:18px !important; margin-top:0px !important; margin-bottom:10px !important;">PHIẾU NHẬP HÀNG</p>
                    <table class="table table-sm table-borderless border-white mt-0">
                        <tbody>
                            <tr>
                                <td style="width:40%">
                                    <p class="mb-2 text-end" style="font-size:11px;">Ngày nhập:</p>
                                    <p class="mb-2 text-end" style="font-size:11px;">Số hoá đơn:</p>
                                    <p class="mb-2 text-end" style="font-size:11px;">ID nội bộ:</p>
                                    <p class="mb-2 text-end" style="font-size:11px;">Công ty:</p>
                                </td>
                                <td style="width:60%">
                                    <p class="mb-2 fw-bold" style="font-size:11px;"><?= $row1['accounting_date']; ?></p>
                                    <p class="mb-2 fw-bold" style="font-size:11px;"><?= $row1['accounting_nhap_id']; ?></p>
                                    <p class="mb-2 fw-bold" style="font-size:11px;"><?= $row1['ksky_nhap_id']; ?></p>
                                    <p class="mb-2 fw-bold" style="font-size:11px;"><?= $row1['company_name']; ?></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                    $sql_item = "SELECT * FROM nhap_detail WHERE nhap_id ='" . $_GET['id'] . "'";
                    $res_item = mysqli_query($connect, $sql_item);
                    $count_item = mysqli_num_rows($res_item);
                    if ($count_item > 0) {
                    ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table-secondary">
                                    <th class="text-center" style="font-size:11px;">STT</th>
                                    <th class="text-nowrap" style="font-size:11px;">Mã Sản phẩm</th>
                                    <th class="text-nowrap" style="font-size:11px;">Tên Hàng</th>
                                    <th class="text-end" style="font-size:11px;">ĐVT</th>
                                    <th class="text-end" style="font-size:11px;">SL</th>
                                    <th class="text-end" style="font-size:11px;">Đơn Giá</th>
                                    <th class="text-end text-nowrap" style="font-size:11px;">Thành Tiền</th>
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
                                                <p class="mb-0 text-wrap" style="font-size:11px;"><?= $row_item['product_code']; ?></p>
                                            </td>
                                            <td class="align-middle">
                                                <p class="mb-0 fw-bold text-wrap" style="font-size:11px;"><?= $row_item['product_name']; ?></p>
                                            </td>
                                            <td class="align-middle">
                                                <p class="mb-0 text-wrap" style="font-size:11px;"><?= $row_item['unit']; ?></p>
                                            </td>
                                            <td class="text-end align-middle">
                                                <div class="d-grid">
                                                    <p class="mb-0" style="font-size:11px;"><?= number_format($row_item['qty'], 0); ?></p>
                                                </div>
                                            </td>
                                            <td class="text-end align-middle">
                                                <p class="mb-0" style="font-size:11px;"><?= number_format($row_item['price'], 0); ?></p>
                                            </td>
                                            <td class="text-end align-middle">
                                                <p class="mb-0 text-nowrap" style="font-size:11px;"><?= number_format($row_item['total_before_vat'], 0); ?></p>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="6" class="border-bottom" style="width:75%">
                                            <p class="mb-0 text-end" style="font-size:11px;">Tổng tiền hàng</p>
                                        </td>
                                        <td class="border-bottom" style="width:25%">
                                            <p class="mb-0 text-end" style="font-size:11px;"><?= number_format($row1['total_before_vat'], 0); ?> đ</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="border-bottom" style="width:75%">
                                            <p class="mb-0 text-end" style="font-size:11px;">Thuế suất VAT</p>
                                        </td>
                                        <td class="border-bottom" style="width:25%">
                                            <p class="mb-0 text-end" style="font-size:11px;"><?= number_format($row1['vat_rate'], 0); ?>%</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="border-bottom" style="width:75%">
                                            <p class="mb-0 text-end" style="font-size:11px;">VAT</p>
                                        </td>
                                        <td class="border-bottom" style="width:25%">
                                            <p class="mb-0 text-end" style="font-size:11px;"><?= number_format($row1['vat'], 0); ?> đ</p>
                                        </td>
                                    </tr>
                                    <tr class="table-secondary">
                                        <td colspan="6" class="border-bottom" style="width:75%">
                                            <p class="mb-0 text-end fw-bold" style="font-size:11px;">Tổng cộng sau VAT</p>
                                        </td>
                                        <td class="border-bottom" style="width:25%; text-align:right;">
                                            <p class="mb-0 text-end fw-bold" style="font-size:11px;"><?= number_format($row1['total_after_vat'], 0); ?> đ</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-sm table-borderless border-white mt-3">
                                <tbody>
                                    <tr>
                                        <td style="width:50%">
                                        </td>
                                        <td style="width:50%">
                                            <p class="mb-3 text-center" style="font-size:11px;">Tp.HCM, ngày <?= date("d", strtotime($row1['accounting_date'])); ?> tháng <?= date("m", strtotime($row1['accounting_date'])); ?> năm <?= date("Y", strtotime($row1['accounting_date'])); ?></p>
                                            <p class="mb-0 text-center fw-bold" style="font-size:11px;">NGƯỜI NHẬN HÀNG</p>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <p class="mb-0 text-center fw-bold" style="font-size:11px;"></p>
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

        <!-- Bootstrap Toast -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
            <div id="copyToast" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        Copied!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <!-- Click To copy -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const copyElements = document.querySelectorAll("p.click-to-copy");
                const toastEl = document.getElementById("copyToast");
                const toast = new bootstrap.Toast(toastEl);

                copyElements.forEach(element => {
                    element.style.cursor = "pointer";
                    element.addEventListener("click", function() {
                        const text = this.textContent;

                        navigator.clipboard.writeText(text).then(() => {
                            toast.show(); // Show the Bootstrap toast
                        }).catch(err => {
                            console.error("Copy failed", err);
                        });
                    });
                });
            });
        </script>

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.form-thousand').each(function() {
                    const input = $(this);

                    // Apply the mask
                    input.mask('#,##0', {
                        reverse: true
                    });

                    // Format any pre-populated values
                    const value = input.val();
                    if (value) {
                        input.val(value.replace(/\B(?=(\d{3})+(?!\d))/g, ',')); // Add commas
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
    <p class="text-center">Không tìm thấy Đơn Mua Mã #<?= $_GET['id']; ?></p>
<?php
}
?>