    <?php include("../lib/nav.php"); ?>
    <?php
    if (isset($_GET['mainview'])) {
        $mainview = $_GET['mainview'];
    } else {
        $mainview = "bill";
    }
    function getCurrentQuarter()
    {
        $currentMonth = date('n'); // Get the current month as a number (1-12)
        $year = date('Y');
        $quarter = ceil($currentMonth / 3); // Calculate the quarter
        return "{$year}-Q{$quarter}";
    }

    function getQuarterDates($quarterString)
    {
        list($year, $quarter) = explode('-Q', $quarterString);

        $startMonth = ($quarter - 1) * 3 + 1; // Calculate the start month
        $endMonth = $startMonth + 2;         // Calculate the end month

        $startDate = date('Y-m-d', strtotime("{$year}-{$startMonth}-01"));
        $endDate = date('Y-m-t', strtotime("{$year}-{$endMonth}-01"));

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    function getAdjacentQuarters($quarterString)
    {
        list($year, $quarter) = explode('-Q', $quarterString);

        // Calculate previous quarter
        if ($quarter == 1) {
            $previousQuarter = 4;
            $previousYear = $year - 1;
        } else {
            $previousQuarter = $quarter - 1;
            $previousYear = $year;
        }

        // Calculate next quarter
        if ($quarter == 4) {
            $nextQuarter = 1;
            $nextYear = $year + 1;
        } else {
            $nextQuarter = $quarter + 1;
            $nextYear = $year;
        }

        return [
            'previous' => "{$previousYear}-Q{$previousQuarter}",
            'next' => "{$nextYear}-Q{$nextQuarter}",
        ];
    }

    $currentQuarter = getCurrentQuarter();
    $quarter = (isset($_GET['quarter'])) ? $_GET['quarter'] : $currentQuarter;

    $quarter_dates = getQuarterDates($quarter);
    $currentQuarter_start = $quarter_dates['start_date'];
    $currentQuarter_end = $quarter_dates['end_date'];

    $adjacentQuarters = getAdjacentQuarters($quarter);
    $prev_quarter = $adjacentQuarters['previous'];
    $next_quarter = $adjacentQuarters['next'];

    // Count bill records
    $sql_bill = "SELECT * FROM xuat WHERE accounting_date BETWEEN '$currentQuarter_start' AND '$currentQuarter_end'";
    $res_bill = mysqli_query($connect, $sql_bill);
    $count_bill = mysqli_num_rows($res_bill);

    ?>
    <div class="d-flex align-items-center justify-content-between p-3 bg-blue-gra">
        <div class="d-flex align-items-center">
            <span class="material-symbols-outlined text-white me-2">download</span>
            <p class="text-white fw-bold my-3 text-20">Xuất Hàng</p>
        </div>
        <div class="d-grid">
            <a href="/xuathang/?view=add" class="btn btn-light btn-sm px-3">
                <p class="">+ Tạo mới</p>
            </a>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-between p-3">
        <div>
            <a href="/xuathang/?quarter=<?= $quarter; ?>&mainview=bill" class="btn btn-sm <?= ($mainview == "bill") ? "btn-outline-primary" : "border"; ?>"><span class="material-symbols-outlined text-14 lh-base">receipt_long</span></a>
            <a href="/xuathang/?quarter=<?= $quarter; ?>&mainview=item" class="btn btn-sm <?= ($mainview == "item") ? "btn-outline-primary" : "border"; ?>"><span class="material-symbols-outlined text-14 lh-base">format_list_bulleted</span></a>
        </div>
        <div class="d-flex align-items-center">
            <a href="/xuathang/?quarter=<?= $prev_quarter; ?>&mainview=<?= $mainview; ?>" class="btn btn-sm"><span class="material-symbols-outlined text-14 text-grey lh-base">arrow_back_ios</span></a>
            <p class="mx-1 px-3 py-2 bg-white border border-grey text-13 rounded fw-bold"><?= $quarter; ?></p>
            <a href="/xuathang/?quarter=<?= $next_quarter; ?>&mainview=<?= $mainview; ?>" class="btn btn-sm"><span class="material-symbols-outlined text-14 text-grey lh-base">arrow_forward_ios</span></a>
        </div>
        <div class="dropdown">
            <button class="btn btn-sm border" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="material-symbols-outlined text-14 lh-base">more_vert</span></button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="/xuathang/api/list/">
                        <div class="d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined text-14">code</span>
                            <p class="text-12">API Xuất</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="/xuathang/api/detail/">
                        <div class="d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined text-14">code</span>
                            <p class="text-12">API Xuất Detail</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="/xuathang/download/ao/">
                        <div class="d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined text-14">download</span>
                            <p class="text-12">Tải file Xuất (.xlsx)</p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="p-0 p-md-3" style="padding-bottom: 80px !important;">
        <?php
        if ($mainview == "bill") {
        ?>
            <p class="text-center mb-2"><?= $count_bill; ?> đơn xuất</p>
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hovered bg-white">
                    <thead class="table-secondary">
                        <th class="d-none d-md-table-cell">
                            <p class="text-10">Số HĐ</p>
                        </th>
                        <th>
                            <p class="text-10">ID KSKY</p>
                        </th>
                        <th class="d-none d-md-table-cell">
                            <p class="text-10">Công ty</p>
                        </th>
                        <th class="d-none d-md-table-cell">
                            <p class="text-end text-10">TC</p>
                        </th>
                        <th class="d-none d-md-table-cell">
                            <p class="text-end text-10">TC Sau VAT</p>
                        </th>
                        <th class="d-none d-md-table-cell">
                            <p class="text-10">%VAT</p>
                        </th>
                        <th>
                            <p class="text-10">Chi Tiết</p>
                        </th>
                    </thead>
                    <tbody>
                        <?php
                        $sql_date = "SELECT DISTINCT accounting_date FROM xuat WHERE accounting_date BETWEEN '$currentQuarter_start' AND '$currentQuarter_end' ORDER BY accounting_date DESC";
                        $res_date = mysqli_query($connect, $sql_date);
                        $count_date = mysqli_num_rows($res_date);
                        while ($row_date = mysqli_fetch_array($res_date)) {
                        ?>
                            <tr>
                                <td colspan="2" class="table-warning">
                                    <p class="text-10 fw-bold"><?= date("d/m", strtotime($row_date['accounting_date'])); ?></p>
                                </td>
                                <td colspan="5" class="d-none d-md-table-cell table-warning"></td>
                            </tr>
                            <?php
                            $sql1 = "SELECT * FROM xuat WHERE accounting_date = '" . $row_date['accounting_date'] . "' ORDER BY id DESC";
                            $res1 = mysqli_query($connect, $sql1);
                            $count1 = mysqli_num_rows($res1);
                            while ($row1 = mysqli_fetch_array($res1)) {
                            ?>
                                <tr style="cursor: pointer;" onclick="location.href='/xuathang/?view=detail&id=<?= $row1['id']; ?>'">
                                    <td class="d-none d-md-table-cell">
                                        <div class="d-flex align-items-center">
                                            <span class="material-symbols-outlined text-secondary me-1 text-14 lh-base">checkbook</span>
                                            <p class="text-10"><?= $row1['accounting_xuat_id']; ?></p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="material-symbols-outlined text-secondary me-1 text-10 lh-base">receipt_long</span>
                                            <p class="text-10 text-danger fw-bold"><?= $row1['ksky_xuat_id']; ?></p>
                                        </div>
                                        <div class="d-md-none">
                                            <div class="d-flex align-items-center">
                                                <span class="material-symbols-outlined text-secondary me-1 text-14 lh-base">checkbook</span>
                                                <p class="text-10"><?= $row1['accounting_xuat_id']; ?></p>
                                            </div>
                                            <p class="text-10 text-success text-end fw-bold"><?= number_format($row1['total_before_vat'], 0); ?> đ</p>
                                            <p class="text-10 text-end fw-bold"><?= number_format($row1['total_after_vat'], 0); ?> đ</p>
                                            <p class="text-10 text-primary text-end fw-bold">VAT <?= number_format($row1['vat_rate'], 0); ?>%</p>
                                        </div>

                                    </td>
                                    <td class="d-none d-md-table-cell" style="min-width:200px">
                                        <div class="d-flex align-items-center">
                                            <span class="material-symbols-outlined text-secondary text-14 me-2 lh-base">business</span>
                                            <div>
                                                <p class="text-10 text-wrap"><?= $row1['company_name']; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <?php
                                    $sql_tong_that = "SELECT SUM(total_before_vat) as tong_that FROM xuat_detail WHERE xuat_id ='" . $row1['id'] . "' AND is_it = 0";
                                    $res_tong_that = mysqli_query($connect, $sql_tong_that);
                                    while ($row_tong_that = mysqli_fetch_assoc($res_tong_that)) {
                                        $tong_that = $row_tong_that['tong_that'];
                                        $tong_that_after_vat = $tong_that * (1 + $row1['vat_rate'] / 100);
                                    }
                                    ?>
                                    <td class="d-none d-md-table-cell">
                                        <p class="text-10 text-success text-end fw-bold"><?= number_format($tong_that, 0); ?> đ</p>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <p class="text-10 text-end fw-bold"><?= number_format($tong_that_after_vat, 0); ?> đ</p>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <p class="text-10 text-primary text-end fw-bold"><?= number_format($row1['vat_rate'], 0); ?>%</p>
                                    </td>
                                    <td>
                                        <?php
                                        $sql_line = "SELECT * FROM xuat_detail WHERE xuat_id ='" . $row1['id'] . "' AND is_it = 0";
                                        $res_line = mysqli_query($connect, $sql_line);
                                        while ($row_line = mysqli_fetch_assoc($res_line)) {
                                        ?>
                                            <p class="text-10 text-wrap"><b><?= $row_line['qty'] . "</b> x " . $row_line['product_name_display']; ?></p>
                                            <?php
                                            $sql_ao = "SELECT * FROM xuat_detail WHERE xuat_id ='" . $row1['id'] . "' AND is_it = 1 AND is_it_for = '" . $row_line['id'] . "'";
                                            $res_ao = mysqli_query($connect, $sql_ao);
                                            while ($row_ao = mysqli_fetch_assoc($res_ao)) {
                                            ?>
                                                <p class="text-10 text-wrap"><span class="text-12 text-danger">★</span> <b><?= $row_ao['qty'] . "</b> x " . $row_ao['product_name_display']; ?></p>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        <?php
        } else {
            $sql2 = "SELECT * FROM xuat_detail WHERE (accounting_date BETWEEN '$currentQuarter_start' AND '$currentQuarter_end') and is_it=0 ORDER BY accounting_date DESC,xuat_id";
            $res2 = mysqli_query($connect, $sql2);
            $count2 = mysqli_num_rows($res2);
        ?>
            <p class="text-center mb-2"><?= $count2; ?> đơn xuất</p>
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hovered bg-white">
                    <thead class="table-secondary">
                        <th class="text-center">
                            <p class="text-10">Ngày</p>
                        </th>
                        <th class="d-none d-md-table-cell">
                            <p class="text-10">Số HĐ</p>
                        </th>
                        <th>
                            <p class="text-10">ID KSKY</p>
                        </th>
                        <th>
                            <p class="text-10">Loại</p>
                        </th>
                        <th class="d-none d-md-table-cell">
                            <p class="text-10">Mã sản phẩm</p>
                        </th>
                        <th>
                            <p class="text-10">Tên sản phẩm</p>
                        </th>
                        <th>
                            <p class="text-end text-10">ĐVT</p>
                        </th>
                        <th class="d-none d-md-table-cell">
                            <p class="text-end text-10">SL</p>
                        </th>
                        <th>
                            <p class="text-10">Giá</p>
                        </th>
                        <th>
                            <p class="text-10">Thành tiền</p>
                        </th>
                    </thead>
                    <tbody>
                        <?php
                        while ($row2 = mysqli_fetch_array($res2)) {
                        ?>
                            <tr style="cursor: pointer;" onclick="location.href='/xuathang/?view=detail&id=<?= $row2['xuat_id']; ?>'">
                                <td class="text-center">
                                    <p class="text-10 fw-bold"><?= date("d/m", strtotime($row2['accounting_date'])); ?></p>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <div class="d-flex align-items-center">
                                        <span class="material-symbols-outlined text-secondary me-1 text-14 lh-base">checkbook</span>
                                        <p class="text-10"><?= $row2['accounting_xuat_id']; ?></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="material-symbols-outlined text-secondary me-1 text-10 lh-base">receipt_long</span>
                                        <p class="text-10 text-danger fw-bold"><?= $row2['ksky_xuat_id']; ?></p>
                                    </div>
                                </td>
                                <td>
                                    <span class="material-symbols-outlined text-14 text-success">psychology_alt</span>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <div class="d-flex align-items-center">
                                        <span class="material-symbols-outlined text-secondary text-14 me-2 lh-base">barcode</span>
                                        <div>
                                            <p class="text-10"><?= $row2['product_code']; ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="material-symbols-outlined text-secondary text-14 me-2 lh-base">package_2</span>
                                        <div>
                                            <p class="text-10 fw-bold text-wrap"><?= $row2['product_name_display']; ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-10 text-end"><?= $row2['unit']; ?></p>
                                </td>
                                <td>
                                    <p class="text-10 text-end"><?= number_format($row2['qty'], 0); ?></p>
                                </td>
                                <td class="d-none d-md-table-cell">
                                    <p class="text-10 text-end"><?= number_format($row2['price'], 0); ?></p>
                                </td>
                                <td>
                                    <p class="text-10 text-primary text-end fw-bold"><?= number_format($row2['total_before_vat'], 0); ?> đ</p>
                                </td>
                            </tr>
                            <?php
                            $sql_list_ao = "SELECT * FROM xuat_detail WHERE (accounting_date BETWEEN '$currentQuarter_start' AND '$currentQuarter_end') and is_it=1 AND is_it_for='" . $row2['id'] . "' ORDER BY accounting_date DESC,xuat_id";
                            $res_list_ao = mysqli_query($connect, $sql_list_ao);
                            $count_list_ao = mysqli_num_rows($res_list_ao);
                            if ($count_list_ao > 0) {
                                while ($row_list_ao = mysqli_fetch_array($res_list_ao)) {
                            ?>

                                    <tr style="cursor: pointer;" onclick="location.href='/xuathang/?view=detail&id=<?= $row_list_ao['xuat_id']; ?>'">
                                        <td class="text-center">
                                            <p class="text-10 fw-bold"><?= date("d/m", strtotime($row_list_ao['accounting_date'])); ?></p>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <div class="d-flex align-items-center">
                                                <span class="material-symbols-outlined text-secondary me-1 text-14 lh-base">checkbook</span>
                                                <p class="text-10"><?= $row_list_ao['accounting_xuat_id']; ?></p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="material-symbols-outlined text-secondary me-1 text-10 lh-base">receipt_long</span>
                                                <p class="text-10 text-danger fw-bold"><?= $row_list_ao['ksky_xuat_id']; ?></p>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($row_list_ao['is_it']) { ?>
                                                <span class="text-14 text-danger">★</span>
                                            <?php } ?>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <div class="d-flex align-items-center">
                                                <span class="material-symbols-outlined text-secondary text-14 me-2 lh-base">barcode</span>
                                                <div>
                                                    <p class="text-10"><?= $row_list_ao['product_code']; ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="material-symbols-outlined text-14 text-danger">
                                                    subdirectory_arrow_right
                                                </span>
                                                <div>
                                                    <p class="text-10 fw-bold text-wrap"><?= $row_list_ao['product_name_display']; ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-10 text-end"><?= $row_list_ao['unit']; ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10 text-end"><?= number_format($row_list_ao['qty'], 0); ?></p>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <p class="text-10 text-end"><?= number_format($row_list_ao['price'], 0); ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10 text-primary text-end fw-bold"><?= number_format($row_list_ao['total_before_vat'], 0); ?> đ</p>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>