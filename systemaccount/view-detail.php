<?php
date_default_timezone_set("Asia/Ho_Chi_Minh");

// $sql = "SELECT * FROM ops_user_permission WHERE ops_user_id = '" . $_GET['user'] . "' AND permission_id = '" . $_GET['permission'] . "'";
// $res = mysqli_query($connect, $sql);
// $count = mysqli_num_rows($res);
// if ($count > 0) {
//     while ($row = mysqli_fetch_array($res)) {
//         $value = $row['value'];
//     }
// } else {
//     $value = null;
// }
// $sql_permission = "SELECT * FROM ops_permission WHERE id = '" . $_GET['permission'] . "'";
// $res_permission = mysqli_query($connect, $sql_permission);
// while ($row_permission = mysqli_fetch_array($res_permission)) {
//     $permission_name = $row_permission['name'];
//     $permission_detail = $row_permission['detail'];
// }

$sql_user = "SELECT * FROM ops_user WHERE id = '" . $_GET['id'] . "'";
$res_user = mysqli_query($connect, $sql_user);
while ($row_user = mysqli_fetch_array($res_user)) {
?>
    <div class="container-fluid bg-blue-gra vh-100">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <div class="d-flex align-items-center justify-content-between p-3">
                    <a href="javascript:history.back()" class="btn btn-sm">
                        <span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span>
                    </a>
                    <div class="d-flex align-items-center">
                        <p class="fw-bold text-14">THÔNG TIN TÀI KHOẢN</p>
                    </div>
                    <div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                <div class="px-3">
                    <div class="bg-white shadow-gg rounded p-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="fw-bold text-16">Thông tin tài khoản</p>
                            <a href="/systemaccount/?view=edit-info&id=<?= $_GET['id']; ?>" class="btn btn-link">
                                <div class="d-flex align-items-center">
                                    <span class="material-symbols-outlined text-14 me-1">edit</span>
                                    <p class="text-12">Sửa</p>
                                </div>
                            </a>
                        </div>
                        <ul class="list-group mb-3">
                            <li class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="text-12">Tên</p>
                                    <p class=" text-12 fw-bold"><?= $row_user['name']; ?></p>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="text-12">Gmail</p>
                                    <p class=" text-12 fw-bold"><?= $row_user['gmail']; ?></p>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="text-12">Phone</p>
                                    <p class=" text-12 fw-bold"><?= $row_user['phone']; ?></p>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="text-12">Avatar</p>
                                    <?php
                                    if (isset($row_user['avatar'])) {
                                    ?>
                                        <img width="50" class="rounded-circle" src="<?= $row_user['avatar']; ?>">
                                    <?php
                                    } else {
                                    ?>
                                        <!-- <a href="/systemaccount/?view=set-avatar&id=<?= $row_user['id']; ?>" class="btn btn-link btn-sm">
                                        <p class="text-12">Đặt hình đại diện</p>
                                    </a> -->
                                    <?php
                                    }
                                    ?>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="text-12">Mật khẩu</p>
                                    <a href="/systemaccount/?view=reset-pass&id=<?= $row_user['id']; ?>" class="btn btn-link btn-sm">
                                        <p class="text-12">Đặt lại mật khẩu</p>
                                    </a>
                                </div>
                            </li>
                        </ul>
                        <div class="d-grid my-4">
                            <a href="/systemaccount/?view=delete&id=<?= $_GET['id']; ?>" class="btn btn-outline-danger my-2">
                                <p>Xóa nhân viên</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>