<?php

function objectsIntoArray($arrObjData, $arrSkipIndices = array())
{
    $arrData = array();
   
    // if input is object, convert into array
    if (is_object($arrObjData)) {
        $arrObjData = get_object_vars($arrObjData);
    }
   
    if (is_array($arrObjData)) {
        foreach ($arrObjData as $index => $value) {
            if (is_object($value) || is_array($value)) {
                $value = objectsIntoArray($value, $arrSkipIndices); // recursive call
            }
            if (in_array($index, $arrSkipIndices)) {
                continue;
            }
            $arrData[$index] = $value;
        }
    }
    return $arrData;
}

$xmlUrl = "XMLini.xml"; // XML feed file/URL
$xmlStr = file_get_contents($xmlUrl);
$xmlObj = simplexml_load_string($xmlStr);
//print_r($xmlObj);
//print "<br>";
$arrXml = objectsIntoArray($xmlObj);
                    $content .= "<head>
                                    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
                                    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/highlight.css\" />
                             </head>";
                    $content .= "<pre>";
                    $content .= print_r($arrXml, TRUE);
                    $content .= "</pre>";
                    echo $content;
?>
