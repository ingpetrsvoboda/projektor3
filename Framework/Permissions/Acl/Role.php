<?php
/**
 * Description of Role
 *
 * @author pes2704
 */
class Framework_Permissions_Acl_Role implements Framework_Permissions_Acl_RoleInterface {
    
    protected $parentRoles = array();
    protected $roleId;
    
    public function __construct($roleId, $parent) {
        if (is_string($roleId)) {
            $this->roleId = $roleId;
            if (is_array($parent)) {
                foreach ($parent as $p) {
                    if (is_a($p, self)) {
                        $this->parentRoles[] = $p;
                    } else {
                        throw new InvalidArgumentException('All of $parent array items must be an instance of '.__CLASS__);
                    }                    
                }
            } else {
                if (is_a($parent, self)) {
                    $this->parentRoles[] = $parent;
                } else {
                    throw new InvalidArgumentException('Parametr $parent must be an instance of '.__CLASS__);
                }
            }
        } else {
            throw InvalidArgumentException('Parameter $roleId must be a string.');
        }
    }
    
    /**
     * 
     * @return string
     */
    public function getRoleName() {
        return $this->roleId;
    }
    
    /**
     * 
     * @return array[Framework_Permissions_Acl_Role] array of Framework_Permissions_Acl_Role
     */
    public function getParentRoles() {
        return $this->parentRoles;
        
    }
}

?>
