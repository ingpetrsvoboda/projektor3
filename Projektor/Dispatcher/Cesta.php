<?php
class Projektor_Dispatcher_Cesta extends Projektor_Dispatcher_Base implements Projektor_Dispatcher_DispatcherInterface {

    private static $prefixCesty;
    private static $koren;

    public static function getPrefixCesty()
    {
        return self::$prefixCesty;
    }

    private static function setPrefixCesty($prefix)
    {
        self::$prefixCesty = $prefix;
    }

    private static function setKoren(Projektor_Dispatcher_Uzel $koren = NULL)
    {
            if ($koren) self::$koren = $koren;
            return self::$koren;
    }

    public static function getKoren()
    {
        return self::$koren;
    }

    public function getResponse(Projektor_App_StatusInterface $appStatus) {
        Projektor_Dispatcher_Cesta::setPrefixCesty($_SERVER["SCRIPT_NAME"]."?route=strom&cesta=");
        Projektor_App_Logger::resetLog();

        if ( !isset($this->controllerParams['cesta'])) {
            $koren = new Projektor_Dispatcher_Uzel("Projektor_Stranka_Index", null, null, FALSE);
        } else {
            $koren = unserialize($this->controllerParams['cesta']);
        }
        self::setKoren($koren);
        $content = $koren->traverzuj($appStatus);
        $this->response->setResponseBody($content);
        return $this->response;    }
}