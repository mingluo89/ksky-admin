    <?php
    include("../lib/nav.php");
    ?>
    <div class="d-flex align-items-center justify-content-between p-3 bg-blue-gra">
        <div class="d-flex align-items-center">
            <span class="material-symbols-outlined text-white me-2">package_2</span>
            <p class="text-white fw-bold my-3 text-20">Sản phẩm</p>
        </div>
        <div class="d-grid">
            <a href="/products/?view=add" class="btn btn-light btn-sm">
                <p class="">+ Tạo mới</p>
            </a>
        </div>
    </div>
    <div class="bg-white">
        <div class="row mx-0">
            <div class="col-12 mt-3" style="padding-bottom: 80px;">

                <?php
                if (isset($_GET['search'])) {
                ?>
                    <a href="/products">
                        <div class="d-flex mb-3">
                            <span class="material-symbols-outlined text-16 text-primary">arrow_back_ios</span>
                            <p class="text-12 text-primary">Tất cả sản phẩm</p>
                        </div>
                    </a>
                <?php
                }
                ?>
                <form action="/products" method="get" class="my-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <input type="search" class="form-control form-control-sm me-3" name="search" placeholder="Tìm tên sản phẩm, mã" value="<?= (isset($_GET['search'])) ? $_GET['search'] : ""; ?>">
                    </div>
                </form>

                <?php
                $sql1 = "SELECT * FROM products";
                if (isset($_GET['search'])) {
                    $search = mysqli_escape_string($connect, $_GET['search']);
                    $sql1_search_part = " WHERE product_name LIKE '%$search%' OR product_code LIKE '%$search%'";
                } else {
                    $sql1_search_part = "";
                }
                $sql1_order_part = " ORDER BY id DESC";
                $sql1 .= $sql1_search_part;
                $sql1 .= $sql1_order_part;
                $res1 = mysqli_query($connect, $sql1);
                $count1 = mysqli_num_rows($res1);
                if ($count1 == 0) {
                ?>
                    <p class="text-12 my-3">0 sản phẩm</p>
                <?php
                } else {
                ?>
                    <p class="text-12 mb-2 text-primary text-center"><?= $count1; ?> sản phẩm</p>
                    <div class="table-responsive vh-100">
                        <table class="table table-sm table-bordered table-hovered bg-white">
                            <thead class="table-secondary">
                                <th>
                                    <p class="text-12">ID</p>
                                </th>
                                <th>
                                    <p class="text-12">Mã sản phẩm</p>
                                </th>
                                <th>
                                    <p class="text-12 fw-bold">Tên sản phẩm</p>
                                </th>
                                <th>
                                    <p class="text-12">ĐVT</p>
                                </th>
                                <th>
                                    <p class="text-12 text-end">Kỳ bắt đầu</p>
                                </th>
                                <th>
                                    <p class="text-12 text-end">SL Đầu</p>
                                </th>
                                <th>
                                    <p class="text-12 text-end">Giá trị Đầu</p>
                                </th>
                                <th>
                                </th>
                            </thead>
                            <tbody>
                                <?php
                                while ($row1 = mysqli_fetch_array($res1)) {
                                ?>
                                    <tr>
                                        <td>
                                            <p class="text-10"><?= $row1['id']; ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10 click-to-copy text-wrap"><?= $row1['product_code']; ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10 click-to-copy text-wrap fw-bold"><?= $row1['product_name']; ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10"><?= $row1['unit']; ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10 fw-bold text-danger"><?= $row1['start_period']; ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10 text-end"><?= number_format($row1['start_qty'], 0); ?></p>
                                        </td>
                                        <td>
                                            <p class="text-10 text-end"><?= number_format($row1['start_value'], 0); ?></p>
                                        </td>
                                        <td class="align-middle">
                                            <a class="btn btn-sm btn-link" href="/products/?view=edit&id=<?= $row1['id']; ?>"><span class="material-symbols-outlined text-14 text-dark">edit</span></a>
                                            <a class="btn btn-sm btn-link" href="/products/?view=delete&id=<?= $row1['id']; ?>"><span class="material-symbols-outlined text-14 text-danger">delete</span></a>
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