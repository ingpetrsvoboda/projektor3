<?php
/**
 * Description of Acl
 *
 * @author pes2704
 */
class Framework_Permissions_Acl_Acl {
    protected $acl;
    
    private $roles;
    private $resources;
    
    /**
     * Přidá roli.
     * @param Framework_Permissions_Acl_RoleInterface $role
     * @return \Framework_Permissions_Acl_Acl
     */
    public function addRole(Framework_Permissions_Acl_RoleInterface $role) {
        $this->roles[$role->getRoleName()] = $role;
        return $this;
    }
    
    public function addResource(Framework_Permissions_Acl_Resource $resource) {
        $this->resources[$resource->getResourceRoute()->getName()] = $resource;
        return $this;
    }
    
    public function isAllowed(Framework_Permissions_Acl_RoleInterface $role, Framework_Permissions_Acl_Resource $resource, ) {
        
    }
}
