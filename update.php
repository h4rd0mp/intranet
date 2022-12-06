<?php
function doValidateUpdate(){
	require_once ('entity.php');

	if ( !isset($_POST['email'], $_POST['username']) ) {
		// Could not get the data that should have been sent.
		$_SESSION['err'] = true;
		$_SESSION['message'] =  'Por verifique sus campos!';
		return false;
	}
		
	$email = $_POST['email'];
	$username = $_POST['username'];
	$is_admin_c = $_POST['is_admin'];
	$status_c = $_POST['status'];

	$username_filtrado = filter_var($_POST['username'],FILTER_SANITIZE_STRING);

    $status = '';
    $is_admin = '';

	if ($is_admin_c == "on")
	{
		$is_admin = 1;
	}

    if ($status_c == "on")
	{
		$status = 1;
	}

	updateUser($email, $username_filtrado, $is_admin, $status);

	return true;
}
?>
