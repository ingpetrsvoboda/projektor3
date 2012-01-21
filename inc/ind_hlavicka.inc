<?php
//ind_hlavicka.inc
?>
<html>
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
        <title>Grafia.cz | Projektor | <?php echo $Projekt->text ?> | <?php echo $Kancelar->text ?></title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    <SCRIPT LANGUAGE=JavaScript>
<!--
  var navratovy_objekt
  function kzam_okno(nova_url, jmeno)
  {
  navratovy_objekt = document.getElementById(jmeno);
    window.open(nova_url,"Vyber_kzam");
  }
//-->
</SCRIPT>
    </head>
    <body bgcolor="silver">
        <DIV ID="con" >
            <?php   
            //echo "*Host: " . $dbh->dbhost .  "&nbsp;&nbsp;&nbsp; *Databáze: " . $dbh->dbname . "&nbsp;&nbsp;&nbsp; *Uživatel: "  . $data_users['username'] ;
            echo "Uživatel  &nbsp;&nbsp;&nbsp; " . $data_users['username'] 
            		. " &nbsp;&nbsp;&nbsp;pracuje s databází  &nbsp;&nbsp;&nbsp; "
            		. $dbh->__get(dbhost) . "/" . $dbh->__get(dbname)  ;
            ?>
        </DIV>
        <DIV ID="logout-ie">
        <DIV ID="logout">
        <form name="Logout" ID="Logout" action="./logout.php" method="get">
            <input type="Submit" value="Odhlásit">
        </form>
        </DIV>
        </DIV>
    <center>
    