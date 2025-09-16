<div class="container">
    <div class="row">
        <div class="col-12 col-md-6 offset-md-3">
            <div class="d-flex align-items-center justify-content-between py-3">
                <a href="javascript:history.back()" class="btn"><span class="material-icons text-20 lh-base">arrow_back_ios</span></a>
                <div class="d-flex align-items-center">
                    <p class="fw-bold text-14">TẠO ĐƠN BÁN</p>
                </div>
                <div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white row mx-0">
        <div class="col-12 offset-md-2 col-md-8 offset-xl-3 col-xl-6">
            <div class="rounded shadow-gg bg-white p-3">
                <form action="do.php" method="post">
                    <input type="hidden" name="action" value="add-order">
                    <input type="hidden" name="customer_id" id="customer_id" value="" required>

                    <p class="text-16 mb-4 fw-bold">Chọn Khách Hàng</p>

                    <!-- Nhà cung cấp -->
                    <div class="d-flex align-items-center mb-4">
                        <span class="material-icons text-20 me-3 text-theme">search</span>
                        <input class="form-control" id="customer_name" name="customer_name" placeholder="Tìm Khách Hàng" type="search" autocomplete="off" required>
                    </div>

                    <div class="text-center">
                        <div id="loader" class="loader" style="display:none;"></div>
                    </div>

                    <div id="suggestNcc" class="mb-3">
                        <!-- List -->
                        <div class="accordion" id="accordionCustomerGroup">
                            <?php
                            $sql_group = "SELECT * FROM customers_group ORDER BY name";
                            $res_group = mysqli_query($connect, $sql_group);
                            while ($row_group = mysqli_fetch_array($res_group)) {
                            ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header p-1">
                                        <button class="accordion-button collapsed p-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $row_group['id']; ?>" aria-expanded="false" aria-controls="collapse<?= $row_group['id']; ?>">
                                            <p class="text-12 fw-bold"><?= $row_group['name']; ?></p>
                                        </button>
                                    </h2>
                                    <div id="collapse<?= $row_group['id']; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionCustomerGroup">
                                        <div class="accordion-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-striped mb-0">
                                                    <tbody>
                                                        <?php
                                                        $sql_customer = "SELECT * FROM customers WHERE customer_group_id = '" . $row_group['id'] . "' ORDER BY name";
                                                        $res_customer = mysqli_query($connect, $sql_customer);
                                                        $count_customer = mysqli_num_rows($res_customer);
                                                        if ($count_customer > 0) {
                                                            while ($row_customer = mysqli_fetch_array($res_customer)) {
                                                        ?>
                                                                <tr onclick="updateForm('<?= $row_customer['id']; ?>','<?= $row_customer['name']; ?>')" style="cursor:pointer">
                                                                    <td>
                                                                        <p class="text-12 fw-bold mx-3">#<?= $row_customer['id']; ?></p>
                                                                    </td>
                                                                    <td>
                                                                        <p class="text-wrap fw-bold text-12"><?= $row_customer['name']; ?></p>
                                                                    </td>
                                                                    <td class="d-none d-md-table-cell">
                                                                        <p class="text-12"><?= $row_customer['address'] . ", " . $row_customer['street']; ?></p>
                                                                        <p class="text-12 fw-bold"><?= $row_customer['district'] . ", " . $row_customer['city']; ?></p>
                                                                    </td>
                                                                </tr>
                                                            <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <p class="text-12 text-center my-3">0 khách hàng</p>
                                                        <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="accordion-item p-2">
                                <a href="../customers/?view=add" target="_blank" class="btn btn-link ps-0">
                                    <p class="text-12">+ Thêm Khách Mới</p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="addressInput" style="display:none">
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
                        <button type="submit" class="btn btn-sm btn-theme my-3 fw-bold">Tiếp</button>
                    </div>
                </form>
            </div>
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

        $.ajax({
            type: "POST",
            url: "post.php",
            data: {
                action: "customer-address",
                id: id
            },
            dataType: 'json',
            success: function(data) {
                console.log(data);
                $("#addressInput").show();

                $("#city").val(data[0].city);

                $("#district").html(data[0].districtoption);
                $("#district").val(data[0].district);
                $("#ward").html(data[0].wardoption);
                $("#ward").val(data[0].ward);
                $("#street").val(data[0].street);
                $("#address").val(data[0].address);

                $("#city").prop('required', true);
                $("#district").prop('required', true);
                $("#ward").prop('required', true);
                $("#street").prop('required', true);
                $("#address").prop('required', true);
            }
        });
    }
</script>

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