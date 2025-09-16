<?php
include __DIR__ . "/../lib/session.php";

if (isset($_SESSION['in_phone'])) {
  if ($in_id == 2 || $in_id == 5) {
    include __DIR__ . "/../lib/connect.php";
    include __DIR__ . "/../lib/header.php";
?>

    <body>
      <?php if (!isset($_GET['do'])) {
        include __DIR__ . "/../lib/nav.php";
      } ?>
      <div class="container-fluid bg-blue-gra vh-100">
        <div class="row">
          <?php
          include __DIR__ . "/../lib/nav-side.php";
          ?>
          <div class="col-12 col-lg-9 col-xl-10 px-0">
            <?php
            if (isset($_GET['view'])) {
              switch ($_GET['view']) {
                case 'detail':
                  include __DIR__ . "/view-detail.php";
                  break;

                case 'add':
                  include __DIR__ . "/view-add.php";
                  break;

                case 'edit-info':
                  include __DIR__ . "/view-edit-info.php";
                  break;

                case 'reset-pass':
                  include __DIR__ . "/view-reset-pass.php";
                  break;

                case 'set-permission':
                  include __DIR__ . "/view-set-permission.php";
                  break;

                case 'delete':
                  include __DIR__ . "/view-delete.php";
                  break;

                default:
                  include __DIR__ . "/view-all.php";
                  break;
              }
            } else {
              include __DIR__ . "/view-all.php";
            }

            ?>
          </div>
        </div>
      </div>
    </body>

    </html>

<?php
  } else {
    header('Location: /login');
  }
} else {
  header('Location: /login');
}
?>