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
    $doanhthu = $row_doanhthu['doanhthu'] ?? 0;
}
$sql_cogs = "SELECT SUM(total_after_vat) as cogs FROM nhap WHERE accounting_date BETWEEN '$currendPeriod_start' AND '$currendPeriod_end'";
$res_cogs = mysqli_query($connect, $sql_cogs);
while ($row_cogs = mysqli_fetch_assoc($res_cogs)) {
    $cogs = $row_cogs['cogs'] ?? 0;
}
$gross_profit = $doanhthu - $cogs;

$sql_sga = "SELECT SUM(total_after_vat) as sga FROM chiphi WHERE accounting_date BETWEEN '$currendPeriod_start' AND '$currendPeriod_end'";
$res_sga = mysqli_query($connect, $sql_sga);
while ($row_sga = mysqli_fetch_assoc($res_sga)) {
    $sga = $row_sga['sga'] ?? 0;
}
$chart_after_sga = $gross_profit - $sga;

// $luong = 0;
$sql_luong = "SELECT SUM(total) as luong FROM luong WHERE month BETWEEN '$currendPeriod_start' AND '$currendPeriod_end'";
$res_luong = mysqli_query($connect, $sql_luong);
while ($row_luong = mysqli_fetch_assoc($res_luong)) {
    $luong = $row_luong['luong'] ?? 0;
}

$net_profit = $gross_profit - $sga - $luong;
?>

<div class="ksky-header d-flex align-items-center">
    <h1 class="ksky-title">KSKY</h1>
    <div class="ksky-rings"></div>
</div>

<?php include(__DIR__ . '/../lib/nav-top.php'); ?>
<!-- Menu Section -->
<div class="container">
    <div class="row">
        <div class="col-12 offset-md-2 col-md-8 offset-xl-2 col-xl-8">
            <!-- Quick Link -->
            <div class="rounded-20 bg-white shadow-gg mb-3">
                <div class="p-3">
                    <div class="row">
                        <div class="col-3 px-1 pb-2">
                            <a href="/nhaphang">
                                <div class="text-center bg-white p-0 rounded-20 pt-2">
                                    <p class=""><span class="material-symbols-outlined text-20">download</p>
                                    <p class="text-12">Nhập hàng</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 px-1 pb-2">
                            <a href="/xuathang">
                                <div class="text-center bg-white p-0 rounded-20 pt-2">
                                    <p class=""><span class="material-symbols-outlined text-20">upload</p>
                                    <p class="text-12">Xuất hàng</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 px-1 pb-2">
                            <a href="/chiphi">
                                <div class="text-center bg-white p-0 rounded-20 pt-2">
                                    <p class=""><span class="material-symbols-outlined text-20">remove_circle</p>
                                    <p class="text-12">Chi phí</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 px-1 pb-2">
                            <a href="/luong">
                                <div class="text-center bg-white p-0 rounded-20 pt-2">
                                    <p class=""><span class="material-symbols-outlined text-20">payments</p>
                                    <p class="text-12">Lương</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu -->
            <div class="mb-3 d-lg-none">
                <div class="row">
                    <?php
                    foreach ($menu as $i => $part) {
                    ?>
                        <div class="col-12 rounded my-2">
                            <p class="fw-bold text-16 text-dark mt-1 mb-0"><?= $part['name']; ?></p>
                        </div>
                        <?php

                        foreach ($part["submenu"] as $j => $link) {
                        ?>
                            <div class="col-3 col-xl-3 px-1 pb-2 border-end">
                                <a href="/<?php echo $link['link']; ?>">
                                    <div class="text-center p-0 rounded-20 pt-2">
                                        <p class=""><span class="material-symbols-outlined text-20"><?php echo $link['icon']; ?></span></p>
                                        <p class="text-12"><?php echo $link['name']; ?></p>
                                    </div>
                                </a>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- P&L Section -->
<div class="container">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <!-- Filter bar -->
            <div class="d-flex align-items-center justify-content-between py-3">
                <div>
                    <p class="fw-bold text-20">P&L</p>
                </div>
                <div class="d-flex align-items-center">
                    <a href="/home/?period=<?= $prev_period; ?>&mainview=<?= $mainview; ?>" class="btn btn-sm"><span class="material-symbols-outlined text-14 text-grey lh-base">arrow_back_ios</span></a>
                    <p class="mx-1 px-3 py-2 bg-white border border-grey text-13 rounded fw-bold"><?= $periodName; ?></p>
                    <a href="/home/?period=<?= $next_period; ?>&mainview=<?= $mainview; ?>" class="btn btn-sm"><span class="material-symbols-outlined text-14 text-grey lh-base">arrow_forward_ios</span></a>
                </div>

                <div>
                    <a href="/home/" class="btn btn-sm <?= ($mainview == "quarter") ? "btn-outline-primary" : "border"; ?>">Q</a>
                    <a href="/home/?mainview=year" class="btn btn-sm <?= ($mainview == "year") ? "btn-outline-primary" : "border"; ?>">Y</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <!-- Main Table -->
            <div class="table-responsive mt-4">
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
        </div>

        <div class="col-12 col-md-6" style="margin-bottom: 120px; height: 400px;">
            <!-- Chart -->
            <figure class="highcharts-figure">
                <div id="container"></div>
                <p class="highcharts-description"></p>
            </figure>
            <!-- <div id="waterfall_chart" style="width: 100%; height: 400px;"></div> -->
        </div>
    </div>
</div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/waterfall.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://code.highcharts.com/themes/adaptive.js"></script>
<script type="text/javascript">
    Highcharts.chart("container", {
        chart: {
            type: "waterfall",
        },

        title: {
            text: "P&L <?= $periodName; ?>",
        },

        xAxis: {
            type: "category",
        },

        yAxis: {
            title: {
                text: "",
            },
        },

        legend: {
            enabled: false,
        },

        tooltip: {
            pointFormat: "<b>{point.y:,.0f}</b> đ",
        },

        series: [{
            upColor: "#2cb67d",
            color: "#ff1d50",
            data: [{
                    name: "Doanh thu",
                    y: <?= $doanhthu; ?>,
                },
                {
                    name: "Chi phí đầu vào",
                    y: -<?= $cogs; ?>,
                },
                {
                    name: "Lợi nhuận gộp",
                    isIntermediateSum: true,
                    color: "#cfd9df",
                },
                {
                    name: "Chi phí quản lý",
                    y: -<?= $sga; ?>,
                },
                {
                    name: "Chi phí lương",
                    y: -<?= $luong; ?>,
                },
                {
                    name: "Lợi nhuận ròng",
                    isSum: true,
                    color: "#cfd9df",
                },
            ],
            dataLabels: {
                enabled: true,
                formatter: function() {
                    const rounded = Math.round(this.y / 1000000);
                    return new Intl.NumberFormat().format(rounded) + "M";
                }
            },
            pointPadding: 0,
        }, ],
    })
</script>