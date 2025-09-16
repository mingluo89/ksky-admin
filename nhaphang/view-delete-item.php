<div class="bg-danger row mx-0 vh-100">
    <div class="col-12 offset-md-2 col-md-8 offset-xl-3 col-xl-6">
        <!-- Edit Label Form -->
        <div class="rounded-20 bg-white my-5 p-3">
            <form action="/nhaphang/do.php" method="post">
                <input type="hidden" name="action" value="delete-item">
                <input type="hidden" name="nhap_id" value="<?= $_GET['nhapid']; ?>">
                <input type="hidden" name="item_id" value="<?= $_GET['itemid']; ?>">
                <input type="hidden" name="product_id" value="<?= $_GET['productid']; ?>">

                <label class="my-3 text-14 fw-bold text-danger">Xóa dòng nhập detail này?</label>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <?php
                        $sql1 = "SELECT * FROM nhap_detail WHERE id ='" . $_GET['itemid'] . "'";
                        $res1 = mysqli_query($connect, $sql1);
                        while ($row1 = mysqli_fetch_array($res1)) {
                        ?>
                            <tr>
                                <td>
                                    <p class="fw-bold">Tên</p>
                                </td>
                                <td>
                                    <p class="text-wrap"><?= $row1['product_name']; ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="fw-bold">Số lượng</p>
                                </td>
                                <td>
                                    <p class="fw-bold"><?= $row1['qty']; ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="fw-bold">Giá</p>
                                </td>
                                <td>
                                    <p class=""><?= number_format($row1['price'], 0); ?> đ</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="fw-bold">Thành tiền</p>
                                </td>
                                <td>
                                    <p class="fw-bold text-success"><?= number_format($row1['total_before_vat'], 0); ?> đ</p>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>

                <div class="d-flex justify-content-center align-items-center">
                    <button type="submit" class="btn btn-sm btn-dark me-2">Xóa</button>
                    <a href="javascript:history.back()" class="btn btn-sm btn-outline-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>