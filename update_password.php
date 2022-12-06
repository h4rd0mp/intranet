<?php
function doValidatePassword(){
	require_once ('entity.php');

	if (!isset($_POST['email'], $_POST['password'], $_POST['passwordconfirm'])) {
		// Could not get the data that should have been sent.
		$_SESSION['err'] = true;
		$_SESSION['message'] =  'Por verifique sus campos!';
		return false;
	}
		
	$email = $_POST['email'];

	$password_filtrado = filter_var($_POST['password'],FILTER_SANITIZE_STRING);
	$passwordconfirm_filtrado = filter_var($_POST['passwordconfirm'],FILTER_SANITIZE_STRING);

	if (!empty(strcmp($password_filtrado, $passwordconfirm_filtrado))){
		$_SESSION['err'] = true;
		$_SESSION['message'] =  'Por verifique su password!';
		return false;
	}

    $password_hashed = password_hash($password_filtrado, PASSWORD_DEFAULT);

	updatePassword($email, $password_hashed);

	return true;
	}
?>
