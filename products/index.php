<?php
include("../lib/session.php");
if (isset($_SESSION['in_phone'])) {
    include("../lib/connect.php");
    include("../lib/header.php");
?>

    <body>
        <div class="container-fluid bg-blue-gra vh-100">
            <div class="row">
                <?php include('../lib/nav-side.php'); ?>

                <div class="col-12 col-lg-9 col-xl-10 px-0">

                    <?php
                    if (isset($_GET['view'])) {
                        switch ($_GET['view']) {
                            case 'detail':
                                include('./view-detail.php');
                                break;

                            case 'baogia':
                                include('./view-baogia.php');
                                break;

                            case 'add':
                                include('./view-add.php');
                                break;

                            case 'add-imgs':
                                include('./view-add-imgs.php');
                                break;

                            case 'edit':
                                include('./view-edit.php');
                                break;

                            case 'edit-img':
                                include('./view-edit-img.php');
                                break;

                            case 'edit-imgs':
                                include('./view-edit-imgs.php');
                                break;

                            case 'edit-price':
                                include('./view-edit-price.php');
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </body>

    </html>
<?php
} else {
    header("Location: ../login");
}
