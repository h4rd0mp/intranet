<?php
    session_start();

    if(isset($_SESSION['err'])){
        print('<div class="alert alert-danger"> ' . $_SESSION['message'] . ' </div>');
        session_unset();
        session_destroy();
        exit;
    }

    if (isset($_SESSION['loggedin']) && !empty($_SESSION['loggedin'])) {
        $loggedUser = $_SESSION['loggedUser'];

        $is_admin = $loggedUser['is_admin'];

        if ($is_admin == 1)
        {
            header('Location: admin.php');
            exit;
        }
        else
        {
            header('Location: precios.php');
            exit;
        }
    }

    header('Location: login.php');
?>
