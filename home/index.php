<?php
require_once __DIR__ . '/../lib/helper.php';
include __DIR__ . '/../lib/session.php';

if (isset($_SESSION['in_phone'])) {
    include __DIR__ . '/../lib/connect.php';
    include __DIR__ . '/../lib/header.php';
?>

    <body>
        <?php include __DIR__ . '/../lib/nav.php'; ?>
        <div class="container-fluid">
            <div class="row">
                <?php include __DIR__ . '/../lib/nav-side.php'; ?>

                <div class="col-12 col-lg-9 col-xl-10 px-0" style="overflow: auto;">
                    <?php
                    if (isset($_GET['permission'])) {
                        switch ($_GET['permission']) {
                            case 'none':
                                include('./view-nopermission.php');
                                break;

                            default:
                                include('./view-main.php');
                                break;
                        }
                    } else {
                        include('./view-main.php');
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>

    </html>
<?php
} else {
    header("Location: ../login");
}
