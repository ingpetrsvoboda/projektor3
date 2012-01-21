<?php
//ind_vyber_beh.inc
setcookie("beh_id");
?>

<h2>Registrace účastníků</h2>
<hr>
    <form name="Beh" ID="Beh" action="index.php" method="post">
    <input type="hidden" name="akce" value="zobraz_reg">
    <label for="beh" >Vyberte turnus projektu:</label>
    <select ID="beh" size="1" name="beh">
<?php
            $query="SELECT id_s_beh_projektu,beh_cislo,text
                    FROM s_beh_projektu
                    WHERE id_c_projekt = :1 and valid=1";
            $data=$dbh->prepare($query)->execute($Projekt->id);
            while ($radek = $data->fetch_assoc()){
                echo "<option value=\"".$radek['id_s_beh_projektu']."\">".$radek['text'];
                
                //$datum = new Datum($radek['zacatek'],"MySQL");
                //echo " - od ".$datum->f_web;
                //If( $radek['konec'] !=NULL) {
                //    $datum = new Datum($radek['konec'],"MySQL");
                //    echo " do ".$datum->f_web;
                //}
                
                echo "</option>\n";
            }
?>
    </select>
    <br><br>
    <input type="submit" value="Zobrazit registrace">
    </form>  
<hr>

    

<!--     
<h2>Statistické formuláře</h2>
<hr>
    <form name="Beh" ID="Beh" action="index.php" method="post">
    <input type="hidden" name="akce" value="zobraz_stat">
    <label for="beh" >Vyberte turnus projektu:</label>
    <select ID="beh" size="1" name="beh">
-->    
<?php 
        /*    $query="SELECT id_s_beh_projektu,beh_cislo,text
                    FROM s_beh_projektu
                    WHERE id_c_projekt = :1 and valid=1";
            $data=$dbh->prepare($query)->execute($Projekt->id);
            while ($radek = $data->fetch_assoc()){
                echo "<option value=\"".$radek['id_s_beh_projektu']."\">".$radek['text'];
 */       
                //$datum = new Datum($radek['zacatek'],"MySQL");
                //echo " - od ".$datum->f_web;
                //If( $radek['konec'] !=NULL) {
                //    $datum = new Datum($radek['konec'],"MySQL");
                //    echo " do ".$datum->f_web;
                //}
                
                
   /*             echo "</option>\n";
            }
   */
?>
<!--     </select>
    <br>
    <br>
-->        
<?php
         /*   $query="SELECT id_c_stat_dotazniky,razeni,kod,text
                    FROM c_stat_dotazniky";
            $data=$dbh->prepare($query)->execute('');
            while($radek = $data->fetch_assoc()){
                echo "<input type=\"submit\" name=\"dot\" value=\"".$radek['text']."\" ><br>";
            }
        */    
?>
<!--     </form>-->
    
