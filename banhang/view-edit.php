<?php
$sql1 = "SELECT * FROM orders WHERE id ='" . $_GET['id'] . "'";
$res1 = mysqli_query($connect, $sql1);
$count1 = mysqli_num_rows($res1);
if ($count1 > 0) {
    while ($row1 = mysqli_fetch_assoc($res1)) {
?>
        <div class="row">
            <div class="col-12 col-md-6 offset-md-3">
                <div class="d-flex align-items-center justify-content-between py-3">
                    <a href="javascript:history.back()" class="btn"><span class="material-icons text-20 lh-base">arrow_back_ios</span></a>
                    <div class="d-flex align-items-center">
                        <p class="fw-bold text-20">Sửa thông tin đơn hàng</p>
                    </div>
                    <div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-top-30 bg-white vh-100 row mx-0">
            <div class="col-12 offset-md-2 col-md-8 offset-xl-3 col-xl-6">
                <!-- Edit Label Form -->
                <div class="rounded shadow-gg bg-white p-3">
                    <form action="do.php" method="post">
                        <input type="hidden" name="action" value="edit-order">
                        <input type="hidden" name="order_id" value="<?= $_GET['id']; ?>">
                        <input type="hidden" name="customer_id" id="customer_id" value="<?= $row1['customer_id']; ?>" required>

                        <p class="text-20 mb-4 fw-bold">Thông tin cơ bản</p>

                        <!-- Thời gian ship cho khách -->
                        <label class="form-label mb-2" for="log_time">Thời gian ship</label>
                        <div class="d-flex align-items-center mb-4">
                            <span class="material-icons text-20 me-3 text-theme">event_note</span>
                            <input type="datetime" class="form-control" id="ship_time" name="ship_time" value="<?= $row1['ship_time']; ?>" required>
                        </div>

                        <!-- Khách hàng -->
                        <label class="form-label mb-2" for="supplier_id">Khách hàng</label>
                        <div class="d-flex align-items-center mb-3">
                            <span class="material-icons text-20 me-3 text-theme">search</span>
                            <?php
                            $sql2 = "SELECT * FROM customers WHERE id ='" . $row1['customer_id'] . "'";
                            $res2 = mysqli_query($connect, $sql2);
                            $count2 = mysqli_num_rows($res2);
                            if ($count2 > 0) {
                                while ($row2 = mysqli_fetch_assoc($res2)) {
                            ?>
                                    <input class="form-control" id="customer_name" name="customer_name" placeholder="Tìm Khách Hàng" value="<?= $row2['name']; ?>" type="search" autocomplete="off" required>
                            <?php
                                }
                            }
                            ?>
                        </div>

                        <div class="text-center">
                            <div id="loader" class="loader" style="display:none;"></div>
                        </div>
                        <div id="suggestNcc" class="alert-warning p-3 rounded-20 mb-3" style="display:none">
                            <p class="mb-3 fw-bold text-dark">Chọn Khách Hàng</p>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-theme my-3">Tiếp</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <style>
            .loader {
                border: 16px solid #f3f3f3;
                border-radius: 50%;
                border-top: 16px solid #3498db;
                width: 120px;
                height: 120px;
                -webkit-animation: spin 1s linear infinite;
                /* Safari */
                animation: spin 1s linear infinite;
                margin-left: auto;
                margin-right: auto;
                margin-top: 10px;
                margin-bottom: 10px;
            }

            /* Safari */
            @-webkit-keyframes spin {
                0% {
                    -webkit-transform: rotate(0deg);
                }

                100% {
                    -webkit-transform: rotate(360deg);
                }
            }

            @keyframes spin {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }
        </style>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#customer_name").keyup(function() {
                    $.ajax({
                        type: "POST",
                        url: "post.php",
                        data: {
                            action: "customer-suggest",
                            q: $(this).val()
                        },
                        beforeSend: function() {
                            $("#loader").show();
                            $("#suggestNcc").hide();
                        },
                        success: function(data) {
                            $("#suggestNcc").html(data);
                            $("#loader").hide();
                            $("#suggestNcc").show();
                        }
                    });
                });
            });

            function updateForm(id, name) {
                $("#suggestNcc").hide();
                $("#customer_name").val(name);
                $("#customer_id").val(id);
            }
        </script>

    <?php
    }
} else {
    ?>
    <p class="text-center">Không tìm thấy nhãn mã #<?= $_GET['id']; ?></p>
<?php
}
?>