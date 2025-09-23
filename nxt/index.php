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
                            case 'sync':
                                include('./view-sync.php');
                                break;

                            case 'transaction':
                                include('./view-transaction.php');
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
