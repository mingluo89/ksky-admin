<?php
require_once __DIR__ . '/../lib/helper.php';
include __DIR__ . "/../lib/session.php";
if (isset($_SESSION['in_phone'])) {
    include __DIR__ . "/../lib/connect.php";
    include __DIR__ . "/../lib/header.php";
?>

    <body>
        <div class="container-fluid bg-blue-gra vh-100">
            <div class="row">
                <?php include __DIR__ . "/../lib/nav-side.php"; ?>

                <div class="col-12 col-lg-9 col-xl-10 px-0">
                    <?php
                    include('./view-main.php');
                    ?>
                </div>
            </div>
        </div>
    </body>

    </html>
<?php
} else {
    header("Location: /login");
}
