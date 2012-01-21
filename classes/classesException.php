<?php

/**
 * Abstraktní vlastní třída výjimek, ke každé "package" je vytvořena odvozená třída výjimek, 
 * každá odvozená třída obsluhující výjimky příslušné "package" je v souboru Exception.php umístěném ve složce dané "package"
 * @author Petr Svoboda, ask at nilpo dot com http://www.php.net/manual/en/language.exceptions.php
 *
 */
abstract class classesException extends Exception implements InterfaceException
{
    protected $message = 'Neznámá výjimka';       // Zpráva výjimky
    private   $string;                            // nedefinováno
    protected $code    = 0;                       // Uživatelsky definovaný kód výjimky
    protected $file;                              // Název souboru v němž došlo k výjimce
    protected $line;                              // Zdrojová řádka výjimky
    private   $trace;                             // nedefinováno

    public function __construct($message = null, $code = 0)
    {
        if (!$message) {
            throw new $this('Neznámá '. get_class($this));
        }
        parent::__construct($message, $code);
    }
    
    public function __toString()
    {
        return 	
                '<div class="vyjimka">'.
                    get_class($this) . " '{$this->message}', k chybě došlo v souboru {$this->file}(řádek {$this->line})<BR>" .
                    "Zásobník: {$this->getTraceAsString()}<BR>".
                '</div>' ;
    }
}
?>