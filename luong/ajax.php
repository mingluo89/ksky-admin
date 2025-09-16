<?php
include('../lib/connect.php');
include('../lib/session.php');

global $connect;

switch ($_POST['action']) {
    case 'employee':
        $q = mysqli_escape_string($connect, $_POST['q']);

        $sql = "SELECT DISTINCT full_name FROM luong WHERE full_name LIKE '%$q%'";
        $res = mysqli_query($connect, $sql);
        $count = mysqli_num_rows($res);
        if ($count > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $sql_info = "SELECT * FROM luong WHERE full_name = '" . $row['full_name'] . "' LIMIT 0,1";
                $res_info = mysqli_query($connect, $sql_info);
                while ($row_info = mysqli_fetch_assoc($res_info)) {
?>
                    <div class="suggest-row my-2 mx-2"
                        data-fullname="<?= $row['full_name']; ?>"
                        data-cccd="<?= $row_info['cccd']; ?>"
                        data-mst="<?= $row_info['mst']; ?>"
                        data-title="<?= $row_info['title']; ?>">
                        <p class="fw-bold"><?= $row['full_name']; ?></p>
                        <p class="text-secondary text-10"><?= $row_info['cccd']; ?></p>
                    </div>
<?php
                }
            }
        } else {
            echo "<p>Không có kết quả</p>";
        }

        mysqli_close($connect);
        break;
}
