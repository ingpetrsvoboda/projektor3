<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Framework_Controller_Abstract
 *
 * @author pes2704
 */
abstract class Framework_Controller_AbstractMiddlewareController extends Framework_Controller_AbstractController {
    const DEFAULT_PROCEEDING_ALLOWED = FALSE;
    
    public function __construct(Framework_Response_Output $output, array $controllerParams = null) {
        parent::__construct($output, $controllerParams);
        $this->output->setProceedingAllowed(self::DEFAULT_PROCEEDING_ALLOWED);
    }
    
    public function setProceedingAllowed($allowed=FALSE) {
        $this->output->setProceedingAllowed($allowed);
    }
}
