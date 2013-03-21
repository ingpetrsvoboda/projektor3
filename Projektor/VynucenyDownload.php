<?php

/*
 * Třída s požitím http hlaviček vynutí v prohlížečí dialog pro otevření/uložení souboru
 * Současně vyprázdní výstupní buffer a ukončí vykonávání skriptu, v prohlížeči tedy zůstane zobrazena minulá stránka
 * @param string název souboru s cestou
 */
class Projektor_VynucenyDownload
{
    
        public static function download($souborProDownload)
        { 
            if (file_exists($souborProDownload)) {
                header('Content-Description: File Transfer');
                //header("Content-type: Projektor_Application/force-download"); 
                //header('Content-Type','text/html; charset=windows-1250');
                //header('Content-type: Projektor_Application/pdf');
                header('Content-Type: Projektor_Application/octet-stream');
                header('Content-Disposition: attachment; filename="'.basename($souborProDownload).'"');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize($souborProDownload));
                ob_clean();
                flush();
                readfile($souborProDownload);
                exit;
            }
        }
//function downloadFile( $fullPath ){
//
//  // Must be fresh start
//  if( headers_sent() )
//    die('Headers Sent');
//
//  // Required for some browsers
//  if(ini_get('zlib.output_compression'))
//    ini_set('zlib.output_compression', 'Off');
//
//  // File Exists?
//  if( file_exists($fullPath) ){
//   
//    // Parse Info / Get Extension
//    $fsize = filesize($fullPath);
//    $path_parts = pathinfo($fullPath);
//    $ext = strtolower($path_parts["extension"]);
//   
//    // Determine Content Type
//    switch ($ext) {
//      case "pdf": $ctype="Projektor_Application/pdf"; break;
//      case "exe": $ctype="Projektor_Application/octet-stream"; break;
//      case "zip": $ctype="Projektor_Application/zip"; break;
//      case "doc": $ctype="Projektor_Application/msword"; break;
//      case "xls": $ctype="Projektor_Application/vnd.ms-excel"; break;
//      case "ppt": $ctype="Projektor_Application/vnd.ms-powerpoint"; break;
//      case "gif": $ctype="image/gif"; break;
//      case "png": $ctype="image/png"; break;
//      case "jpeg":
//      case "jpg": $ctype="image/jpg"; break;
//      default: $ctype="Projektor_Application/force-download";
//    }
//
//    header("Pragma: public"); // required
//    header("Expires: 0");
//    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//    header("Cache-Control: private",false); // required for certain browsers
//    header("Content-Type: $ctype");
//    header("Content-Disposition: attachment; filename=\"".basename($fullPath)."\";" );
//    header("Content-Transfer-Encoding: binary");
//    header("Content-Length: ".$fsize);
//    ob_clean();
//    flush();
//    readfile( $fullPath );
//
//  } else
//    die('File Not Found');
//
//}         

        // KOPIE kódu z fpdf funkce Output:
//        case 'D':
//            //Download file
//            if(isset($HTTP_SERVER_VARS['HTTP_USER_AGENT']) and strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'],'MSIE'))
//                Header('Content-Type: Projektor_Application/force-download');
//            else
//                Header('Content-Type: Projektor_Application/octet-stream');
//            if(headers_sent())
//                $this->Error('Some data has already been output to browser, can\'t send PDF file');
//            Header('Content-Length: '.strlen($this->buffer));
//            Header('Content-disposition: attachment; filename='.$name);
//            echo $this->buffer;
//            break;        
}
?>
