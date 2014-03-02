<?php
/**
 * Description of Login
 *
 * @author pes2704
 */
class Projektor_Dispatcher_Loginlogout extends Framework_Dispatcher_AbstractDispatcher implements Framework_Dispatcher_DispatcherInterface {
    
    public function __construct() {
        $controller = new Projektor_Controller_Login();
        $this->attachMiddlewareController($controller);
    }

}

?>
