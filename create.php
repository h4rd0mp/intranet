<?php
function doValidateCreate(){
	require_once ('entity.php');
	require_once ('utils.php');

	if ( !isset($_POST['email'], $_POST['password'], $_POST['username']) ) {
		// Could not get the data that should have been sent.
		$_SESSION['err'] = true;
		$_SESSION['message'] =  'Por verifique sus campos!';
		return false;
	}

	$email = validateEmail( $_POST['email']);

	if (empty($email)){
		$_SESSION['err'] = true;
		$_SESSION['message'] =  'Por verifique su correo!';
		return false;
	}

	$password_filtrado = filter_var($_POST['password'],FILTER_SANITIZE_STRING);
	$passwordconfirm_filtrado = filter_var($_POST['passwordconfirm'],FILTER_SANITIZE_STRING);
	
	if (!empty(strcmp($password_filtrado, $passwordconfirm_filtrado))){
		$_SESSION['err'] = true;
		$_SESSION['message'] =  'Por verifique su password!';
		return false;
	}

	$username_filtrado = filter_var($_POST['username'],FILTER_SANITIZE_STRING);
	$admin = $_POST['is_admin'];
	
	$is_admin = 0;
	
	if ($admin == "on")
	{
		$is_admin = 1;
	}
	
	$status = 1;
	
	$password_hashed = password_hash($password_filtrado, PASSWORD_DEFAULT);
	
	createUser($email, $password_hashed, $username_filtrado, $is_admin, $status);

	return true;
}
?>
