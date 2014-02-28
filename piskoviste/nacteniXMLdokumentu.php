<?php


  function __get($id) { return $this->items[ $id ]; }

  function loadDocument($xmlFile)
  {
    $doc = new DOMDocument();
//$doc->preserveWhiteSpace=false;
    $doc->load( $xmlFile );
    
    return $doc;

  }

function dom2array($node) {
  $res = array();
  print $node->nodeType.'<br/>';
  if($node->nodeType == XML_TEXT_NODE){
      $res = $node->nodeValue;
  }
  else{
      if($node->hasAttributes()){
          $attributes = $node->attributes;
          if(!is_null($attributes)){
              $res['@attributes'] = array();
              foreach ($attributes as $index=>$attr) {
                  $res['@attributes'][$attr->name] = $attr->value;
              }
          }
      }
      if($node->hasChildNodes()){
          $children = $node->childNodes;
          for($i=0;$i<$children->length;$i++){
              $child = $children->item($i);
              $res[$child->nodeName] = dom2array($child);
          }
      }
  }
  return $res;
}  

    function xml2array($domDocument) {
//    function xml2array($xml) {
//        $domDocument = new DOMDocument;
//        $domDocument->loadXML($xml);
//        $domXPath = new DOMXPath($domDocument);
        $domXPath = new DOMXPath($domDocument);
        $array = array();
        foreach ($domXPath->query('//key') as $keyDOM) {
            $id = $keyDOM->getAttribute('id');
            $value = $keyDOM->hasAttribute('value') ? $keyDOM->getAttribute('value') : trim($keyDOM->textContent);
            if (array_key_exists($id, $array)) {
                if (is_array($array[$id])) {
                    $array[$id][] = $value;
                } else {
                    $array[$id] = array($array[$id]);
                    $array[$id][] = $value;
                }
            } else {
                $array[$id] = $value;
            }
        }
        return $array;
    }
    
function xmlToArray($n)
{
    $return=array();

    foreach($n->childNodes as $nc){
        if( $nc->hasChildNodes() ){
            if( $n->firstChild->nodeName== $n->lastChild->nodeName&&$n->childNodes->length>1){
                $item = $n->firstChild;
                $return[$nc->nodeName][]=xmlToArray($item);
            }
            else{
                 $return[$nc->nodeName]=xmlToArray($nc);
            }
       }
       else{
           $return=$nc->nodeValue;
       }
    }
    return $return;
}
function dom2array_full($node){
    $result = array();
    if($node->nodeType == XML_TEXT_NODE) {
        $result = $node->nodeValue;
    }
    else {
        if($node->hasAttributes()) {
            $attributes = $node->attributes;
            if(!is_null($attributes)) 
                foreach ($attributes as $index=>$attr) 
                    $result[$attr->name] = $attr->value;
        }
        if($node->hasChildNodes()){
            $children = $node->childNodes;
            for($i=0;$i<$children->length;$i++) {
                $child = $children->item($i);
                if($child->nodeName != '#text')
                if(!isset($result[$child->nodeName]))
                    $result[$child->nodeName] = dom2array($child);
                else {
                    $aux = $result[$child->nodeName];
                    $result[$child->nodeName] = array( $aux );
                    $result[$child->nodeName][] = dom2array($child);
                }
            }
        }
    }
    return $result;
} 

function dom_to_array($root)
{
    $result = array();

    if ($root->hasAttributes())
    {
        $attrs = $root->attributes;

        foreach ($attrs as $i => $attr)
            $result[$attr->name] = $attr->value;
    }

    $children = $root->childNodes;

    if ($children->length == 1)
    {
        $child = $children->item(0);

        if ($child->nodeType == XML_TEXT_NODE)
        {
            $result['_value'] = $child->nodeValue;

            if (count($result) == 1)
                return $result['_value'];
            else
                return $result;
        }
    }

    $group = array();

    for($i = 0; $i < $children->length; $i++)
    {
        $child = $children->item($i);

        if (!isset($result[$child->nodeName]))
            $result[$child->nodeName] = dom_to_array($child);
        else
        {
            if (!isset($group[$child->nodeName]))
            {
                $tmp = $result[$child->nodeName];
                $result[$child->nodeName] = array($tmp);
                $group[$child->nodeName] = 1;
            }

            $result[$child->nodeName][] = dom_to_array($child);
        }
    }

    return $result;
} 




echo ('
    <html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Projektor | test |</title>
        <link rel="icon" type="image/gif" href="../favicon.gif"></link>
        <link rel="stylesheet" type="text/css" href="../css/default.css" />
        <link rel="stylesheet" type="text/css" href="../css/highlight.css" />
    </head>

    <body>
        ');

//  $doc = loadDocument('c:\_Export Projektor\content.xml' );
//  $list = $doc->getElementsByTagName('*');
//  $cnt = $list->length;
//  for ($i = 0; $i < $cnt; $i++) {
//      $item = $list->item($i);
//      $textContent = $item->textContent;
//      echo $textContent.'<br>';
//  }
  
$xmlDoc=<<<XML
<?xml version="1.0"?>
<methodCall>
   <methodName>examples.getStateName</methodName>
   <params>
      <param>
         <value><i4>41</i4></value>
         </param>
      </params>
   </methodCall>
XML;

//$doc= new DOMDocument();
//$doc->loadXML($xmlDoc);
$doc = loadDocument('c:\_Export Projektor\content.xml' );
echo '<pre>';
print_r(xmlToArray($doc));
echo '</pre>'; 
echo '<hr>';  
echo '<pre>';
print_r(dom2Array($doc));
echo '</pre>';   
echo '<hr>';  
echo '<pre>';
print_r(xml2Array($doc));
echo '</pre>'; 
echo '<hr>';  
echo '<pre>';
print_r(dom2array_full($doc));
echo '</pre>'; 
echo '<hr>';  
echo '<pre>';
print_r(dom_to_array($doc));
echo '</pre>';


  echo ('
    </body>
    </html>
        ');
?>
