<?php
class Data_Kontrola_DatumCas 
{
    private $den=0;
    private $mesic=0;
    private $rok=0;
    private $hodina=0;
    private $minuta=0;
    private $sekunda=0;
    public $ok=false;
    public $f_mysql = false;
    
    public function __construct($datum=false,$typ=false) 
    {
        if($datum) 
        {
            if(!$typ) 
            {
                $datum=trim($datum);
                $regex_pattern="^([1-9]|1[0-9]|2[0-9]|3[0-1])\.([1-9]|1[0-2]|0[1-9])\.([1-2][0-9]{3})( )([0-2][0-9]|[0-9]):([0-5][0-9]|[0-9]):([0-5][0-9]|[0-9])";
                if (ereg($regex_pattern, $datum, $regs) && checkdate($regs[2],$regs[1],$regs[3])) 
                {
                    $this->den=trim($regs[1]);
                    $this->mesic=trim($regs[2]);
                    $this->rok=trim($regs[3]);
                    $this->hodina=trim($regs[5]);
                    $this->minuta=trim($regs[6]);
                    $this->sekunda=trim($regs[7]);
                    $this->ok=true;
                    $this->f_mysql = $this->rok."-".$this->mesic."-".$this->den." ".$this->hodina.":".$this->minuta.":".$this->sekunda;
                    return;
                }
            }
        }
        else 
        {
            if($datum =="") 
            {
                $this->f_mysql = "NULL";
                $this->ok=true;
                return;
            }
        }
        $this->ok=false;
    }

}
?>