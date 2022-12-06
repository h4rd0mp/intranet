<?php
    session_start();

    header("Content-Security-Policy: script-src 'self' https://ajax.googleapis.com/ https://www.google.com/recaptcha/ https://www.gstatic.com/ https://www.googleapis.com; connect-src 'self'; img-src 'self'; style-src 'self'; frame-ancestors 'self'; form-action 'self'");

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

        header('Location: salir.php');
        exit;
    }

    require_once("header.php");
    require_once("nav.php");
    require_once("entity.php");

    $users = getUsers();

    echo "<div class='container'>
    <main class='pb-3'>
        <div class='row'>
            <h2>Usuarios</h2>
            <table class='table'>
            </table>
                <p>
                <a href='registro.php'>Registrar Nuevo</a>
            </p>
            <table class='table'>
                <thead>
                <tr>
                    <th>
                        Correo Electrónico
                    </th>
                    <th>
                        Nombre
                    </th>
                    <th>
                        Administrador
                    </th>
                    <th>
                        Activo
                    </th>
                    <th>
                        Acción
                    </th>
                </tr>
            </thead>
        
            <tbody>";

            foreach ($users as $k => $user)
            {
                $status_c = '';
                $is_admin_c = '';
            
                if ($user['status'] == 1)
                {
                    $status_c = 'checked';
                }
            
                if ($user['is_admin'] == 1)
                {
                    $is_admin_c = 'checked';
                }
             
                echo "<tr>
                <td>
                    {$user['email']}
                </td>
                <td>
                    {$user['username']}
                </td>
                <td>
                    <div class='checkbox'>
                        <input type='checkbox' {$is_admin_c} disabled />
                    </div>
                </td>
                <td>
                    <div class='checkbox'>
                        <input type='checkbox' {$status_c} disabled/>
                    </div>
                </td>
                <td>
                    <a href='edit.php?id={$user['id']}'>Editar</a>  |  <a href='password.php?id={$user['id']}'>Cambiar Password</a>
                </td>
            </tr>";
            }
            
        echo "</tbody>
        </table>
        </div>
        </main>
    </div>";

    require_once("footer.php");
?>