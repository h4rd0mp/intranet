<?php
    function validateEmail($email){
        $correo_filtrado = filter_var($email,FILTER_SANITIZE_EMAIL);

        if (filter_var($correo_filtrado,FILTER_VALIDATE_EMAIL) == true)
        {
            $correo_filtrado = filter_var($correo_filtrado,FILTER_VALIDATE_EMAIL);

            $correo_filtra = explode("@", $correo_filtrado);
    
            if (count($correo_filtra) != 2){
                return false;
            }
            
            $appSettings = json_decode(file_get_contents(dirname(__FILE__) . "./appsettings.json"));
    
            $Correo = $appSettings->Correo;
                
            if ($correo_filtra[1] == $Correo->correo_accept)
            {
                return $correo_filtrado;
            }
        }

        return false;
    }

    function checkCaptcha($recaptcha){
        $recaptcha_url="https://www.google.com/recaptcha/api/siteverify";
        
        $recaptcha_data = array (
            'secret' => '6LexJnMiAAAAADF9a-HXR6UldM3vGTcqQsNuXsNX',
            'response' => $recaptcha
        );
    
        $options = array (
            'http' => array (
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($recaptcha_data)
            )
        );
    
        $context = stream_context_create($options);
        $verify = file_get_contents($recaptcha_url, false, $context);
        $response_json = json_decode($verify);
    
        if ($response_json->success == true && $response_json->score>=0.5 && $response_json->action=='submit'){
            return $response_json->success;
        }
    }

?>