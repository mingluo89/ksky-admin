<?php

function getCurrentQuarter($date)
{
    $currentMonth = date('n', strtotime($date)); // Get the current month as a number (1-12)
    $year = date('Y', strtotime($date));
    $quarter = ceil($currentMonth / 3); // Calculate the quarter
    return "{$year}-Q{$quarter}";
}

$sql1 = "SELECT * FROM luong WHERE id ='" . $_GET['id'] . "'";
$res1 = mysqli_query($connect, $sql1);
$count1 = mysqli_num_rows($res1);
if ($count1 > 0) {
    while ($row1 = mysqli_fetch_assoc($res1)) {
        $currentQuarter = getCurrentQuarter($row1['month']);
?>
        <div class="container-fluid bg-grey vh-100">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center justify-content-between py-3">
                    <a href="/luong/?quarter=<?= $currentQuarter; ?>" class="btn btn-sm border bg-white me-2"><span class="material-symbols-outlined text-14 lh-base">arrow_back_ios</span></a>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="material-symbols-outlined text-20 me-2">receipt_long</span>
                        <p class="fw-bold text-14">Khoản lương #<?= $row1['id']; ?></p>
                    </div>
                </div>
            </div>
            <div class="row px-0">
                <div class="col-12 col-md-8 offset-md-2 col-xl-4 offset-xl-0">
                    <!-- Thông Tin -->
                    <div class="rounded border shadow-gg rounded mb-3 p-3 bg-white">
                        <ul class="nav nav-underline">
                            <li class="nav-item">
                                <button class="nav-link text-grey active" id="underline-info-tab" data-bs-toggle="pill" data-bs-target="#underline-info" type="button" role="tab" aria-controls="underline-info" aria-selected="true">
                                    <p class="text-12">THÔNG TIN</p>
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="underline-tabContent">
                            <!-- Info & Status -->
                            <div class="tab-pane fade show active" id="underline-info" role="tabpanel" aria-labelledby="underline-info-tab" tabindex="0">
                                <div class="d-flex align-items-center justify-content-end">
                                    <a href="/luong/?view=edit&id=<?= $_GET['id']; ?>" class="btn btn-sm btn-outline-primary me-1">
                                        <span class="material-symbols-outlined text-14">edit</span>
                                    </a>
                                    <a href="/luong/?view=delete&id=<?= $_GET['id']; ?>" class="btn btn-sm btn-outline-danger">
                                        <span class="material-symbols-outlined text-14">delete</span>
                                    </a>
                                </div>
                                <div class="table-responsive mt-2">
                                    <table class="table table-borderless table-sm mb-0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p class="text-secondary mb-1">Tháng</p>
                                                </td>
                                                <td>
                                                    <p class="click-to-copy"><?= date("Y-m", strtotime($row1['month'])); ?></span></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="text-secondary mb-1">Tên</p>
                                                </td>
                                                <td>
                                                    <p class="fw-bold text-danger click-to-copy"><?= $row1['full_name']; ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="text-secondary mb-1">CCCD</p>
                                                </td>
                                                <td>
                                                    <p class="click-to-copy"><?= $row1['cccd']; ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="text-secondary mb-1">MST</p>
                                                </td>
                                                <td>
                                                    <p class="fwlick-to-copy"><?= $row1['mst']; ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="text-secondary mb-1">Vị trí</p>
                                                </td>
                                                <td>
                                                    <p class="click-to-copy"><?= $row1['title']; ?></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <p class="text-secondary mb-1">Tổng</p>
                                                </td>
                                                <td class="text-wrap">
                                                    <p class="fw-bold click-to-copy"><?= number_format($row1['total'], 0); ?></p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Payment -->
                            <div class="tab-pane fade" id="underline-payment" role="tabpanel" aria-labelledby="underline-payment-tab" tabindex="0">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap Toast -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
            <div id="copyToast" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        Copied!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <!-- Click To copy -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const copyElements = document.querySelectorAll("p.click-to-copy");
                const toastEl = document.getElementById("copyToast");
                const toast = new bootstrap.Toast(toastEl);

                copyElements.forEach(element => {
                    element.style.cursor = "pointer";
                    element.addEventListener("click", function() {
                        const text = this.textContent;

                        navigator.clipboard.writeText(text).then(() => {
                            toast.show(); // Show the Bootstrap toast
                        }).catch(err => {
                            console.error("Copy failed", err);
                        });
                    });
                });
            });
        </script>
    <?php
    }
} else {
    ?>
    <p class="text-center">Không tìm thấy Khoản lương #<?= $_GET['id']; ?></p>
<?php
}
?>