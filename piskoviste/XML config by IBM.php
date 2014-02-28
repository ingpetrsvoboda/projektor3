<?php
/**
http://www.ibm.com/developerworks/library/os-php-config/
XML configuration files
While text files are easy to understand and edit, they aren't as trendy as XML files. Plus, XML files have the advantage of having lots of editors for them that understand tags, special-character escaping, and more. So what does an XML version of the configuration file look like? Listing 11 shows the configuration file as XML.

Listing 11. config.xml

<?xml version="1.0"?>
<config>
  <Title>My App</Title>
  <TemplateDirectory>tempdir</TemplateDirectory>
</config>


Listing 12 shows an updated version of the Configuration class that uses XML to load the configuration settings.

Listing 12. xml1.php

 */



class Configuration
{
  private $configFile = 'config.xml';

  private $items = array();

  function __construct() { $this->parse(); }

  function __get($id) { return $this->items[ $id ]; }

  function parse()
  {
    $doc = new DOMDocument();
    $doc->load( $this->configFile );

    $cn = $doc->getElementsByTagName( "config" );

    $nodes = $cn->item(0)->getElementsByTagName( "*" );
    foreach( $nodes as $node )
      $this->items[ $node->nodeName ] = $node->nodeValue;
  }
}

$c = new Configuration();
echo( $c->TemplateDirectory."\n" );
?>

