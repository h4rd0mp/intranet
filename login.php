<?php
    session_start();

    include_once('authenticate.php');

    if(isset($_COOKIE['burmestercom']) && !empty($_COOKIE['burmestercom'])){
        doLogin();
        header('Location: index.php');
        exit;
    }

    require_once 'SecurityService.php';

    if (!empty($_POST['send'])) {
        $csrfService = new securityService();
        $csrfResponse = $csrfService->validate();

//        $_SESSION['err'] = true;
//        $_SESSION['message'] = $_SERVER['RequestVerificationToken'];

        if (!empty($csrfResponse)) {
            //$_SESSION['err'] = true;
            //$_SESSION['message'] = "";

            doAuthenticate();

            } else {
            //$_SESSION['err'] = true;
            //$_SESSION['message'] =  $_SERVER['RequestVerificationToken'];
        }

        header('Location: index.php');
        exit;
    } else {
        //$csrfService = new securityService();
        //header("RequestVerificationToken: " .  $csrfService->insertHiddenToken());
    }

	header("Content-Security-Policy: script-src 'self' https://ajax.googleapis.com/ https://www.google.com/recaptcha/ https://www.gstatic.com/ https://www.googleapis.com; connect-src 'self'; img-src 'self'; style-src 'self'; frame-ancestors 'self'; form-action 'self'");

    require_once 'header.php';
?>
<div class="container">
    <main class="pt-5">
        <div class="row">
            <form class="form-signin" name="account" id="account" enctype="application/x-www-form-urlencoded" method="post">
                <h1 class="h3 mb-3 font-weight-normal">Firme por favor...</h1>
                <hr />
                <div class="text-danger"></div>
                <div class="form-group">
                    <label class="sr-only" for="email"></label>
                    <input type="text" class="form-control" name="email" id="email" placeholder="Correo Electronico" required/>
                </div>
                <div class="form-group">
                    <label for="Password"></label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required/>
                </div>
                <div class="form-group">
                    <button class="btn btn-lg btn-primary btn-block" name="send" type="submit" value="send" >Accesar</button>
                </div>
                <?php require_once 'form-footer.php';?>
                <input type="hidden" name="recaptcha_response" value="" id="recaptchaResponse" />
            </form>
        </div>
    </main>
</div>
<script src="https://www.google.com/recaptcha/api.js?render=6LexJnMiAAAAAOXGKRHuU4qZCcGytM5ZX9Kg1l0D"></script>
<script src="js/recaptcha.js">
</script>
<?php
    require_once 'footer.php';
?>
