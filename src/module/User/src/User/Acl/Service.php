<?php

/**
 * namespace definition and usage
 */
namespace User\Acl;

use Zend\Permissions\Acl\Acl;

/**
 * Acl Service
 * 
 * @package    User
 */
class Service
{
    /**
     * @var string
     */
    protected $role = 'guest';

    /**
     * @var array
     */
    protected $config = array();

    /**
     * @var Acl
     */
    protected $acl = null;

    /**
     * Constructor
     * 
     * @param string $role
     * @param array $config
     */
    public function __construct($role = 'guest', array $config)
    {
        $this->setRole($role);
        $this->setConfig($config);
        $this->setAcl($this->buildAcl());
    }

    /**
     * Get role
     * 
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }
    
    /**
     * Set role
     * 
     * @param string $role
     * @return Service
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }
    
    /**
     * Get config
     * 
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }
    
    /**
     * Set config
     * 
     * @param array $config
     * @return Service
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }
    
    /**
     * Get acl
     * 
     * @return Acl
     */
    public function getAcl()
    {
        return $this->acl;
    }
    
    /**
     * Set acl
     * 
     * @param Acl $acl
     * @return Service
     */
    public function setAcl(Acl $acl)
    {
        $this->acl = $acl;
        return $this;
    }
    
    /**
     * Create acl instance
     *
     * @return Acl
     */
    public function buildAcl()
    {
        // create acl
        $acl = new Acl();
        $acl->addRole('guest');
        $acl->addRole('customer', 'guest');
        $acl->addRole('staff', 'customer');
        $acl->addRole('admin', 'staff');
        
        // loop through role data
        foreach ($this->config as $role => $resources) {
            // loop through resource data
            foreach ($resources as $resource => $rules) {
                // check for resource
                if (!$acl->hasResource($resource)) {
                    $acl->addResource($resource);
                }
        
                // loop trough rules
                foreach ($rules as $rule => $privileges)
                {
                    // add rule with privileges
                    $acl->$rule($role, $resource, $privileges);
                }
            }
        }
        
        // pass acl
        return $acl;
    }
    
    /**
     * Check if current role is allowed to access resource with privilege
     *
     * @param string $resource
     * @param string $privilege
     * @return boolean
     */
    public function isAllowed($resource, $privilege)
    {
        // check resource
        if (empty($resource) || !$this->getAcl()->hasResource($resource)) {
            return false;
        }
        
        // check privilege
        if (empty($privilege)) {
            return false;
        }
        
        // check acl
        return $this->getAcl()->isAllowed(
            $this->getRole(), $resource, $privilege
        );
    }
}
