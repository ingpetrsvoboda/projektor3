<?php

/**
 *
 * @author pes2704
 */
interface Framework_Router_RouterInterface {
    public function __construct(Framework_Request_Request $request, Framework_Application_StatusInterface $appStatus);
    public function getDispatcher();
}

?>
