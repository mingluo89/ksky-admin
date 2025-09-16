<div class="bg-theme row mx-0">
    <div class="col-12 offset-md-2 col-md-8 offset-xl-3 col-xl-6">
        <!-- Edit Label Form -->
        <div class="rounded-20 bg-white my-5 p-3">
            <form action="do.php" method="post">
                <input type="hidden" name="action" value="cancel-order">
                <input type="hidden" name="order_id" value="<?= $_GET['id']; ?>">

                <label class="my-3 text-14 fw-bold text-danger" for="cat">Bạn thực sự muốn hủy Đơn hàng này?</label>
                <p>Đơn hàng sẽ thay đổi như sau</p>
                <ul>
                    <li>
                        <p>Trạng thái đơn hàng được cập nhật thành <b>"Hủy"</b></p>
                    </li>
                    <li>
                        <p>Phiếu Xuất Kho dang dở chuyển trạng thái thành <b>"Hủy"</b></p>
                    </li>
                    <li>
                        <p>Vận Đơn dang dở chuyển trạng thái thành <b>"Hủy"</b></p>
                    </li>
                </ul>

                <div class="d-flex justify-content-center align-items-center">
                    <button type="submit" class="btn btn-dark me-2">Hủy đơn</button>
                    <a href="javascript:history.back()" class="btn btn-outline-dark">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>