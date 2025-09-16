<?php
include("../lib/session.php");
if (isset($_SESSION['in_phone'])) {
    if ($_SESSION['permission8']) {
        include("../lib/connect.php");
        include("../lib/header.php");
?>

        <body>
            <div class="container-fluid bg-theme vh-100">
                <div class="row">
                    <?php include('../lib/nav-side.php'); ?>

                    <div class="col-12 col-lg-9 col-xl-10 px-0">

                        <?php
                        if (isset($_GET['view'])) {
                            switch ($_GET['view']) {
                                case 'detail':
                                    include('./view-detail.php');
                                    break;

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
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        </body>

        </html>
<?php
    } else {
        header('Location: ../home/?permission=none');
    }
} else {
    header("Location: ../login");
}
