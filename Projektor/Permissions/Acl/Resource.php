<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Resource
 *
 * @author pes2704
 */
class Projektor_Permissions_Acl_Resource implements Projektor_Permissions_Acl_ResourceInterface{

    private $resourceId;
    private $resourceParent;
    private $resourceChildren = array();

    public function __construct($resourceClassName) {
        $this->resourceId = (string) $resourceClassName;
    }

    public function getResourceId() {
         return $this->resourceId;
    }
}

?>
