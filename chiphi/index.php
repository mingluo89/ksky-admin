<?php
include("../lib/session.php");
if (isset($_SESSION['in_phone'])) {
    include("../lib/connect.php");
    include("../lib/header.php");
?>

    <body>
        <div class="container-fluid vh-100">
            <div class="row">
                <?php include('../lib/nav-side.php'); ?>

                <div class="col-12 col-lg-9 col-xl-10 px-0">
                    <?php
                    if (isset($_GET['view'])) {
                        switch ($_GET['view']) {
                            case 'add':
                                include('./view-add.php');
                                break;

                            case 'edit':
                                include('./view-edit.php');
                                break;

                            case 'delete':
                                include('./view-delete.php');
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
