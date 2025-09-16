<?php
$sql = "SELECT * FROM nhap_detail WHERE id ='" . $_GET['itemid'] . "'";
$res = mysqli_query($connect, $sql);
$count = mysqli_num_rows($res);
if ($count > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
?>
        <div class="container-fluid bg-blue-gra vh-100">
            <div class="row">
                <div class="col-12 col-md-8 offset-md-2 col-xl-6 offset-xl-3">
                    <div class="d-flex align-items-center justify-content-between py-3">
                        <a href="javascript:history.back()" class="btn btn-sm">
                            <span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span>
                        </a>
                        <div class="text-center">
                            <p class="fw-bold text-16">SỬA NHẬP DETAIL</p>
                        </div>
                        <div>
                        </div>
                    </div>
                    <!-- Add Item Form -->
                    <div class="rounded shadow-gg bg-white mb-3 p-3">
                        <form action="/nhaphang/do.php" method="post" id="addItemForm">
                            <input type="hidden" name="action" value="edit-item">
                            <input type="hidden" name="nhap_id" value="<?= $_GET['nhapid']; ?>">
                            <input type="hidden" name="item_id" value="<?= $_GET['itemid']; ?>">
                            <input type="hidden" name="product_id" id="productId" value="<?= $row['product_id']; ?>">

                            <!-- Is It -->
                            <label class="form-label mb-2" for="isit">Loại</label>
                            <div class="d-flex align-items-center mb-3">
                                <span class="material-symbols-outlined text-20 me-3 text-theme">sell</span>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_it" id="isit1" value="0" <?= ($row['is_it']) ? "" : "checked"; ?> required>
                                    <label class="form-check-label" for="isit1">Thật</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_it" id="isit2" value="1" <?= ($row['is_it']) ? "checked" : ""; ?> required>
                                    <label class="form-check-label" for="isit2">Ảo</label>
                                </div>
                            </div>

                            <!-- Sản phẩm -->
                            <label class="form-label mb-2" for="product_name">Sản phẩm</label>
                            <div class="d-flex align-items-center mb-3">
                                <span class="material-symbols-outlined text-20 me-3 text-theme">package_2</span>
                                <input class="form-control" id="product_name" name="product_name" placeholder="Tìm tên, mã sản phẩm" type="search" value="<?= $row['product_name']; ?>" required>
                            </div>

                            <!-- Mã Sản phẩm -->
                            <label class="form-label mb-2" for="product_name">Mã sản phẩm</label>
                            <div class="d-flex align-items-center mb-3">
                                <span class="material-symbols-outlined text-20 me-3 text-theme">barcode</span>
                                <input class="form-control" id="product_code" name="product_code" placeholder="Mã sản phẩm tự nhảy" type="text" value="<?= $row['product_code']; ?>" readonly>
                            </div>

                            <div class="text-center">
                                <div id="loader" class="loader" style="display:none;"></div>
                            </div>
                            <div id="suggestProduct" class="bg-grey p-3 rounded-20 mb-3" style="display:none">
                                <p class="mb-3 fw-bold text-dark">Chọn sản phẩm</p>
                            </div>

                            <div class="d-flex align-items-start mb-3 bg-light rounded p-2 pb-0">
                                <span class="material-symbols-outlined text-20 me-3 mt-4 text-theme">barcode_reader</span>
                                <div class="container mb-3 px-0">
                                    <div class="row">
                                        <div class="col-3 mb-2">
                                            <!-- SL -->
                                            <label class="form-label mb-2" for="qty">SL</label>
                                            <input type="number" min="0" step="1" class="form-control text-12" name="qty" id="qty" value="<?= $row['qty']; ?>" required>
                                        </div>
                                        <div class="col-3 mb-2">
                                            <!-- Đơn vị đóng gói-->
                                            <label class="form-label mb-2" for="unitPack">ĐVT</label>
                                            <input type="text" class="form-control text-12" name="unit" id="unit" value="<?= $row['unit']; ?>" required>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <!-- Giá -->
                                            <label class="form-label mb-2" for="price">Giá</label>
                                            <div class="d-flex align-items-center mb-3">
                                                <input type="text" class="form-control text-12 form-thousand" name="price" id="price" value="<?= $row['price']; ?>" required>
                                                <p class="fw-bold text-16 ms-3">&#8363;</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thành tiền -->
                            <label class="form-label mb-2">Thành tiền</label>
                            <div class="d-flex align-items-center mb-3">
                                <span class="material-symbols-outlined text-20 me-3 text-theme">paid</span>
                                <p class="fw-bold text-success"><span id="total"><?= number_format($row['total_before_vat'], 0); ?></span> &#8363;</p>
                            </div>

                            <div class="d-grid">
                                <input type="submit" name="submit" class="btn btn-sm btn-dark mt-3" value="Sửa">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-12 offset-md-2 col-md-8 offset-xl-3 col-xl-6">
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

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.form-thousand').each(function() {
                    const input = $(this);

                    // Apply the mask
                    input.mask('#,##0', {
                        reverse: true
                    });

                    // Format any pre-populated values
                    const value = input.val();
                    if (value) {
                        input.val(value.replace(/\B(?=(\d{3})+(?!\d))/g, ',')); // Add commas
                    }
                });

                // Ensure only a raw number is sent
                $('#addItemForm').on('submit', function(e) {
                    $('.form-thousand').each(function() {
                        const input = $(this); // Reference the current input element
                        const rawValue = input.val().replace(/,/g, ''); // Remove commas
                        input.val(rawValue); // Update only this input's value
                    });
                });

                $("#product_name").keyup(function() {
                    $.ajax({
                        type: "POST",
                        url: "ajax.php",
                        data: {
                            action: "product",
                            q: $(this).val()
                        },
                        beforeSend: function() {
                            $("#loader").show();
                            $("#suggestProduct").hide();
                        },
                        success: function(data) {
                            $("#suggestProduct").html(data);
                            $("#loader").hide();
                            $("#suggestProduct").show();
                        }
                    });
                });
                $("#qty").keyup(function() {
                    qty = $("#qty").val().replace(/,/g, '');
                    price = $("#price").val().replace(/,/g, '');
                    total = qty * price;
                    var num = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                    $("#total").html(num);
                });
                $("#price").keyup(function() {
                    qty = $("#qty").val().replace(/,/g, '');
                    price = $("#price").val().replace(/,/g, '');
                    total = qty * price;
                    var num = total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
                    $("#total").html(num);
                });
            });

            $(document).on("click", ".suggest-row", function() {
                const productid = $(this).data("productid");
                $("#productId").val(productid);
                const code = $(this).data("code");
                $("#product_code").val(code);
                const name = $(this).data("name");
                $("#product_name").val(name);
                const unit = $(this).data("unit");
                $("#unit").val(unit);
                $("#suggestProduct").hide();
            });
        </script>

        <!-- Thousand separator input -->
        <script>
            // Helper function to add thousand separators
            const formatNumber = (number) => {
                return number.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            };

            // Helper function to remove thousand separators
            const unformatNumber = (number) => {
                return number.replace(/,/g, '');
            };
            // Main function to handle input event
            const handleInput = (event) => {
                const inputField = event.target;

                // Ensure the input type is "text"
                if (inputField.type !== "text") return;

                const cursorPosition = inputField.selectionStart;

                // Get unformatted number
                const unformattedValue = unformatNumber(inputField.value);

                // Reformat number with thousand separators
                inputField.value = formatNumber(unformattedValue);

                // Adjust the cursor position
                const formattedLength = inputField.value.length;
                const unformattedLength = unformattedValue.length;

                const offset = formattedLength - unformattedLength;
                inputField.setSelectionRange(cursorPosition + offset, cursorPosition + offset);
            };

            // Initialize with thousand separator
            const initializeInput = (input) => {
                // Ensure the input type is "text"
                if (input.type !== "text") return;

                const initialValue = input.value;
                if (initialValue) {
                    input.value = formatNumber(unformatNumber(initialValue));
                }
            };

            // Apply functionality to all inputs with class "form-thousand"
            const applyThousandSeparator = () => {
                const inputs = document.querySelectorAll('.form-thousand');
                inputs.forEach((input) => {
                    // Initialize the input value
                    initializeInput(input);

                    // Add event listeners
                    input.addEventListener('input', handleInput);
                    input.addEventListener('blur', () => {
                        input.value = formatNumber(unformatNumber(input.value));
                    });
                    input.addEventListener('focus', () => {
                        input.value = unformatNumber(input.value);
                    });
                });
            };

            // On form submission, unformat the values
            const handleFormSubmit = (event) => {
                const inputs = document.querySelectorAll('.form-thousand');
                inputs.forEach((input) => {
                    // Replace the formatted value with the unformatted value for submission
                    input.value = unformatNumber(input.value);
                });
            };

            // Apply the functionality on page load
            applyThousandSeparator();

            // Attach the form submission handler
            const form = document.getElementById('addItemForm');
            form.addEventListener('submit', handleFormSubmit);
        </script>
<?php
    }
}
?>