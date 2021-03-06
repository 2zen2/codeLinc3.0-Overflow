<?php

/**
 * ======================================================================
 * LICENSE: This file is subject to the terms and conditions defined in *
 * file 'license.txt', which is part of this source code package.       *
 * ======================================================================
 */

/**
 * Capability object
 * 
 * @package AAM
 * @author Vasyl Martyniuk <vasyl@vasyltech.com>
 */
class AAM_Core_Object_Capability extends AAM_Core_Object {

    /**
     * Constructor
     *
     * @param AAM_Core_Subject $subject
     *
     * @return void
     *
     * @access public
     */
    public function __construct(AAM_Core_Subject $subject) {
        parent::__construct($subject);
        
        $this->setOption($this->getSubject()->getCapabilities());
    }

    /**
     * 
     * @param type $capability
     * @param type $granted
     */
    public function save($capability, $granted) {
        if (intval($granted)) {
            $result = $this->getSubject()->addCapability($capability);
        } else {
            $result = $this->getSubject()->removeCapability($capability);
        }
        
        return $result;
    }

    /**
     *
     * @param type $capability
     * @return type
     */
    public function has($capability) {
        return $this->getSubject()->hasCapability($capability);
    }
    
}