<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.php');
	exit;
}

  require_once('header.php');
  require_once('nav.php');

  if (!isset($_SESSION['intranet'])) {
    include('googledrive.php');
  }

  $intranetFolders = $_SESSION['intranet'];

  $appFolders = $_SESSION['intranet'];
  $appFiles = $_SESSION['intranet_f'];

  echo "<section class='pricing py-5'>
  <div class='container'>
    <div class='row'>";

    foreach($intranetFolders as $k => $folder)
    {
        $id = $folder['id'];
        $nameFolder = $folder['name'];

          echo "<div class='col-lg-4'>
                  <ul class='fa-ul'>";
          echo "    <a href={$id} class='btn btn-block btn-warning text-uppercase'><li><i class='fa fa-check'></i>{$nameFolder}</li></a>";
          echo "     </ul>
                    </div>";
        }
echo"    </div>
  </div>
</section>";

    require_once 'footer.php';
?>