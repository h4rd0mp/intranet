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

    include('create.php');

    if (!empty($_POST['send'])) {
        if (empty(doValidateCreate())){
            $_SESSION['err']  =  true;
            $_SESSION['message'] = 'Existio error en captura';
        }

        header('Location: index.php');
        exit;
    }

    header("Content-Security-Policy: script-src 'self' https://ajax.googleapis.com/ https://www.google.com/recaptcha/ https://www.gstatic.com/ https://www.googleapis.com; connect-src 'self'; img-src 'self'; style-src 'self'; frame-ancestors 'self'; form-action 'self'");

    require_once("header.php");
    require_once("nav.php");

?>
<div class='container'>
    <main class='pb-3'>
    <div class='row'>
    <div class='col-md-4 col-md-offset-2'>
    </div>
    <div class="col-md-4">
        <form class="form-signin" enctype="application/x-www-form-urlencoded" method="post" >
            <h4>Registrar usuario.</h4>
            <hr />
            <div class="text-danger"></div>
            <div class="form-group">
                <input type="text" class="form-control" name="email" id="email" placeholder="Correo Electronico" required/>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required />
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="passwordconfirm" id="passwordconfirm" placeholder="Confirm Password" required/>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="username" id="username" placeholder="Nombre" required/>
            </div>
            <div class='form-group'>
                <div class="checkbox">
                    <label>
                        Administrador  
                        <input type="checkbox" name="is_admin" id="is_admin"/>
                    </label>
                </div>
            </div>
            <div class="form-group">
                    <button class="btn btn-lg btn-primary btn-block"  type="submit"  name="send" value="send">Registrar</button>
                </div>
                <?php require_once 'form-footer.php';?>
        </form>
    </div>
    </main>
</div>

<?php
    require('footer.php');
?>