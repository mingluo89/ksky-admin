<?php
include __DIR__ . '/../lib/connect.php';
include __DIR__ . '/../lib/session.php';

switch ($_POST['action']) {

  case 'login':
    // username and password sent from form       
    $phone = mysqli_escape_string($connect, $_POST['phone']);
    $password = mysqli_escape_string($connect, $_POST['password']);

    $sql = "SELECT * 
    FROM ops_user 
    WHERE phone = '$phone' 
    LIMIT 0,1";
    $res = mysqli_query($connect, $sql);
    $count = mysqli_num_rows($res);
    if ($count > 0) {
      // Tìm thấy số đt
      while ($row = mysqli_fetch_array($res)) {
        // So sánh password
        $password_hash = hash('sha256', $password);
        if ($password_hash  == $row['password']) {
          // Đúng pass
          $_SESSION['in_id'] = $row['id'];
          $_SESSION['in_phone'] = $phone;
          $_SESSION['in_name'] = $row['name'];

          header('Location: /home');
          exit;
        } else {
          // Sai pass
          header("Location: /login/?message=wrongpass&phone=$phone");
          exit;
        }
      }
    } else {
      // Không tìm thấy số đt
      header("Location: /login/?message=noacc&phone=$phone");
    }

    break;

  default:

    break;
}
