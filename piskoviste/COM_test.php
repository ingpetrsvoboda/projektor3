<?php
//1
  $shell= &new COM('WScript.Shell');
    var_dump($shell->regRead('HKEY_LOCAL_MACHINE\SOFTWARE\Microsoft\Windows\CurrentVersion\Group Policy\State\Machine\Distinguished-Name'));
    var_dump($shell->regRead('HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\ComputerName\ActiveComputerName\ComputerName'));
    var_dump($shell->regRead('HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\services\Tcpip\Parameters\Hostname'));
    
?>
