<?php
$sql1 = "SELECT * FROM orders WHERE id ='" . $_GET['id'] . "'";
$res1 = mysqli_query($connect, $sql1);
$count1 = mysqli_num_rows($res1);
if ($count1 > 0) {
    while ($row1 = mysqli_fetch_assoc($res1)) {
        $sql_customer = "SELECT * FROM customers WHERE id ='" . $row1['customer_id'] . "'";
        $res_customer = mysqli_query($connect, $sql_customer);
        while ($row_customer = mysqli_fetch_assoc($res_customer)) {
            // Choose current set City District Ward, if not set, get address from customer 
            if (empty($row1['city'])) {
                $city = $row_customer['city'];
                $district = $row_customer['district'];
                $ward = $row_customer['ward'];
                $street = $row_customer['street'];
                $address = $row_customer['address'];
            } else {
                $city = $row1['city'];
                $district = $row1['district'];
                $ward = $row1['ward'];
                $street = $row1['street'];
                $address = $row1['address'];
            }
?>
            <div class="row">
                <div class="col-12 col-md-6 offset-md-3">
                    <div class="d-flex align-items-center justify-content-between py-2">
                        <a href="./?view=detail&id=<?= $_GET['id']; ?>" class="btn"><span class="material-icons text-20 lh-base">arrow_back_ios</span></a>
                        <div class="d-flex align-items-center">
                            <p class="fw-bold text-20">Sửa địa chỉ giao hàng</p>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mx-0 pb-5">
                <div class="col-12 offset-md-3 col-md-6">
                    <div class="rounded shadow-gg bg-white mb-3 p-3">
                        <form action="do.php" method="post">
                            <input type="hidden" name="action" value="edit-address">
                            <input type="hidden" name="id" value="<?= $_GET['id']; ?>">

                            <div class="row">
                                <div class="col-12">
                                    <label class="mb-2" for="city">Thành phố</label>
                                    <div class="d-flex align-items-center mb-4">
                                        <span class="material-icons text-20 me-3 text-theme">place</span>
                                        <select class="form-select" name="city" id="city" onchange="handleCityChange(this.value)">
                                            <?php
                                            $sql_city = "SELECT DISTINCT city FROM city_district_ward ORDER BY city DESC";
                                            $res_city = mysqli_query($connect, $sql_city);
                                            $count_city = mysqli_num_rows($res_city);
                                            if ($count_city > 0) {
                                                while ($row_city = mysqli_fetch_assoc($res_city)) {
                                            ?>
                                                    <option value="<?= $row_city['city']; ?>" <?= ($row_city['city'] == $city) ? "selected" : ""; ?>><?= $row_city['city']; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="mb-2" for="district">Quận</label>
                                    <div class="d-flex align-items-center mb-4">
                                        <span class="material-icons text-20 me-3 text-theme">place</span>
                                        <select class="form-select" name="district" id="district" onchange="handleDistrictChange(this.value)">
                                            <option>Chọn quận</option>
                                            <?php
                                            $sql_district = "SELECT DISTINCT district FROM city_district_ward WHERE city = '" . $city . "' ORDER BY district";
                                            $res_district = mysqli_query($connect, $sql_district);
                                            $count_district = mysqli_num_rows($res_district);
                                            if ($count_district > 0) {
                                                while ($row_district = mysqli_fetch_assoc($res_district)) {
                                            ?>
                                                    <option value="<?= $row_district['district']; ?>" <?= ($row_district['district'] == $district) ? "selected" : ""; ?>><?= $row_district['district']; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="mb-2" for="ward">Phường</label>
                                    <div class="d-flex align-items-center mb-4">
                                        <span class="material-icons text-20 me-3 text-theme">place</span>
                                        <select class="form-select" name="ward" id="ward">
                                            <option>Chọn phường</option>
                                            <?php
                                            $sql_ward = "SELECT DISTINCT ward FROM city_district_ward WHERE city = '" . $city . "' AND district = '" . $district . "' ORDER BY ward";
                                            $res_ward = mysqli_query($connect, $sql_ward);
                                            $count_ward = mysqli_num_rows($res_ward);
                                            if ($count_ward > 0) {
                                                while ($row_ward = mysqli_fetch_assoc($res_ward)) {
                                            ?>
                                                    <option value="<?= $row_ward['ward']; ?>" <?= ($row_ward['ward'] == $ward) ? "selected" : ""; ?>><?= $row_ward['ward']; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="mb-2" for="street">Đường</label>
                                    <div class="d-flex align-items-center mb-4">
                                        <span class="material-icons text-20 me-3 text-theme">place</span>
                                        <input type="text" class="form-control" id="street" name="street" placeholder="Chỉ nhập tên đường, vd: Phan Văn Trị" value="<?= $street; ?>">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="mb-2" for="address">Số nhà</label>
                                    <div class="d-flex align-items-center mb-4">
                                        <span class="material-icons text-20 me-3 text-theme">place</span>
                                        <input type="text" class="form-control" id="address" name="address" placeholder="Chỉ nhập số nhà, tòa nhà, khu" value="<?= $address; ?>">
                                    </div>
                                </div>

                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-dark">Sửa</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    <?php
        }
    }
} else {
    ?>
    <p class="text-center">Không tìm thấy khách hàng #<?= $_GET['id']; ?></p>
<?php
}
?>

<script>
    function handleCityChange(city) {
        var xhttp;
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("district").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "get.php?action=city-change&q=" + city, true);
        xhttp.send();
    }

    function handleDistrictChange(district) {
        var xhttp;
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("ward").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "get.php?action=district-change&s=" + district, true);
        xhttp.send();
    }
</script>