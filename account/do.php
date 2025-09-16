<?php
include __DIR__ . '/../lib/connect.php';
include __DIR__ . '/../lib/session.php';

global $connect;

switch ($_POST['action']) {
	case 'change-pass':
		if (!isset($_POST['id'], $_POST['new_pass_1'], $_POST['new_pass_2'])) {
			die("Thiếu dữ liệu đầu vào");
		}
		$id = (int) $_POST['id'];
		$new_pass_1 = mysqli_real_escape_string($connect, $_POST['new_pass_1']);
		$new_pass_2 = mysqli_real_escape_string($connect, $_POST['new_pass_2']);
		if ($new_pass_1 == $new_pass_2) {
			$new_pass = hash('sha256', $new_pass_1);
			$sql = "UPDATE ops_user SET password='$new_pass' WHERE id= $id";

			if (mysqli_query($connect, $sql)) {
				header("Location: /account/?action=change-pass&mess=ok");
			} else {
				echo "Error: " . mysqli_error($connect);
			}
		} else {
			header("Location: /account/?action=change-pass&mess=wrong");
		}
		exit;

		mysqli_close($connect);
		break;
}
