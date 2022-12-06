<?php
    require_once 'SecurityService.php';
    $csrfService = new securityService();
    $csrfService->insertHiddenToken();
?>