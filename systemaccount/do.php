<?php
include('../lib/connect.php');
include('../lib/session.php');

global $connect;

switch ($_POST['action']) {
	case 'add':
		$name = mysqli_escape_string($connect, $_POST['name']);
		$phone = mysqli_escape_string($connect, $_POST['phone']);
		$gmail = mysqli_escape_string($connect, $_POST['gmail']);

		$pass = mysqli_escape_string($connect, $_POST['password']);
		$password = hash('sha256', $pass);

		$sql_check = "SELECT * FROM ops_user WHERE phone = '$phone'";
		$res_check = mysqli_query($connect, $sql_check);
		$count_check = mysqli_num_rows($res_check);
		if ($count_check > 0) {
			header("Location: /systemaccount/?do=add&mess=Phone already used!");
		} else {
			$sql = "INSERT INTO ops_user (name, phone, gmail, password) VALUES ('$name', '$phone', '$gmail', '$password')";

			if (mysqli_query($connect, $sql)) {
				header("Location: /systemaccount/?view=detail&id=" . mysqli_insert_id($connect));
			} else {
				echo "Error: " . mysqli_error($connect);
			}
		}

		mysqli_close($connect);
		break;

	case 'edit-info':
		$id = mysqli_escape_string($connect, $_POST['id']);

		$name = mysqli_escape_string($connect, $_POST['name']);
		$phone = mysqli_escape_string($connect, $_POST['phone']);
		$gmail = mysqli_escape_string($connect, $_POST['gmail']);

		$sql = "UPDATE ops_user SET name='$name', phone='$phone', gmail='$gmail' WHERE id='$id'";
		if (mysqli_query($connect, $sql)) {
			header("Location: /systemaccount/?view=detail&id=" . $id);
		} else {
			echo "Error: " . mysqli_error($connect);
		}

		mysqli_close($connect);
		break;

	case 'reset-pass':
		$id = mysqli_escape_string($connect, $_POST['id']);
		$pass = mysqli_escape_string($connect, $_POST['password']);
		$password = hash('sha256', $pass);

		$sql = "UPDATE ops_user SET password='$password' WHERE id='$id'";

		if (mysqli_query($connect, $sql)) {
			header("Location: /systemaccount/");
		} else {
			echo "Error: " . mysqli_error($connect);
		}

		mysqli_close($connect);
		break;

	case 'delete':
		$id = mysqli_escape_string($connect, $_POST['id']);

		$stmt1 = $connect->prepare("DELETE FROM ops_user WHERE id=?");
		$stmt1->bind_param("i", $id);
		$success1 = $stmt1->execute();
		if ($success1) {
			$stmt1->close();
			header('Location: /systemaccount');
		} else {
			echo "Error " . $stmt1->errno . ": <strong>" . $stmt1->error . "</strong>";
		}

		break;
}
