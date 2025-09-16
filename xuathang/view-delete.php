<?php
$sql1 = "SELECT * FROM xuat WHERE id ='" . $_GET['id'] . "'";
$res1 = mysqli_query($connect, $sql1);
$count1 = mysqli_num_rows($res1);
if ($count1 > 0) {
    while ($row1 = mysqli_fetch_assoc($res1)) {
?>
        <div class="bg-danger row mx-0 vh-100">
            <div class="col-12 offset-md-2 col-md-8 offset-xl-3 col-xl-6">
                <!-- Edit Label Form -->
                <div class="rounded-20 bg-white border shadow-gg m-4 p-3">
                    <form action="/xuathang/do.php" method="post">
                        <input type="hidden" name="action" value="delete-xuat">
                        <input type="hidden" name="xuat_id" value="<?= $_GET['id']; ?>">

                        <p class="my-3 text-16 fw-bold text-danger text-center" for="cat">XÓA ĐƠN NHẬP #<?= $_GET['id']; ?></p>
                        <p>Các phần sau sẽ được xóa</p>
                        <ul>
                            <li>
                                <p>Toàn bộ đơn nhập</p>
                            </li>
                            <li>
                                <p>Các dòng trong đơn</p>
                            </li>
                        </ul>

                        <div class="d-flex justify-content-center align-items-center">
                            <button type="submit" class="btn btn-dark me-2">
                                <p class="text-13">Xóa</p>
                            </button>
                            <a href="javascript:history.back()" class="btn btn-outline-dark">
                                <p class="text-13">Quay lại</p>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php
    }
}
?>