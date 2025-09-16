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
                            case 'tinhtoan':
                                include('./view-tinhtoan.php');
                                break;

                            case 'add':
                                include('./view-add.php');
                                break;

                            case 'edit':
                                include('./view-edit.php');
                                break;

                            case 'detail':
                                include('./view-detail.php');
                                break;

                            case 'delete':
                                include('./view-delete.php');
                                break;

                            case 'add-item-real':
                                include('./view-add-item-real.php');
                                break;

                            case 'add-item-ao':
                                include('./view-add-item-ao.php');
                                break;

                            case 'edit-item':
                                include('./view-edit-item.php');
                                break;

                            case 'delete-item':
                                include('./view-delete-item.php');
                                break;

                            case 'add-payment':
                                include('./view-add-payment.php');
                                break;

                            case 'edit-payment':
                                include('./view-edit-payment.php');
                                break;

                            case 'delete-payment':
                                include('./view-delete-payment.php');
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

        <script type="text/javascript">
            function handleStatusChange(str) {
                window.location.href = "./?status=" + str
            }
        </script>
    </body>

    </html>
<?php
} else {
    header("Location: ../login");
}
