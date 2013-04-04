<?php
class Projektor_App_Router_Base implements Projektor_App_Router_RouterInterface
{
    /**
     * instanční proměnná
     * Návratový objekt vracený objektem $this->dispatcher (Projektor_Dispatcher_DispatcherInterface),
     * je předáván jako instanční proměnná při vytváření objektu dispatcher
     * @var Projektor_App_Response_ResponseInterface
     */
    private $response;

    private $isEvaluated;
    // proměnné přístupné přes gettery
    private $route;
    private $dispatcher;
    private $params = array();

    public function __construct(Projektor_App_Response_ResponseInterface $response) {
        $this->response = $response;
    }

    public function getRoute() {
        if (!isset($this->isEvaluated) OR !$this->isEvaluated) $this->evaluateRoute();
        return $this->route;
    }

    public function getDispatcher() {
        if (!isset($this->isEvaluated) OR !$this->isEvaluated) $this->evaluateRoute();
        return $this->dispatcher;
    }

    public function getParams() {
        if (!isset($this->isEvaluated) OR !$this->isEvaluated) $this->evaluateRoute();
        return $this->params;
    }

    private function evaluateRoute() {
        if (isset($_GET['route'])) {
            $this->route = $_GET['route'];
        } else {
            $this->route = '';
        }

        switch ($this->route) {
            case 'login':
                $this->dispatcher = new Projektor_Dispatcher_Login($this->response);
                break;
            case 'logout':
                $this->dispatcher = new Projektor_Dispatcher_Logout($this->response);
                break;
            case 'strom':
                $this->params = $_REQUEST;
//                if (isset($_GET['cesta'])) {
//                    $this->params['cesta'] = $_GET['cesta'];
//                }
//                if (isset($_GET["debug"])) {
//                    if ($_GET["debug"]=="0") $appStatus->debug = FALSE;
//                    if ($_GET["debug"]=="1") $appStatus->debug = TRUE;
//                }
                $this->dispatcher = new Projektor_Dispatcher_Cesta($this->response, $this->params);
                break;
            case '':
                $this->dispatcher = new Projektor_Dispatcher_Cesta($this->response);
                break;

            default:
                throw new UnexpectedValueException('Unknown routing.');
                break;
        }
        $this->isEvaluated = TRUE;
    }
}