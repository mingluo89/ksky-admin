<?php
$sql1 = "SELECT * FROM luong WHERE id ='" . $_GET['id'] . "'";
$res1 = mysqli_query($connect, $sql1);
$count1 = mysqli_num_rows($res1);
if ($count1 > 0) {
    while ($row1 = mysqli_fetch_assoc($res1)) {
?>
        <div class="container-fluid bg-blue-gra vh-100">
            <div class="row">
                <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                    <div class="d-flex align-items-center justify-content-between p-3">
                        <a href="javascript:history.back()" class="btn btn-sm">
                            <span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span>
                        </a>
                        <div class="d-flex align-items-center">
                            <p class="fw-bold text-14">SỬA KHOẢN LƯƠNG</p>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                    <!-- Edit Form -->
                    <div class="px-3">
                        <div class="bg-white shadow-gg rounded p-3">
                            <form action="/luong/do.php" method="post">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="id" value="<?= $row1['id']; ?>">

                                <!-- Tháng -->
                                <div class="row mb-3">
                                    <div class="col-4 col-md-3 col-form-label">
                                        <div class="d-flex align-items-center">
                                            <span class="material-symbols-outlined text-20 me-3 text-theme">event_note</span>
                                            <label for="month">Tháng</label>
                                        </div>
                                    </div>
                                    <div class="col-8 col-md-9">
                                        <input type="month" class="form-control" id="month" name="month" value="<?= date("Y-m", strtotime($row1['month'])); ?>" required>
                                    </div>
                                </div>

                                <!-- Tên -->
                                <div class="row mb-3">
                                    <div class="col-4 col-md-3 col-form-label">
                                        <div class="d-flex align-items-center">
                                            <span class="material-symbols-outlined text-20 me-3 text-theme">account_circle</span>
                                            <label for="full_name">Tên</label>
                                        </div>
                                    </div>
                                    <div class="col-8 col-md-9">
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?= $row1['full_name']; ?>" placeholder="" required>
                                    </div>
                                </div>

                                <!-- CCCD -->
                                <div class="row mb-3" id="cccd_group">
                                    <div class="col-4 col-md-3 col-form-label">
                                        <div class="d-flex align-items-center">
                                            <span class="material-symbols-outlined text-20 me-3 text-theme">badge</span>
                                            <label for="cccd">CCCD</label>
                                        </div>
                                    </div>
                                    <div class="col-8 col-md-9">
                                        <input type="text" class="form-control" id="cccd" name="cccd" value="<?= $row1['cccd']; ?>" minlength="12" maxlength="12" placeholder="" required>
                                    </div>
                                </div>

                                <!-- MST -->
                                <div class="row mb-3" id="mst_group">
                                    <div class="col-4 col-md-3 col-form-label">
                                        <div class="d-flex align-items-center">
                                            <span class="material-symbols-outlined text-20 me-3 text-theme">barcode</span>
                                            <label for="mst">MST</label>
                                        </div>
                                    </div>
                                    <div class="col-8 col-md-9">
                                        <input type="number" class="form-control" id="mst" name="mst" value="<?= $row1['mst']; ?>" min="0" placeholder="" required>
                                    </div>
                                </div>

                                <!-- Vị trí -->
                                <div class="row mb-3" id="title_group">
                                    <div class="col-4 col-md-3 col-form-label">
                                        <div class="d-flex align-items-center">
                                            <span class="material-symbols-outlined text-20 me-3 text-theme">business</span>
                                            <label class="form-label mb-2" for="title">Vị trí</label>
                                        </div>
                                    </div>
                                    <div class="col-8 col-md-9">
                                        <input type="text" class="form-control" id="title" name="title" value="<?= $row1['title']; ?>" placeholder="" required>
                                    </div>
                                </div>

                                <!-- Tổng -->
                                <div class="row mb-3">
                                    <div class="col-4 col-md-3 col-form-label">
                                        <div class="d-flex align-items-center">
                                            <span class="material-symbols-outlined text-20 me-3 text-theme">functions</span>
                                            <label for="total">Tổng</label>
                                        </div>
                                    </div>
                                    <div class="col-8 col-md-9">
                                        <input type="number" class="form-control" id="total" name="total" value="<?= $row1['total']; ?>" min="0" placeholder="" required>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-sm btn-dark fw-bold">Tạo</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
} else {
    ?>
    <p class="text-center">Không tìm thấy khoản lương #<?= $_GET['id']; ?></p>
<?php
}
?>