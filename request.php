<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once("ProjektorAutoload.php");



//$ftp_server = "personalservice.cz";
//$ftp_user_name = "personalservice_cz";
//$ftp_user_pass = "person2309";
//$adresar1 = "personalservice.cz";
//$adresar2 = "backup";
//// path to remote file
//$remote_file = 'test.php';
//$local_file = '_kopie_test.php';


$html1 = print_r('
        <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title>Grafia.cz | Projektor | RequestForDbExport</title>
            </head>
            <body>
            ', TRUE);           
echo $html1;


// set up basic connection
//$conn_id = ftp_connect($ftp_server);
//
//// login with username and password
//$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
//ftp_pasv($conn_id, true); 
//
//// try to change the directory to somedir
//if (ftp_chdir($conn_id, $adresar1)) {
//    echo "Aktuální adresář: " . ftp_pwd($conn_id) . "<br>";
//} else { 
//    echo "Nepodařilo se změnit adresář<br>";
//}
//if (ftp_chdir($conn_id, $adresar2)) {
//    echo "Aktuální adresář: " . ftp_pwd($conn_id) . "<br>";
//} else { 
//    echo "Nepodařilo se změnit adresář<br>";
//}

$request  = new HTTP_Request2('http://www.personalservice.cz/backup/AuthorizedExportDB.php');
//$request->setAuth('exporter', 'Exp0rtPassw0rd', HTTP_Request2::AUTH_DIGEST);
$response = $request->send();
    if (200 == $response->getStatus()) {
        echo $response->getBody();
    } else {
        echo 'Unexpected HTTP status: ';
        echo "HTTP_Request:".'http://www.personalservice.cz/backup/AuthorizedExportDB.php<br>';
        echo "Response status: " . $response->getStatus() . "<br>";
        echo "Human-readable reason phrase: " . $response->getReasonPhrase() . "<br>";
        foreach ($response->getHeader() as $k => $v) {
            echo "\t{$k}: {$v}". "<br>";
        }
        echo "Response body:" . $response->getBody()."<br>";
    }echo "Response headers:\n";

//$request  = new HTTP_Request2('http://www.personalservice.cz/backup/AuthorizedExportDB.php');
$request->setAuth('exporter', 'Exp0rtPassw0rd', HTTP_Request2::AUTH_DIGEST);
try {
$response = $request->send();
    if (200 == $response->getStatus()) {
        echo $response->getBody();
    } else {
        echo 'Unexpected HTTP status: ';
        echo "HTTP_Request:".'http://www.personalservice.cz/backup/AuthorizedExportDB.php<br>';
        echo "Response status: " . $response->getStatus() . "<br>";
        echo "Human-readable reason phrase: " . $response->getReasonPhrase() . "<br>";
        echo "Response body:" . $response->getBody()."<br>";
    }
} catch (HTTP_Request2_Exception $e) {
    echo 'Error: ' . $e->getMessage();
}


//$remote_file = $response->getBody();
//$content = $response->getBody();
//$handle = fopen($local_file, 'w');
//fwrite($handle, $content);
//fclose($handle);

////set up basic connection
//$conn_id = ftp_connect($ftp_server);
////
////// login with username and password
//$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
//ftp_pasv($conn_id, true); 
//
//// try to change the directory to somedir
//if (ftp_chdir($conn_id, $adresar1)) {
//    echo "Aktuální adresář: " . ftp_pwd($conn_id) . "<br>";
//} else { 
//    echo "Nepodařilo se změnit adresář<br>";
//}
//if (ftp_chdir($conn_id, $adresar2)) {
//    echo "Aktuální adresář: " . ftp_pwd($conn_id) . "<br>";
//} else { 
//    echo "Nepodařilo se změnit adresář<br>";
//}
//
//$contents = ftp_nlist($conn_id, ".");
//var_dump($contents);
//// open file to write to
//$handle = fopen($local_file, 'w');
//// try to download $remote_file and save it to $handle
//ftp_pasv($conn_id, TRUE); 
//if (ftp_fget($conn_id, $handle, $remote_file, FTP_ASCII, 0)) {
//    echo "Úspěšně zapsán vzdálený soubor $remote_file do $local_file <br>";
//} else {
//    echo "Nastal problém při downloadu souboru $remote_file do $local_file <br>";
//};
//// close the connection
//ftp_close($conn_id);
//fclose($handle);

//$request  = new HTTP_Request2('http://www.personalservice.cz/backup/test2.php', HTTP_Request2::METHOD_GET);
////$request  = new HTTP_Request2('http://www.personalservice.cz/backup/text.txt', HTTP_Request2::METHOD_GET);
//try {
//$response = $request->send();
//    if (200 == $response->getStatus()) {
//        echo $response->getBody();
//    } else {
//        echo 'Unexpected HTTP status: ';
//        echo "HTTP_Request:".'http://www.personalservice.cz/backup/test1.php';
//        echo "Response status: " . $response->getStatus() . "<br>";
//        echo "Human-readable reason phrase: " . $response->getReasonPhrase() . "<br>";
//        echo "Response body:" . $response->getBody()."!";
//        echo "<br>";
//    }
//} catch (HTTP_Request2_Exception $e) {
//    echo 'Error: ' . $e->getMessage();
//}

$html2 = print_r('
    </body>
    </html>
            ', TRUE);           
echo $html2;

?>
