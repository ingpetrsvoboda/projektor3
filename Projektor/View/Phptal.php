<?php
/**
 * Description of Twig
 *
 * @author pes2704
 */
class Projektor_View_Phptal implements Projektor_View_ViewInterface {

    private $context;
    private $templateSystemObject;
    private $templateFileName;

    public function __construct(array $context, PHPTAL $templateSystemObject, $templateteFileName) {
        $this->context = $context;
        $this->templateSystemObject = $templateSystemObject;
        $this->templateFileName = $templateteFileName;
    }

    public function render() {
        $this->templateSystemObject->setTemplate($this->templateFileName);
        foreach($this->context as $klic => $hodnota) $this->templateSystemObject->$klic = $hodnota;
        $content = $this->templateSystemObject->execute($this->templateFileName, $this->context);
        return $content;
    }
}

?>
