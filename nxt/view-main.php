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

    function formatNumberCell($value)
    {
        if ($value > 0) {
            echo "<p class='text-10 fw-bold m-0 p-1 bg-green-highlight'>" . number_format($value, 0) . "</p>";
        } else if ($value < 0) {
            echo "<p class='text-10 fw-bold m-0 p-1 bg-red-highlight'>" . number_format($value, 0) . "</p>";
        } else {
            echo "<p class='text-10 fw-bold'>" . number_format($value, 0) . "</p>";
        }
    }
    ?>
    <div class="d-flex align-items-center justify-content-between p-3 bg-blue-gra">
        <div class="d-flex align-items-center">
            <span class="material-symbols-outlined text-white me-2">shelves</span>
            <p class="text-white fw-bold my-3 text-20">Nhập Xuất Tồn</p>
        </div>

        <div class="d-flex align-items-center">
            <a href="/nxt/?view=sync" class="btn btn-sm btn-light me-2"><span class="material-symbols-outlined text-20 lh-base">sync</span></a>
            <a href="/nxt/api/list" class="btn btn-sm btn-light me-2"><span class="material-symbols-outlined text-20 lh-base">code</span></a>
            <a href="/nxt/download/" class="btn btn-sm btn-light"><span class="material-symbols-outlined text-20 lh-base">download</span></a>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-between p-3">
        <div></div>
        <div class="d-flex align-items-center">
            <a href="/nxt/?quarter=<?= $prev_quarter; ?>" class="btn btn-sm"><span class="material-symbols-outlined text-14 text-grey lh-base">arrow_back_ios</span></a>
            <p class="mx-1 px-3 py-2 bg-white border border-grey text-13 rounded fw-bold"><?= $quarter; ?></p>
            <a href="/nxt/?quarter=<?= $next_quarter; ?>" class="btn btn-sm"><span class="material-symbols-outlined text-14 text-grey lh-base">arrow_forward_ios</span></a>
        </div>
        <div></div>
    </div>

    <div class="p-0 p-md-3" style="padding-bottom: 80px !important;">
        <div class="mb-3 position-relative">
            <input type="search" id="search-input" class="form-control" placeholder="Tìm kiếm mã SP hoặc tên SP...">
            <div id="loading-spinner" style="display:none; position:absolute; right:10px; top:50%; transform:translateY(-50%);">
                <span class="spinner-border spinner-border-sm"></span>
            </div>
        </div>
        <?php
        $sql = "SELECT * FROM nxt WHERE period = '$currentQuarter_start'";
        $res = mysqli_query($connect, $sql);
        $count = mysqli_num_rows($res);
        ?>
        <p id="product-count" class="text-center mb-2"><?= $count; ?> sản phẩm</p>

        <div class="table-responsive vh-100">
            <table class="table table-sm table-bordered table-hovered bg-white">
                <thead class="table-secondary">
                    <th colspan="2">
                    </th>
                    <th colspan="2" class="d-none d-md-table-cell">
                    </th>
                    <th colspan="2">
                        <p class="text-10">Đầu kỳ</p>
                    </th>
                    <th colspan="2">
                        <p class="text-10">Nhập</p>
                    </th>
                    <th colspan="2">
                        <p class="text-10">Xuất</p>
                    </th>
                    <th colspan="2">
                        <p class="text-10">Tồn</p>
                    </th>
                    <th>
                    </th>
                    <th></th>
                </thead>
                <thead class="table-secondary">
                    <th>
                        <p class="text-10">ID SP</p>
                    </th>
                    <th class="d-none d-md-table-cell">
                        <p class="text-10">Mã SP</p>
                    </th>
                    <th>
                        <p class="text-10">Mặt Hàng</p>
                    </th>
                    <th class="d-none d-md-table-cell">
                        <p class="text-10">Đơn vị</p>
                    </th>
                    <th>
                        <p class="text-10">SL</p>
                    </th>
                    <th>
                        <p class="text-10">TT</p>
                    </th>
                    <th>
                        <p class="text-10">SL</p>
                    </th>
                    <th>
                        <p class="text-10">TT</p>
                    </th>
                    <th>
                        <p class="text-10">SL</p>
                    </th>
                    <th>
                        <p class="text-10">TT</p>
                    </th>
                    <th>
                        <p class="text-10">SL</p>
                    </th>
                    <th>
                        <p class="text-10">TT</p>
                    </th>
                    <th>
                        <p class="text-10">Giá BQGQ</p>
                    </th>
                    <th></th>
                </thead>
                <tbody class="table-responsive" style="overflow-y:auto !important; height:200px !important;">
                    <?php
                    $tong_dauky_qty = 0;
                    $tong_dauky_value = 0;
                    $tong_nhap_qty = 0;
                    $tong_nhap_value = 0;
                    $tong_xuat_qty = 0;
                    $tong_xuat_value = 0;
                    $tong_cuoiky_qty = 0;
                    $tong_cuoiky_value = 0;
                    while ($row = mysqli_fetch_array($res)) {
                        $cuoiky_qty = $row['dauky_qty'] + $row['nhap_qty'] - $row['xuat_qty'];
                        if ($cuoiky_qty == 0) {
                            $cuoiky_value = 0;
                        } else {
                            $cuoiky_value = $row['dauky_value'] + $row['nhap_value'] - $row['xuat_value'];
                        }
                    ?>
                        <tr>
                            <td class="text-center">
                                <p class="text-10 fw-bold text-danger"><?= $row['product_id']; ?></p>
                            </td>
                            <?php
                            $sql_product = "SELECT * FROM products WHERE id = '" . $row['product_id'] . "'";
                            $res_product = mysqli_query($connect, $sql_product);
                            while ($row_product = mysqli_fetch_array($res_product)) {
                            ?>
                                <td class="d-none d-md-table-cell">
                                    <p class="text-10 click-to-copy text-wrap"><?= $row_product['product_code']; ?></p>
                                </td>
                                <td>
                                    <p class="text-10 click-to-copy fw-bold text-wrap"><?= $row_product['product_name']; ?></p>
                                </td>
                                <td class=" d-none d-md-table-cell">
                                    <p class="text-10"><?= $row_product['unit']; ?></p>
                                </td>
                            <?php } ?>
                            <td class="p-0">
                                <?php formatNumberCell($row['dauky_qty']); ?>
                            </td>
                            <td class="p-0">
                                <?php formatNumberCell($row['dauky_value']); ?>
                            </td>
                            <td class="p-0">
                                <?php formatNumberCell($row['nhap_qty']); ?>
                            </td>
                            <td class="p-0">
                                <?php formatNumberCell($row['nhap_value']); ?>
                            </td>
                            <td class="p-0">
                                <?php formatNumberCell($row['xuat_qty']); ?>
                            </td>
                            <td class="p-0">
                                <?php formatNumberCell($row['xuat_value']); ?>
                            </td>
                            <td class="p-0">
                                <?php formatNumberCell($cuoiky_qty); ?>
                            </td>
                            <td class="p-0">
                                <?php formatNumberCell($cuoiky_value); ?>
                            </td>
                            <td class="p-0">
                                <p class="text-10"><?= number_format($row['price_weighted'], 0); ?></p>
                            </td>
                            <td><a href="/nxt/?view=transaction&productid=<?= $row['product_id']; ?>" class="btn btn-sm btn-outline-dark btn-detail text-12" data-product="<?= $row['product_id'] ?>">Chi tiết</a></td>

                        </tr>
                    <?php
                        $tong_dauky_qty += $row['dauky_qty'];
                        $tong_dauky_value += $row['dauky_value'];
                        $tong_nhap_qty += $row['nhap_qty'];
                        $tong_nhap_value += $row['nhap_value'];
                        $tong_xuat_qty += $row['xuat_qty'];
                        $tong_xuat_value += $row['xuat_value'];
                        $tong_cuoiky_qty += $cuoiky_qty;
                        $tong_cuoiky_value += $cuoiky_value;
                    }
                    ?>

                    <tr data-product="<?= $row['product_id'] ?>" class="nxt-row">
                        <td class="text-center" colspan="2">
                        </td>
                        <th class="d-none d-md-table-cell" colspan="2">
                        </th>
                        <td class="p-0">
                            <?php formatNumberCell($tong_dauky_qty); ?>
                        </td>
                        <td class="p-0">
                            <?php formatNumberCell($tong_dauky_value); ?>
                        </td>
                        <td class="p-0">
                            <?php formatNumberCell($tong_nhap_qty); ?>
                        </td>
                        <td class="p-0">
                            <?php formatNumberCell($tong_nhap_value); ?>
                        </td>
                        <td class="p-0">
                            <?php formatNumberCell($tong_xuat_qty); ?>
                        </td>
                        <td class="p-0">
                            <?php formatNumberCell($tong_xuat_value); ?>
                        </td>
                        <td class="p-0">
                            <?php formatNumberCell($tong_cuoiky_qty); ?>
                        </td>
                        <td class="p-0">
                            <?php formatNumberCell($tong_cuoiky_value); ?>
                        </td>
                        <td class="p-0">
                        </td>
                    </tr>
                </tbody>
            </table>
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

    <!-- Filter Table -->
    <script>
        function debounce(fn, delay) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => fn.apply(this, args), delay);
            };
        }

        function escapeRegExp(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }

        function highlightText(text, term) {
            if (!term) return text;
            const re = new RegExp(`(${escapeRegExp(term)})`, 'gi');
            return text.replace(re, '<mark>$1</mark>');
        }

        function filterTableContent(cell, term) {
            const p = cell.querySelector('p');
            if (!p) return;

            const originalText = p.getAttribute('data-original-text') || p.textContent;

            // Lưu lại text gốc vào attribute để dùng lại khi clear
            if (!p.getAttribute('data-original-text')) {
                p.setAttribute('data-original-text', originalText);
            }

            if (!term || term.length < 2) {
                p.innerHTML = originalText;
                return;
            }

            const highlighted = highlightText(originalText, term);
            p.innerHTML = highlighted;
        }

        const input = document.getElementById('search-input');
        const spinner = document.getElementById('loading-spinner');
        const rows = document.querySelectorAll("table tbody tr");

        const filterTable = debounce(() => {
            const term = input.value.trim().toLowerCase();
            spinner.style.display = 'inline-block';

            setTimeout(() => {
                rows.forEach(row => {
                    const codeCell = row.cells[1]; // mã sản phẩm
                    const nameCell = row.cells[2]; // tên sản phẩm

                    const code = codeCell.textContent.toLowerCase();
                    const name = nameCell.textContent.toLowerCase();

                    if (term.length < 2 || code.includes(term) || name.includes(term)) {
                        row.style.display = "";
                        filterTableContent(codeCell, term);
                        filterTableContent(nameCell, term);
                    } else {
                        row.style.display = "none";
                    }
                });
                // ✅ Cập nhật số sản phẩm hiển thị
                const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                document.getElementById('product-count').textContent = `${visibleRows.length} sản phẩm`;
                spinner.style.display = 'none';
            }, 100); // chỉ để giả delay loading
        }, 300); // debounce 300ms

        input.addEventListener('keyup', filterTable);
    </script>