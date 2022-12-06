<?php
    session_start();
    
    $intranet_u = $_SESSION['intranet_u'];

    $urlFiles = json_encode($intranet_u);

    echo $urlFiles;
?>
