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
                        <p class="fw-bold text-20">Thời gian giao hàng</p>
                    </div>
                    <div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-top-30 bg-white vh-100 row mx-0">
            <div class="col-12 offset-md-2 col-md-8 offset-xl-3 col-xl-6">
                <!-- Edit Label Form -->
                <div class="rounded shadow-gg bg-white my-3 p-3">
                    <form action="do.php" method="post">
                        <input type="hidden" name="action" value="edit-ship-time">
                        <input type="hidden" name="order_id" value="<?= $_GET['id']; ?>">

                        <!-- Thời gian ship cho khách -->
                        <label class="form-label mb-2" for="log_time">Thời gian ship</label>
                        <div class="d-flex align-items-center mb-4">
                            <span class="material-icons text-20 me-3 text-theme">event_note</span>
                            <input type="datetime" class="form-control" id="ship_time" name="ship_time" value="<?= $row1['ship_time']; ?>" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-theme my-3">Tiếp</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <?php
    }
} else {
    ?>
    <p class="text-center">Không tìm thấy nhãn mã #<?= $_GET['id']; ?></p>
<?php
}
?>