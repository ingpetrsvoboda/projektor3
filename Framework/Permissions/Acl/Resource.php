<?php
/**
 * Description of Resource
 *
 * @author pes2704
 */
class Framework_Permissions_Acl_Resource implements Framework_Permissions_Acl_ResourceInterface{

    /**
     *
     * @var Framework_Router_Route 
     */
    private $resourceRoute;
    private $resourceParent;
    private $resourceChildren = array();

    public function __construct(Framework_Router_Route $resourceRoute) {
        $this->resourceRoute = (string) $resourceRoute;
    }

    /**
     * 
     * @return Framework_Router_Route
     */
    public function getResourceRoute() {
         return $this->resourceRoute;
    }
}

?>
