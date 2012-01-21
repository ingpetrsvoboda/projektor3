<?php
    $regex_pattern="^([1-9]|0[0-9]|1[0-9]|2[0-9]|3[0-1])\.( [1-9]|[1-9]|1[0-2]|0[1-9])\.( [1-2][0-9]{3}|[1-2][0-9]{3})";
    ereg($regex_pattern, "12.12.2012", $regs) && checkdate($regs[2],$regs[1],$regs[3]);   // SVOBODA This function has been DEPRECATED as of PHP 5.3.0. Relying on this feature is highly discouraged.
    print_r($regs);                //preg_match()
    $regex_pattern="/^([1-9]|0[0-9]|1[0-9]|2[0-9]|3[0-1])\.( [1-9]|[1-9]|1[0-2]|0[1-9])\.( [1-2][0-9]{3}|[1-2][0-9]{3})";
    preg_match($regex_pattern, "12.12.2012", $regs) && checkdate($regs[2],$regs[1],$regs[3]);   // SVOBODA This function has been DEPRECATED as of PHP 5.3.0. Relying on this feature is highly discouraged.
    print_r($regs);
?>
