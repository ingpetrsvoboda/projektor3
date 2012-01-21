<?php
/* 
 * 
 */

class User_Kontext
{
    public $user;
    public $projekt;
    public $kancelar;
    public $beh;
    public $povoleneProjekty;
    public $povoleneKancelare;

    public function __construct($user = null, $projekt = null, $kancelar = null, $beh = null, $povoleneProjekty = NULL, $povoleneKancelare = NULL)
    {
        $this->user = $user;
        $this->projekt = $projekt;
        $this->kancelar = $kancelar;
        $this->beh = $beh;
        $this->povoleneProjekty = $povoleneProjekty;
        $this->povoleneKancelare = $povoleneKancelare;
    }


}



?>
