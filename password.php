<?php
    session_start();

    if (!isset($_SESSION['loggedin']) || (isset($_SESSION['loggedin']) && empty($_SESSION['loggedin']))) {
        $_SESSION['err']  =  true;
        $_SESSION['message'] = '';

        header('Location: index.php');
        exit;
    }

    $loggedUser = $_SESSION['loggedUser'];

    $username = $loggedUser['username'];
    $is_admin = $loggedUser['is_admin'];

    if (!$is_admin) {
        session_unset();
        session_destroy();

        $_SESSION['err']  =  true;
        $_SESSION['message'] = '';

        header('Location: index.php');
        exit;
    }

    if (filter_has_var(INPUT_GET, 'id'))
    {
        $id_clean = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

        $id = filter_var($id_clean, FILTER_VALIDATE_INT);

        if ($id == false){
            $_SESSION['err'] = true;
            $_SESSION['message'] = '';
            
            header('Location: index.php');
            exit;    
        }
    }
    else
    {
        $_SESSION['err'] = true;
        $_SESSION['message'] = '';

        header('Location: index.php');
        exit;
    }

    include('update_password.php');

    if (!empty($_POST['send'])) {
        if (empty(doValidatePassword())){
            $_SESSION['err']  =  true;
            $_SESSION['message'] = 'Existio error en captura';
        }

        header('Location: index.php');
        exit;
    }

    header("Content-Security-Policy: script-src 'self' https://ajax.googleapis.com/ https://www.google.com/recaptcha/ https://www.gstatic.com/ https://www.googleapis.com; connect-src 'self'; img-src 'self'; style-src 'self'; frame-ancestors 'self'; form-action 'self'");

    require_once("header.php");
    require_once("nav.php");
    require_once("entity.php");

    $user = getUser($id);

    $email = $user['email'];
    $username = $user['username'];
    $is_admin = $user['is_admin'];
    $status = $user['status'];

    $status_c = '';
    $is_admin_c = '';

    if ($status == 1)
    {
        $status_c = 'checked';
    }

    if ($is_admin == 1)
    {
        $is_admin_c = 'checked';
    }

    echo "<div class='container'>
    <main class='pb-3'>
    <div class='row'>
    <div class='col-md-4 col-md-offset-2'>
    </div>
    <div class='col-md-4'>
        <form class='form-signin' enctype='application/x-www-form-urlencoded' method='post' >
            <h3>Editar usuario</h3>
            <hr />
            <div class='text-danger'></div>
            <div class='form-group'>
                <input type='text' class='form-control' name='email' id='email' value='{$email}' readonly/>
            </div>
            <div class='form-group'>
                <input type='password' class='form-control' name='password' id='password' placeholder='Password' required />
            </div>
            <div class='form-group'>
                <input type='password' class='form-control' name='passwordconfirm' id='passwordconfirm' placeholder='Confirm Password' required/>
            </div>
            <div class='form-group'>
                <input type='text' class='form-control' name='username' id='username' value='{$username}' readonly/>
            </div>

            <div class='form-group'>
                <div class='checkbox'>
                    <label>
                        Administrador  
                        <input type='checkbox' name='is_admin' id='is_admin' {$is_admin_c} disabled/>
                    </label>
                </div>
            </div>
            <div class='form-group'>
                <div class='checkbox'>
                    <label>
                        Activo  
                        <input type='checkbox' name='status' id='status' {$status_c} disabled/>
                    </label>
                </div>
            </div>
            <div class='form-group'>
                <button class='btn btn-lg btn-primary btn-block'  type='submit' name='send' value='send'>Actualizar</button>
            </div>
        </form>
    </div>
</div>
</main>
</div>";


    require('footer.php');
?>