    <?php
    include __DIR__ . "/../lib/nav.php";
    ?>
    <div class="px-3 py-3 py-lg-3">
        <div class="d-flex align-items-center">
            <span class="material-symbols-outlined text-white me-2">business</span>
            <p class="text-white fw-bold my-3 text-20">Nhà cung cấp</p>
        </div>
    </div>
    <div class="bg-white vh-100">
        <div class="row mx-0">
            <div class="col-12 mt-3" style="padding-bottom: 80px;">

                <?php
                if (isset($_GET['search'])) {
                ?>
                    <a href="/suppliers">
                        <div class="d-flex mb-3">
                            <span class="material-symbols-outlined text-16 text-primary">arrow_back_ios</span>
                            <p class="text-12 text-primary">Tất cả nhà cung cấp</p>
                        </div>
                    </a>
                <?php
                }
                ?>
                <form action="/suppliers" method="get" class="my-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <input type="search" class="form-control form-control-sm me-3" name="search" placeholder="Tìm tên công ty" value="<?= (isset($_GET['search'])) ? $_GET['search'] : ""; ?>">
                    </div>
                </form>

                <?php
                $sql1 = "SELECT DISTINCT company_name FROM nhap";
                if (isset($_GET['search'])) {
                    $search = mysqli_escape_string($connect, $_GET['search']);
                    $sql1_search_part = " WHERE company_name LIKE '%$search%' OR company_name LIKE '%$search%'";
                } else {
                    $sql1_search_part = "";
                }
                $sql1_order_part = " ORDER BY company_name";
                $sql1 .= $sql1_search_part;
                $sql1 .= $sql1_order_part;
                $res1 = mysqli_query($connect, $sql1);
                $count1 = mysqli_num_rows($res1);
                if ($count1 == 0) {
                ?>
                    <p class="text-12 my-3">0 nhà cung cấp</p>
                <?php
                } else {
                ?>
                    <p class="text-12 mb-2 text-primary text-center"><?= $count1; ?> nhà cung cấp</p>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hovered bg-white">
                            <thead class="table-secondary">
                                <th>
                                    <p class="text-10">STT</p>
                                </th>
                                <th>
                                    <p class="text-10 fw-bold">Tên nhà cung cấp</p>
                                </th>
                                <th>
                                    <p class="text-10 fw-bold">Số đơn</p>
                                </th>
                                <th>
                                    <p class="text-10 fw-bold">Tổng</p>
                                </th>
                            </thead>
                            <tbody>
                                <?php
                                $stt = 0;
                                while ($row1 = mysqli_fetch_array($res1)) {
                                    $stt++;
                                ?>
                                    <tr>
                                        <td>
                                            <p class="text-10"><?= $stt; ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10 text-wrap fw-bold"><?= $row1['company_name']; ?></p>
                                        </td>

                                        <?php
                                        $sql_bill = "SELECT COUNT(id) as count, SUM(total_before_vat) as value FROM nhap WHERE company_name = '" . $row1['company_name'] . "'";
                                        $res_bill = mysqli_query($connect, $sql_bill);
                                        while ($row_bill = mysqli_fetch_array($res_bill)) {
                                        ?>
                                            <td>
                                                <p class="text-10 fw-bold text-danger text-end"><?= number_format($row_bill['count'], 0); ?> đơn</p>
                                            </td>
                                            <td>
                                                <p class="text-10 fw-bold text-danger text-end"><?= number_format($row_bill['value'], 0); ?> đ</p>
                                            </td>
                                        <?php
                                        }
                                        ?>
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
                <?php
                ?>
            </div>
        </div>
    </div>