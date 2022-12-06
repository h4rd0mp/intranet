<?php
require_once 'vendor/autoload.php';

$tokenPath = 'token.json';

$client = new Google_Client();

$client->setApplicationName('Drive API .NET Quickstart');
$client->setScopes(Google_Service_Drive::DRIVE);
$client->setAuthConfig('credentials.json');
$client->setAccessType('offline');

//$client->setDeveloperKey('AIzaSyDzeiQRTNuwwDIu7_0FhFQAr2tN8RVIf8Q');

if (file_exists($tokenPath)) {
    $accessToken = file_get_contents('./token.json/Google.Apis.Auth.OAuth2.Responses.TokenResponse-user', true);
    $client->setAccessToken($accessToken);
}

/*
// If there is no previous token or it's expired.
if ($client->isAccessTokenExpired()) {
    // Refresh the token if possible, else fetch a new one.
    if ($client->getRefreshToken()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    } else {
        // Request authorization from the user.
//        $authUrl = $client->createAuthUrl();
//        printf("Open the following link in your browser:\n%s\n", $authUrl);
//        print 'Enter verification code: ';
//        $authCode = trim(fgets(STDIN));

        // Exchange authorization code for an access token.
        $accessToken = $client->fetchAccessToken();
        $client->setAccessToken($accessToken);

        // Check to see if there was an error.
        if (array_key_exists('error', $accessToken)) {
            throw new Exception(join(', ', $accessToken));
        }
    }
    // Save the token to a file.
    if (!file_exists(dirname($tokenPath))) {
        mkdir(dirname($tokenPath), 0700, true);
    }
    file_put_contents($tokenPath, json_encode($client->getAccessToken()));
}
*/

$intranet = "1KmdWYKctnY384NOGcnhA0D0mvIHQgP3l"; //Intranet
//$intranet = "18vcMGWvMXXW7d-NiQkB4ELOxFAN0ClZd"; //Descargas Pagina

$service = new Google_Service_Drive($client);

//$intranetFolders = []; // intranet 02.sep

$appFolders = [];
$appFiles = [];
$urlFiles = [];

//$appFolder = ['id'] = $intranet;
//$appFolder = ['name'] = 'Intranet';

//$appFolders[] = $appFolder;

$appFolders = googleFolders($service, $appFolders, $intranet);

//$intranetFolders = $appFolders; // intranet 02.sep

//$_SESSION['intranet_t'] = $appFolders;  // intranet 02.sep

$_appfolders = $appFolders;

foreach ($_appfolders as $k => $folder)
{
    $id = $folder['id'];

    $appFolders = googleFolders($service, $appFolders, $id);
}

$appFiles = googleFiles($service, $appFolders);

$_SESSION['intranet'] = $appFolders;
$_SESSION['intranet_f'] = $appFiles;

$urlFiles = urlFiles($appFiles);

$_SESSION['intranet_u'] = $urlFiles;

/*
$nameFolders = [];
$appFiles = [];
$urlFiles = [];

foreach( $files as $i => $file ){
    if ($file['id'] == $intranet)
    {
        $id = $file['id'];
        $name = $file['name'];
        $fileExtension = $file['fileExtension'];
        $webViewLink = $file['webViewLink'];
        $mimeType = $file['mimeType'];
        $parents = $file['parents'][0];

        if ($mimeType == "application/vnd.google-apps.folder")
        {
            if (!array_key_exists($id,$appFiles))
            {
                $appFiles[$id] = [];
            }

            $nameFolders[$id] = $name;
        }
        else
        {
            if (!array_key_exists($parents,$appFiles))
            {
                $appFiles[$parents] = [];
            }

            $googleFile = [];

            $googleFile["id"] = $id;
            $googleFile["name"] = $name;
            $googleFile["fileExtension"] = $fileExtension;
            $googleFile["webViewLink"] = $webViewLink;
            $googleFile["mimeType"] = $mimeType;
            $googleFile["parents"] = $parents;

            $urlFiles[] = $googleFile;

            $appFiles[$parents][] = $googleFile;
        }
    }
}

*/

    function urlFiles($appfiles)
    {
        $urlfiles = [];
   
        foreach ($appfiles as $k => $folder)
        {
            foreach ($folder as $i => $file)
            {
                $urlfiles[] = $file;
            }
        }

        return $urlfiles;
    }

    function googleFiles($gservice, $folders)
    {
        $googlefiles = [];

        foreach ($folders as $k => $folder)
        {
            $parent = $folder['id'];

            $parameters['q'] = "'".$parent."' in parents and mimeType != 'application/vnd.google-apps.folder' and trashed=false";
            //$parameters['q'] = "name contains 'Intranet'";
            $parameters['pageSize'] = 100; 
            $parameters['fields'] = "nextPageToken, files(id, name, fileExtension, parents, webViewLink, mimeType)";

            $files = $gservice->files->listFiles($parameters);
            
            foreach( $files as $i => $file ){
                $googleFile['Id'] = $file['id'];
                $googleFile['Name'] = $file['name'];
                $googleFile["FileExtension"] = $file['fileExtension'];
                $googleFile["WebViewLink"] = $file['webViewLink'];

                $googlefiles[$parent][] = $googleFile;
            }
        }   
       return $googlefiles;
    }

    function googleFolders($gservice, $folders, $parent)
    {
        $parameters['q'] = "'".$parent."' in parents  and mimeType = 'application/vnd.google-apps.folder'  and trashed=false";
        //$parameters['q'] = "name contains 'Intranet'";
        $parameters['orderBy'] = "name";
        $parameters['pageSize'] = 100; 
        $parameters['fields'] = "nextPageToken, files(id, name, fileExtension, parents, webViewLink, mimeType)";

        $files = $gservice->files->listFiles($parameters);

        foreach( $files as $i => $file ){
            $folder['id'] = $file['id'];
            $folder['name'] = $file['name'];

            $folders[] = $folder;
        }
        
        return $folders;
    }

    function intranetFolders($gservice, $parent)
    {
        $parameters['q'] = "'".$parent."' in parents  and mimeType = 'application/vnd.google-apps.folder'";
        //$parameters['q'] = "name contains 'Intranet'";
        $parameters['pageSize'] = 100; 
        $parameters['fields'] = "nextPageToken, files(id, name, fileExtension, parents, webViewLink, mimeType)";

        $files = $gservice->files->listFiles($parameters);

        foreach( $files as $i => $file ){
            $folder['id'] = $file['id'];
            $folder['name'] = $file['name'];

            $folders[] = $folder;
        }
        
        return $folders;
    }

?>