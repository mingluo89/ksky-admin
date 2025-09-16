<?php
include('../lib/connect.php');
include('../lib/session.php');

global $connect;
switch ($_GET['action']) {
    case "city-change":
        $q = mysqli_escape_string($connect, $_GET['q']);
        $sql = "SELECT DISTINCT district FROM city_district_ward WHERE city='$q'";
        $res = mysqli_query($connect, $sql);
?>
        <option value="" selected>Chọn Quận</option>
        <?php
        while ($row = mysqli_fetch_array($res)) {
        ?>
            <option value="<?php echo $row['district']; ?>"><?php echo $row['district']; ?></option>
        <?php
        }
        break;

    case "district-change":
        $s = mysqli_escape_string($connect, $_GET['s']);
        $sql = "SELECT DISTINCT ward FROM city_district_ward WHERE district='$s'";
        $res = mysqli_query($connect, $sql);
        ?>
        <option value="" selected>Chọn Phường</option>
        <?php
        while ($row = mysqli_fetch_array($res)) {
        ?>
            <option value="<?php echo $row['ward']; ?>"><?php echo $row['ward']; ?></option>
<?php
        }
        break;
}
?>