<?php

/**
 * Description of Response
 *
 * @author pes2704
 */
class Projektor_App_Response_Response implements Projektor_App_Response_ResponseInterface {

    private $redirectLocation;
    private $responseBody;

    public function setRedirectLocation($redirectLocation) {
        $this->redirectLocation = $redirectLocation;
    }

    public function isRedirection() {
        if (isset($this->redirectLocation)) return TRUE;
        return FALSE;
    }

    public function redirect() {
        if ($this->isRedirection()) {
            header("Location: ".$this->redirectLocation);
            exit;
        } else {
            throw new BadMethodCallException('Unable to redirect - redirect location is not set.');
        }
    }

    public function setResponseBody($responseBody) {
        $this->responseBody = $responseBody;
    }

    public function getResponseBody() {
        return $this->responseBody;
    }
}

?>
