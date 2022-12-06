<?php
    function getAccess($id)
    {
        $con = openConn();

        $query = 'SELECT email, username, is_admin, status FROM accounts where id = :id';

        $stmt = $con->prepare($query);

        $stmt->execute([
            'id' => $id
        ]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!empty($user)){
            if ($user['status'] == 1) {

                session_regenerate_id();

                $_SESSION['loggedin'] = TRUE;
                $_SESSION['loggedUser'] = $user;

            }
        }

        $con = null;
    }

    function updatePassword($email, $password)
    {
        $con = openConn();

        $query = 'UPDATE accounts SET password=:password WHERE email=:email';

        $stmt = $con->prepare($query);

        $stmt->execute([
            'password' => $password,
            'email' => $email
        ]);
        
        $con = null;
    }
    
    function updateUser($email, $username, $is_admin, $status)
    {
        $con = openConn();
    
        $query = 'UPDATE accounts SET username=:username, is_admin=:is_admin, status=:status_u WHERE email=:email';

        $stmt = $con->prepare($query);
        
        $stmt->execute([
            'username' => $username,
            'is_admin' => $is_admin,
            'status_u' => $status,
            'email' => $email
        ]);

        $con = null;
    }

    function createUser($email, $password, $username, $is_admin, $status)
    {
        $con = openConn();
    
        $query = 'INSERT INTO accounts (email, password, username, is_admin, status) 
        VALUES (:email, :password, :username, :is_admin, :status)';

        $stmt = $con->prepare($query);
        
        $stmt->execute([
            'email' => $email,
            'password' => $password,
            'username' => $username,
            'is_admin' => $is_admin,
            'status' => $status
        ]);

        $con = null;
    }

    function authenticate($email, $pass)
    {
        try{

        $con = openConn();

        $query = 'CALL authenticateUser(:correo_p)';

        $stmt = $con->prepare($query);

        $stmt->bindParam(':correo_p', $email, PDO::PARAM_STR);

        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!empty($user)){
            if (password_verify($pass, $user['password'])) {

            $_SESSION['loggedin'] = TRUE;
            $_SESSION['loggedUser'] = $user;
            }
        }
    } catch (\PDOException $e){
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }

    $con=null;
    }

    function getUsers()
    {
        $users = [];

        try{
            $con = openConn();

                $query = 'SELECT id, email, password, username, is_admin, status FROM accounts';

                $stmt = $con->prepare($query);
                $stmt->execute();
        
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $con=null;

            } catch (\PDOException $e){
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }
    

        return $users;
    }

    function getUser($id)
    {
        try{

        $con = openConn();

        $query = 'SELECT email, password, username, is_admin, status FROM accounts WHERE id = ?';

        $stmt = $con->prepare($query);
        $stmt->execute([$id]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (\PDOException $e){
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }

    $con=null;

        return $user;
    }

    function openConn()
    {
        $appSettings = json_decode(file_get_contents(dirname(__FILE__) . "/appsettings.json"));

        $connectionString = $appSettings->ConnectionStrings;

        $host = $connectionString->database_host;
        $user = $connectionString->database_user;
        $pass = $connectionString->database_pass;
        $name = $connectionString->database_name;

        // Try and connect using the info above.
            $conn = new PDO('mysql:host=' . $host . ';dbname=' . $name, $user, $pass, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            /*
        } catch (\PDOException $e){
            throw new \PDOException($e->getMessage(), $e->getCode());
        }
/*
        /*$conn = mysqli_connect($host, $user, $pass, $name);
        if ( mysqli_connect_errno() ) {
            // If there is an error with the connection, stop the script and display the error.
            die('Failed to connect to MySQL: ' . mysqli_connect_error());
        }
        */

        return $conn;
    }

    function closeConn($con)
    {
        mysqli_close($con);
    }

    function errores($email, $password, $username, $is_admin, $status)
    {
        $con = openConn();
    
        $stmt = $con->prepare('INSERT INTO accounts (email, password, username, is_admin, status) VALUES (?, ?, ?, ?, ?)');

        if (false === $stmt)
        {
            die('prepare() failed: ' . htmlspecialchars($stmt->error));
        }
    
            // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
            $rc = $stmt->bind_param('sssii', $email, $password, $username, $is_admin, $status);

            if (false === $rc)
            {
                die('bind_param() failed: ' . htmlspecialchars($stmt->error));
            }
    
            $rc = $stmt->execute();
            //$stmt->free_result();

            if (false === $rc)
            {
                die('bind_param() failed: ' . htmlspecialchars($stmt->error));
            }

            $stmt->close();
        
    }
?>