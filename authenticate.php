<?php
//session_start();

include ('entity.php');
require_once ('utils.php');

function doLogin(){
    $id = $_COOKIE['burmestercom'];

    //$_SESSION['err'] = true;
    //$_SESSION['message'] = "id";

    getAccess($id);

    if (!isset($_SESSION['loggedin'])) {
        $_SESSION['err'] = true;
        $_SESSION['message'] = "Accesso no autorizado...";
        
        setcookie('burmestercom', $id, time() - 60, "/", "", "", true);
    }
}

function doAuthenticate()
{
    if(checkCaptcha($_POST['recaptcha_response']))
    {
        //$_SESSION['err'] = true;
        //$_SESSION['message'] = "captcha ok!";

        $correo_filtrado = $_POST['email'];  //validateEmail($_POST['email']);

        if (!empty($correo_filtrado))
        {
            $password_filtrado = $_POST['password']; //filter_var($_POST['password'],FILTER_SANITIZE_STRING);
    
            authenticate($correo_filtrado, $password_filtrado);
    
            if(isset($_SESSION['loggedin']))
            {
                //$_SESSION['err'] = true;
                //$_SESSION['message'] = "authenticate ok!";
        
                $loggedUser = $_SESSION['loggedUser'];
                $id = $loggedUser['id'];
    
                setcookie('burmestercom', $id, time() + 60 * 60 * 1, "/", "", "", true);
            }
            else{
                $_SESSION['err'] = true;
                $_SESSION['message'] = 'Ingreso no autorizado...';;
            }
        }
    }
    else
    {
        //setcookie('burmestercom', $id, time() - 60, "/", "", "", 0);
        
        $_SESSION['err'] = true;
        $_SESSION['message'] = 'Ingreso no válido...';
    }
}
?>
