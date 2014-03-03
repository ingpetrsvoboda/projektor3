<?php

ob_start();

define ("CLASS_PATH", "../");
// zajištění autoload pro Projektor
require_once CLASS_PATH.'Projektor/Autoloader.php';
Projektor_Autoloader::register();
// zajištění autoload pro Framework
require_once CLASS_PATH.'Framework/Autoloader.php';
Framework_Autoloader::register();
// zajištění autoload pro Twig
require_once CLASS_PATH.'Twig/Autoloader.php';
Twig_Autoloader::register();
require_once CLASS_PATH.'Classes/PHPExcel.php';  // uvnitř v Classes/PHPExcel.php se provede PHPExcel_Autoloader::Register();

//###############################################################################
class TestApplication extends Framework_Application_AbstractApplication {

    /**
     * Metoda zabezpečuje celé zpracování hhtp requestu.
     * Metoda vytvoří objekt $appStatus, který načte minulý stav aplikace.
     * Vytvoří objekt $router. Objekt router provede vyhodnocení requestu a vrácení
     * vhodného dispatcheru, dispatcher pak požádá o vygenerování response (dispatcher pro vytvoření response volá Controller a View).
     * Pokud hlavička http response je "redirection", metoda pouze uloží nový stav $appStatus a provede přesměrování. 
     * Pokud se nejedná o přesměrování,
     * metoda uloží nový $appStatus a tělo vygenerovaného response odešle na výstup.
     */
    public function run() {
        $this->appRouter = new TestRouter($this->appRequest, $this->appStatus);
        $this->appDispatcher =  $this->appRouter->getDispatcher();
        $this->appResponse = $this->appDispatcher->dispatch();

        $this->appStatus->responseTime = microtime(TRUE);
        $this->appResponse->send();
    }
}

class TestRouter extends Framework_Router_AbstractRouter {
    public function getDispatcher() {
        switch ($this->request->get('controller')) {
            case 'loginlogout':
            case 'test':
            case '':
                return new TestDispatcher();
                break;

            default:
                throw new UnexpectedValueException('Unknown routing. Route in $_GET is: '.$this->request->get('route').' .');
                break;
        }
        return FALSE;
    }
}

class TestDispatcher extends Framework_Dispatcher_AbstractDispatcher {
    public function __construct() {
        parent::__construct();
        $this->attachMiddlewareController(new TestController(new Framework_Response_Output()));
        $this->attachMiddlewareController(new Projektor_Controller_Loginlogout(new Framework_Response_Output()));
        $this->attachController(new TestControllerPrivate(new Framework_Response_Output()));
        $this->attachController(new TestControllerPrivateChild(new Framework_Response_Output()));
    }
}

class TestController extends Framework_Controller_AbstractController {
    public function getOutput() {
        $view = new TestViewHead();
        $htmlDocument = new Framework_Document_Html();
        $headElem = $htmlDocument->getHtmlElement()->getHeadElement();
        $headElem->appendText($view->render());  //vytváří jen element <head>
        
        $this->output->setDocument($htmlDocument);
        return $this->output;
    }
}

class TestControllerPrivate extends Framework_Controller_AbstractController {
    public function getOutput() {
        $application = Framework_Application_AbstractApplication::getInstance();
        $request = $application->getRequest();
        $appStatus = $application->getAppStatus();        

        $this->context['cookies'] = $request->cookies();
        $this->context['headers'] = $request->headers();
        $this->context['uri'] = $request->getScriptName();
        if ($request->isGet()) {
            $this->context['method'] = 'Metoda HTTP requestu je GET';
            $this->context['get'] = $request->get();
        }
        if ($request->isPost()) {
            $this->context['method'] = 'Metoda HTTP requestu je POST';
            $this->context['post'] = $request->post();
        }        
        $this->context['params'] = $request->params();
        $this->context['kukByl'] = $appStatus->kuk;
        $this->context['kukJe'] = 'Kuk! Je '.  time() ;
        
        $appStatus->originating_uri = $request->getRequestUri();
        $appStatus->kuk = $this->context['kukJe'];
        $this->context['appStatus'] = 
        $this->context['session'] = $_SESSION;
                
        $view = new TestViewPrivate();
        $htmlDocument = new Framework_Document_Html();
        $bodyElem = $htmlDocument->getHtmlElement()->getBodyElement();
        $bodyElem->appendText($view->render($this->context));
        $this->output->setDocument($htmlDocument);        
        return $this->output;
    }
}

class TestControllerPrivateChild extends Framework_Controller_AbstractController {
    public function getOutput() {
        $view = new TestViewPrivateChild();
        $htmlDocument = new Framework_Document_Html();
        $bodyElem = $htmlDocument->getHtmlElement()->getBodyElement();
        $bodyElem->appendText($view->render($this->context));
        $this->output->setDocument($htmlDocument);  
        $this->output->setSlot(Framework_Helper_Slot::getSlotHtmlCode('next'));
        return $this->output;        
    }
}

class TestViewHead extends Framework_View_View {
    public function render(array $context = NULL) {
        $content = '
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>Projektor | test |</title>
                <link rel="icon" type="image/gif" href="favicon.gif"></link>
                <link rel="stylesheet" type="text/css" href="css/default.css" />
                <link rel="stylesheet" type="text/css" href="css/highlight.css" />
                ';
        return $content;
    }
}

class TestViewPrivate extends Framework_View_View {
    public function render(array $context = NULL) {
        $content .= $context['child'];
        
        $content .= '<H1>TestView</H1>';
        $content .= '<p>'.$context['method'].'</p>';
        $content .= '<p>Get (jen když metoda je GET):</p>';
        $content .= '<pre>'.print_r($context['get'], TRUE).'</pre>';
        $content .= '<p>Post (jen když metoda je POST):</p>';
        $content .= '<pre>'.print_r($context['post'], TRUE).'</pre>';
        $content .= '<p>Params:</p>';
        $content .= '<pre>'.print_r($context['params'], TRUE).'</pre>';
        $content .= '<p>Cookies:</p>';
        $content .= '<pre>'.print_r($context['cookies'], TRUE).'</pre>';
        $content .= '<p>Headers:</p>';
        $content .= '<pre>'.print_r($context['headers'], TRUE).'</pre>';
        
        $content .= Framework_Helper_Slot::getSlotHtmlCode('next');
        
        $content .= '<form name="TestApplication" ID="TestApplication" action="'.$context['uri'].'?hodnota_get_z_action_formulare=GETsPOSTem" method="post">
                            <input type="text" name="hodnota_post"></input>
                           <input type="Submit" value="Odeslat POST" \>
                       </form>';
        $content .= '<p><a title="Test" href="'.$context['uri'].'?controller=blabla">Odeslat GET controller=blabla</a></p>';
        $content .= '<p><a title="Test" href="'.$context['uri'].'?hodnota_get=resource/:id">Odeslat GET hodnota_get=resource/:id</a></p>';
        $content .= '<p><a title="Test" href="'.$context['uri'].'?resource/:id">Odeslat GET resource/:id</a></p>';         

        $content .= '<p>Minulý kuk: '.$context['kukByl'].'</p>';
        $content .= '<p>Současný kuk: '.$context['kukJe'].'</p>';
        
        $content .= '<p>Application status:</p>';
        $content .= '<pre>'.print_r($context['appStatus'], TRUE).'</pre>';
        $content .= '<p>$_SESSION:</p>';
        $content .= '<pre>'.print_r($context['session'], TRUE).'</pre>';

        return $content;
    }
}

class TestViewPrivateChild extends Framework_View_View {
    public function render(array $context = NULL) {
        $content = '<div><h2>Jsem TestViewPrivateChild</h2></div>';
        return $content;
    }
}
//###############################################################################


$app = new TestApplication(new Framework_Request_Request(), new Framework_Application_Status());
$app->run();
echo '<p>Log:</p><pre>'.Framework_Logger::getLogText().'</pre>';
?>
