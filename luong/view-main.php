    <?php include("../lib/nav.php"); ?>
    <?php
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

    ?>
    <!-- Head -->
    <div class="p-0">
        <div class="d-flex align-items-center justify-content-between p-3 bg-blue-gra">
            <div class="d-flex align-items-center">
                <span class="material-symbols-outlined text-white me-2">payments</span>
                <p class="text-white fw-bold my-3 text-20">Lương</p>
            </div>
            <div class="d-grid">
                <a href="/luong/?view=add" class="btn btn-light btn-sm">
                    <p class="">+ Tạo mới</p>
                </a>
            </div>
        </div>
    </div>
    <!-- Filter Bar -->
    <div class="d-flex align-items-center justify-content-between p-3">
        <div>
        </div>
        <div class="d-flex align-items-center">
            <a href="/luong/?quarter=<?= $prev_quarter; ?>" class="btn btn-sm"><span class="material-symbols-outlined text-14 text-grey lh-base">arrow_back_ios</span></a>
            <p class="mx-1 px-3 py-2 bg-white border border-grey text-13 rounded fw-bold"><?= $quarter; ?></p>
            <a href="/luong/?quarter=<?= $next_quarter; ?>" class="btn btn-sm"><span class="material-symbols-outlined text-14 text-grey lh-base">arrow_forward_ios</span></a>
        </div>
        <div class="dropdown">
            <button class="btn btn-sm border" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="material-symbols-outlined text-14 lh-base">more_vert</span></button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="/luong/api/list/">
                        <div class="d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined text-14">code</span>
                            <p class="text-12">API Lương</p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Table -->
    <div class="p-0 p-md-3" style="padding-bottom: 80px !important;">
        <?php
        $sql1 = "SELECT * FROM luong WHERE month BETWEEN '$currentQuarter_start' AND '$currentQuarter_end' ORDER BY month DESC";
        $res1 = mysqli_query($connect, $sql1);
        $count1 = mysqli_num_rows($res1);

        $sql_sum = "SELECT SUM(total) as sum FROM luong WHERE month BETWEEN '$currentQuarter_start' AND '$currentQuarter_end' ORDER BY month DESC";
        $res_sum = mysqli_query($connect, $sql_sum);
        while ($row_sum = mysqli_fetch_assoc($res_sum)) {
            $sum = $row_sum['sum'];
        }
        ?>
        <p class="text-center mb-2"><span class="text-danger fw-bold"><?= $count1; ?></span> khoản lương | <span class="text-danger fw-bold"><?= number_format($sum ?? 0, 0); ?></span> đ</p>
        <?php if ($sum > 0) { ?>
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hovered bg-white">
                    <thead class="table-secondary">
                        <th>
                            <p class="text-10">CCCD</p>
                        </th>
                        <th>
                            <p class="text-10">Tên</p>
                        </th>
                        <th class="d-none d-md-table-cell">
                            <p class="text-10">MST</p>
                        </th>
                        <th class="d-none d-md-table-cell">
                            <p class="text-10">Vị trí</p>
                        </th>
                        <th>
                            <p class="text-10 text-end">Tổng</p>
                        </th>
                    </thead>
                    <tbody>
                        <?php
                        $sql_month = "SELECT month, SUM(total) as total FROM luong WHERE month BETWEEN '$currentQuarter_start' AND '$currentQuarter_end' GROUP BY month ORDER BY month DESC";
                        $res_month = mysqli_query($connect, $sql_month);
                        while ($row_month = mysqli_fetch_array($res_month)) {
                        ?>
                            <tr style="cursor: pointer;" onclick="location.href='/luong/?view=detail&id=<?= $row['id']; ?>'">
                                <td colspan="3" class="table-warning">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="text-10 fw-bold"><?= date("Y-m", strtotime($row_month['month'])); ?></p>
                                        <p class="text-10 fw-bold text-primary"><?= number_format($row_month['total']); ?> đ</p>
                                    </div>
                                </td>
                                <td colspan="2" class="table-warning d-none d-md-table-cell">
                                </td>
                            </tr>
                            <?php
                            $sql = "SELECT * FROM luong WHERE month ='" . $row_month['month'] . "' ORDER BY created DESC";
                            $res = mysqli_query($connect, $sql);
                            while ($row = mysqli_fetch_array($res)) {
                            ?>
                                <tr style="cursor: pointer;" onclick="location.href='/luong/?view=detail&id=<?= $row['id']; ?>'">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="material-symbols-outlined text-secondary me-1 text-10 lh-base">badge</span>
                                            <p class="text-10"><?= $row['cccd']; ?></p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="material-symbols-outlined text-secondary me-1 text-14 lh-base">account_circle</span>
                                            <p class="text-10 text-danger fw-bold"><?= $row['full_name']; ?></p>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <div class="d-flex align-items-center">
                                            <span class="material-symbols-outlined text-secondary text-14 me-2 lh-base">barcode</span>
                                            <p class="text-10 fw-bold text-wrap"><?= $row['mst']; ?></p>
                                        </div>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <div class="d-flex align-items-center">
                                            <span class="material-symbols-outlined text-secondary text-14 me-2 lh-base">business</span>
                                            <div>
                                                <p class="text-10 fw-bold text-wrap"><?= $row['title']; ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-10 text-end fw-bold text-success"><?= number_format($row['total'], 0); ?> đ</p>
                                    </td>
                                </tr>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>