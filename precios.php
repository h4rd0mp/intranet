<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();

// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin']) || (isset($_SESSION['loggedin']) && empty($_SESSION['loggedin']))) {
  $_SESSION['err']  =  true;
  $_SESSION['message'] = '';
  
  header('Location: index.php');
	exit;
}

header("Content-Security-Policy: script-src 'self' https://ajax.googleapis.com/ https://www.google.com/recaptcha/ https://www.gstatic.com/ https://www.googleapis.com; connect-src 'self'; img-src 'self'; style-src 'self'; frame-ancestors 'self'; form-action 'self'");

$loggedUser = $_SESSION['loggedUser'];

$username = $loggedUser['username'];
$is_admin = $loggedUser['is_admin'];

  require_once('header.php');
  require_once('nav.php');

  if (!isset($_SESSION['intranet'])) {
    include('googledrive.php');
  }

  $appFolders = $_SESSION['intranet'];
  $appFiles = $_SESSION['intranet_f'];

  echo "<section class='pricing py-5'>
  <div class='container'>
    <div class='row'>";

    foreach($appFolders as $k => $folder)
    {
        $id = $folder['id'];
        $nameFolder = $folder['name'];

        echo "<div class='col-md-4 col-md-offset-2'>
            </div>
        <div class='col-lg-4'>
    
        <div class='card mb-5 mb-lg-0'>
          <div class='card-body'>
            <h5 class='card-title bg-dark text-white text-uppercase text-center'>{$nameFolder}</h5>
            <hr>";

            if (array_key_exists($id,$appFiles))
            {
              $files = $appFiles[$id];

              echo "<ul class='fa-ul'>";

              foreach($files as $i => $file)
              {
                $webViewLink = $file['WebViewLink'];
                $nameFile = $file['Name'];

                echo "<a href={$webViewLink} class='btn btn-block btn-danger text-uppercase'><li><span class='fa-li'><i class='fas fa-check'></i></span>{$nameFile}</li></a>";
              }

              echo "     </ul>";
            }
            echo "</div> 
            </div>
          </div>";
        }
echo"    </div>
  </div>
</section>";

    require_once 'footer.php';
?>