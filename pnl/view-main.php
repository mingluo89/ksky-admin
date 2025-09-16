    <?php
    include __DIR__ . "/../lib/nav.php";

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

    $mainview = (isset($_GET['mainview'])) ? $_GET['mainview'] : "quarter";
    if ($mainview == "year") {
        $currentYear = date('Y');
        $periodName = (isset($_GET['period'])) ? $_GET['period'] : $currentYear;

        $currentYear_start = "$periodName-01-01";
        $currentYear_end   = "$periodName-12-31";
        $prev_year = $periodName - 1;
        $next_year = $periodName + 1;

        $currendPeriod_start = $currentYear_start;
        $currendPeriod_end = $currentYear_end;
        $prev_period = $prev_year;
        $next_period = $next_year;
    } else if ($mainview == "quarter") {
        $currentQuarter = getCurrentQuarter();
        $periodName = (isset($_GET['period'])) ? $_GET['period'] : $currentQuarter;

        $quarter_dates = getQuarterDates($periodName);
        $currentQuarter_start = $quarter_dates['start_date'];
        $currentQuarter_end = $quarter_dates['end_date'];
        $adjacentQuarters = getAdjacentQuarters($periodName);
        $prev_quarter = $adjacentQuarters['previous'];
        $next_quarter = $adjacentQuarters['next'];

        $currendPeriod_start = $currentQuarter_start;
        $currendPeriod_end = $currentQuarter_end;
        $prev_period = $prev_quarter;
        $next_period = $next_quarter;
    }

    // Calculate
    $sql_doanhthu = "SELECT SUM(total_after_vat) as doanhthu FROM xuat WHERE accounting_date BETWEEN '$currendPeriod_start' AND '$currendPeriod_end'";
    $res_doanhthu = mysqli_query($connect, $sql_doanhthu);
    while ($row_doanhthu = mysqli_fetch_assoc($res_doanhthu)) {
        $doanhthu = $row_doanhthu['doanhthu'];
    }
    $sql_cogs = "SELECT SUM(total_after_vat) as cogs FROM nhap WHERE accounting_date BETWEEN '$currendPeriod_start' AND '$currendPeriod_end'";
    $res_cogs = mysqli_query($connect, $sql_cogs);
    while ($row_cogs = mysqli_fetch_assoc($res_cogs)) {
        $cogs = $row_cogs['cogs'];
    }
    $gross_profit = $doanhthu - $cogs;

    $sql_sga = "SELECT SUM(total_after_vat) as sga FROM chiphi WHERE accounting_date BETWEEN '$currendPeriod_start' AND '$currendPeriod_end'";
    $res_sga = mysqli_query($connect, $sql_sga);
    while ($row_sga = mysqli_fetch_assoc($res_sga)) {
        $sga = $row_sga['sga'];
    }
    $chart_after_sga = $gross_profit - $sga;

    // $luong = 0;
    $sql_luong = "SELECT SUM(total) as luong FROM luong WHERE month BETWEEN '$currendPeriod_start' AND '$currendPeriod_end'";
    $res_luong = mysqli_query($connect, $sql_luong);
    while ($row_luong = mysqli_fetch_assoc($res_luong)) {
        $luong = $row_luong['luong'];
    }

    $net_profit = $gross_profit - $sga - $luong;
    ?>
    <div class="px-3 py-3 py-lg-3">
        <div class="d-flex align-items-center">
            <span class="material-symbols-outlined text-white me-2">receipt_long</span>
            <p class="text-white fw-bold my-3 text-20">P&L</p>
        </div>
    </div>
    <div class="bg-white vh-100">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 offset-md-3 mt-3" style="padding-bottom: 80px;">
                    <!-- Filter bar -->
                    <div class="d-flex align-items-center justify-content-between py-3">
                        <div>
                        </div>
                        <div class="d-flex align-items-center">
                            <a href="/pnl/?period=<?= $prev_period; ?>&mainview=<?= $mainview; ?>" class="btn btn-sm"><span class="material-symbols-outlined text-14 text-grey lh-base">arrow_back_ios</span></a>
                            <p class="mx-1 px-3 py-2 bg-white border border-grey text-13 rounded fw-bold"><?= $periodName; ?></p>
                            <a href="/pnl/?period=<?= $next_period; ?>&mainview=<?= $mainview; ?>" class="btn btn-sm"><span class="material-symbols-outlined text-14 text-grey lh-base">arrow_forward_ios</span></a>
                        </div>

                        <div>
                            <a href="/pnl/" class="btn btn-sm <?= ($mainview == "quarter") ? "btn-outline-primary" : "border"; ?>">Q</a>
                            <a href="/pnl/?mainview=year" class="btn btn-sm <?= ($mainview == "year") ? "btn-outline-primary" : "border"; ?>">Y</a>
                        </div>
                    </div>

                    <!-- Main Table -->
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hovered bg-white">
                            <thead class="table-secondary">
                                <th>
                                    <p class="text-12">Mã</p>
                                </th>
                                <th>
                                    <p class="text-12 fw-bold">Chỉ tiêu</p>
                                </th>
                                <th>
                                    <p class="text-12 text-end"><?= $periodName; ?></p>
                                </th>
                                <th>
                                    <p class="text-12 text-end">%</p>
                                </th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <p class="text-12">1</p>
                                    </td>
                                    <td>
                                        <p class="text-12 text-wrap">Doanh thu</p>
                                    </td>
                                    <td>
                                        <p class="text-12 fw-bold text-danger text-end"><?= safe_number($doanhthu) ?></p>
                                    </td>
                                    <td>
                                        <p class="text-12 text-dark text-end">100%</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="text-12">2</p>
                                    </td>
                                    <td>
                                        <p class="text-12 text-wrap">- Chi phí đầu vào (COGS)</p>
                                    </td>
                                    <td>
                                        <p class="text-12 fw-bold text-danger text-end"><?= safe_number($cogs) ?></p>
                                    </td>
                                    <td>
                                        <p class="text-12 text-dark text-end"><?= safe_percent($cogs, $doanhthu) ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="text-12">3</p>
                                    </td>
                                    <td>
                                        <p class="text-12 text-wrap fw-bold">Lợi nhuận gộp</p>
                                    </td>
                                    <td>
                                        <p class="text-12 fw-bold text-danger text-end"><?= safe_number($gross_profit) ?></p>
                                    </td>
                                    <td>
                                        <p class="text-12 text-dark text-end"><?= safe_percent($gross_profit, $doanhthu) ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="text-12">4</p>
                                    </td>
                                    <td>
                                        <p class="text-12 text-wrap">- Chi phí quản lý doanh nghiệp</p>
                                    </td>
                                    <td>
                                        <p class="text-12 fw-bold text-danger text-end"><?= safe_number($sga) ?></p>
                                    </td>
                                    <td>
                                        <p class="text-12 text-dark text-end"><?= safe_percent($sga, $doanhthu) ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="text-12">5</p>
                                    </td>
                                    <td>
                                        <p class="text-12 text-wrap">- Chi phí lương</p>
                                    </td>
                                    <td>
                                        <p class="text-12 fw-bold text-danger text-end"><?= safe_number($luong) ?></p>
                                    </td>
                                    <td>
                                        <p class="text-12 text-dark text-end"><?= safe_percent($luong, $doanhthu) ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="text-12">6</p>
                                    </td>
                                    <td>
                                        <p class="text-12 text-wrap fw-bold">Lợi nhuận ròng trước thuế</p>
                                    </td>
                                    <td>
                                        <p class="text-12 fw-bold text-danger text-end"><?= safe_number($net_profit) ?></p>
                                    </td>
                                    <td>
                                        <p class="text-12 text-dark text-end"><?= safe_percent($net_profit, $doanhthu) ?></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Chart -->
                    <div id="waterfall_chart" style="width: 100%; height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.charts.load('current', {
            packages: ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            const data = google.visualization.arrayToDataTable([
                ['Doanh thu', 0, 0, <?= $doanhthu; ?>, <?= $doanhthu; ?>],
                ['Chi phí đầu vào', <?= $doanhthu; ?>, <?= $doanhthu; ?>, <?= $gross_profit; ?>, <?= $gross_profit; ?>],
                ['Lợi nhuận gộp', <?= $gross_profit; ?>, <?= $gross_profit; ?>, <?= $gross_profit; ?>, <?= $gross_profit; ?>],
                ['Chi phí quản lý', <?= $gross_profit; ?>, <?= $gross_profit; ?>, <?= $net_profit; ?>, <?= $net_profit; ?>],
                ['Lợi nhuận ròng', <?= $net_profit; ?>, <?= $net_profit; ?>, <?= $net_profit; ?>, <?= $net_profit; ?>]
            ], true);

            const options = {
                title: 'P&L <?= $periodName; ?>',
                legend: 'none',
                bar: {
                    groupWidth: '100%'
                }, // Remove space between bars.
                candlestick: {
                    fallingColor: {
                        strokeWidth: 0,
                        fill: '#f27171'
                    }, // red
                    risingColor: {
                        strokeWidth: 0,
                        fill: '#61b58f'
                    } // green
                },
                chartArea: {
                    left: 70,
                    top: 50,
                    width: '95%',
                    height: '80%'
                },
                vAxis: {
                    textStyle: {
                        fontSize: 10
                    },
                    baselineColor: '#333', // Dark axis line (simulated border)
                    gridlines: {
                        color: '#ccc', // Light gridlines
                        count: 5
                    }
                }
            };

            var chart = new google.visualization.CandlestickChart(document.getElementById('waterfall_chart'));
            chart.draw(data, options);
        }
    </script>