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
    <div class="d-flex align-items-center justify-content-between p-3 bg-blue-gra">
        <div class="d-flex align-items-center">
            <span class="material-symbols-outlined text-white me-2">remove_circle</span>
            <p class="text-white fw-bold my-3 text-20">Chi phí</p>
        </div>
        <div class="d-grid">
            <a href="/chiphi/?view=add" class="btn btn-light btn-sm">
                <p class="">+ Tạo mới</p>
            </a>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-between p-3">
        <div>
        </div>
        <div class="d-flex align-items-center">
            <a href="/chiphi/?quarter=<?= $prev_quarter; ?>" class="btn btn-sm"><span class="material-symbols-outlined text-14 text-grey lh-base">arrow_back_ios</span></a>
            <p class="mx-1 px-3 py-2 bg-white border border-grey text-13 rounded fw-bold"><?= $quarter; ?></p>
            <a href="/chiphi/?quarter=<?= $next_quarter; ?>" class="btn btn-sm"><span class="material-symbols-outlined text-14 text-grey lh-base">arrow_forward_ios</span></a>
        </div>
        <div class="dropdown">
            <button class="btn btn-sm border" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="material-symbols-outlined text-14 lh-base">more_vert</span></button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="/chiphi/api/list/">
                        <div class="d-flex align-items-center gap-1">
                            <span class="material-symbols-outlined text-14">code</span>
                            <p class="text-12">API Chi Phí</p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="p-0 p-md-3" style="padding-bottom: 80px !important;">
        <?php
        $sql1 = "SELECT * FROM chiphi WHERE accounting_date BETWEEN '$currentQuarter_start' AND '$currentQuarter_end' ORDER BY accounting_date DESC";
        $res1 = mysqli_query($connect, $sql1);
        $count1 = mysqli_num_rows($res1);
        ?>
        <p class="text-center mb-2"><?= $count1; ?> hoá đơn chi phí</p>
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hovered bg-white">
                <thead class="table-secondary">
                    <th class="text-center">
                        <p class="text-10">Ngày</p>
                    </th>
                    <th>
                        <p class="text-10">Số HĐ</p>
                    </th>
                    <th class="d-none d-md-table-cell">
                        <p class="text-10">ID KSKY</p>
                    </th>
                    <th>
                        <p class="text-10">Công ty</p>
                    </th>
                    <th>
                        <p class="text-end text-10">TC trước VAT</p>
                    </th>
                    <th>
                        <p class="text-end text-10">TC sau VAT</p>
                    </th>
                    <th>
                        <p class="text-10">Mục</p>
                    </th>
                    <th>
                    </th>
                </thead>
                <tbody>
                    <?php
                    while ($row1 = mysqli_fetch_array($res1)) {
                    ?>
                        <tr>
                            <td class="text-center">
                                <p class="text-10 fw-bold"><?= date("d/m", strtotime($row1['accounting_date'])); ?></p>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="material-symbols-outlined text-secondary me-1 text-14 lh-base">checkbook</span>
                                    <p class="text-10 text-danger fw-bold"><?= $row1['accounting_chiphi_id']; ?></p>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <div class="d-flex align-items-center">
                                    <span class="material-symbols-outlined text-secondary me-1 text-10 lh-base">receipt_long</span>
                                    <p class="text-10"><?= $row1['ksky_chiphi_id']; ?></p>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="material-symbols-outlined text-secondary text-14 me-2 lh-base">business</span>
                                    <div>
                                        <p class="text-10 fw-bold text-wrap" style="min-width: 150px !important;"><?= $row1['company_name']; ?></p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="text-10 text-end fw-bold text-success"><?= number_format($row1['total_before_vat'], 0); ?> đ</p>
                            </td>
                            <td>
                                <p class="text-10 text-end fw-bold text-success"><?= number_format($row1['total_after_vat'], 0); ?> đ</p>
                            </td>
                            <td>
                                <p class="text-10"><?= $row1['category']; ?></p>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-link" href="/chiphi/?view=edit&id=<?= $row1['id']; ?>"><span class="material-symbols-outlined text-14 text-dark">edit</span></a>

                                <a class="btn btn-sm btn-link" href="/chiphi/?view=delete&id=<?= $row1['id']; ?>"><span class="material-symbols-outlined text-14 text-danger">delete</span></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>