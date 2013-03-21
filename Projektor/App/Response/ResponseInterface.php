<?php

/**
 *
 * @author pes2704
 */
interface Projektor_App_Response_ResponseInterface {
    public function setRedirectLocation($uri);
    public function isRedirection();
    public function redirect();
    public function getResponseBody();
    public function setResponseBody($content);
}

?>
