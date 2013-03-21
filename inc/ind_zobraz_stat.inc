<?php
//ind_zobraz_reg.inc

if(isset($_POST['beh'])) {
    $beh_id = $_POST['beh'];
}
elseif(isset($_COOKIE['beh_id'])){
    $beh_id = $_COOKIE['beh_id'];
}
$dot_id = 0;
$dot_text = "nifsdfsdfdsf";
//Nastavení dotazniku
if(isset($_POST['dotaznik_id'])) {
    $dot_id = $_POST['dotaznik_id'];
}
elseif (isset($_POST['dot'])){
    $dot_text = $_POST['dot'];
}
else {
    throw new Exception("No info about Statistical form");
}
$query="SELECT id_c_stat_dotazniky AS id,text
        FROM c_stat_dotazniky
        WHERE id_c_stat_dotazniky = :1
        OR text LIKE :2";
$data=$dbh->prepare($query)->execute($dot_id,$dot_text);
if(!$data) {
    throw new Exception("Selected non existing Statistical form");
    }
else {
    $dotaznik = $data->fetch_assoc();
}


//Nastaveni behu
setcookie("beh_id",$beh_id);
//Vyber osob z databaze
$query="SELECT beh_cislo,text,zacatek,konec
        FROM s_beh_projektu
        WHERE id_c_projekt = :1
        AND id_s_beh_projektu = :2";
$data=$dbh->prepare($query)->execute($Projekt->id,$beh_id)->fetch_assoc();

echo '<ul id="menu"><li><a href="index.php?akce=unset_beh">Zpět k výběru turnusu</a></li></ul>';      
echo "<li><h3>Přehled vyplnění dotazníku účastníky</h3></li>";
echo "<h2>Dotazník:".$dotaznik['text']."</h2><br>";
echo "<h2>Běh projektu:".$data['beh_cislo'];
$datum = new Projektor_Data_Konverze_Datum($data['zacatek'],"MySQL");
echo " (od ".$datum->f_web;
if( $data['konec'] !=NULL) {
    $datum = new Projektor_Data_Konverze_Datum($data['konec'],"MySQL");
    echo " do ".$datum->f_web;
}
echo ")</h2>";
if(strlen($data['text'])>2) {
        echo " - ".$data['text'];
}


?>
       <div ID="zaznamy-stat">
       <form name="Statistika" ID="Statistika" action="index.php" method="post">
       <input type="hidden" name="akce" value="uloz_vyplnil_stat">
       <input type="hidden" name="dotaznik_id" value="<?php echo $dotaznik['id'] ?>">
<?php            
//Zobrazení zaregistrovaných osob
$query = "SELECT v_ucastnici.id_ucastnik,jmeno_cele,datum_vyplneni,c_stat_dotazniky.id_c_stat_dotazniky AS dotaznik_id
          FROM v_ucastnici
          Left Join uc_stat_dotazniky ON v_ucastnici.id_ucastnik = uc_stat_dotazniky.id_ucastnik
          Left Join c_stat_dotazniky ON uc_stat_dotazniky.id_c_stat_dotazniky = c_stat_dotazniky.id_c_stat_dotazniky
          WHERE id_s_beh_projektu = :1
          AND id_c_kancelar=:2
          AND (c_stat_dotazniky.id_c_stat_dotazniky= :3
          OR c_stat_dotazniky.id_c_stat_dotazniky IS NULL)";
$data = $dbh->prepare($query)->execute($beh_id,$Kancelar->id,$dotaznik['id']);
$datum_dnes = date('d.m.Y');

while ($zaznam = $data->fetch_assoc()) {
        $datum_vyplneni = new Projektor_Data_Konverze_Datum($zaznam['datum_vyplneni'],'MySQL');
        echo '<table style="text-align: left; width: 700px;" border="0" cellpadding="2" cellspacing="2"><tbody><tr>';
        echo '<td style="width: 500px;">'.$zaznam['jmeno_cele'];
        if(!(!is_null($zaznam['datum_vyplneni']) && $zaznam['dotaznik_id'] == $dotaznik['id'])) {
           echo '<font color="red"> - nevyplněno!';}
        echo '</td>';
        if(!is_null($zaznam['datum_vyplneni']) && $zaznam['dotaznik_id'] == $dotaznik['id']) {
            echo '<td style="width: 200px;"><font color="green">OK</font> - Vyplnil: '.$datum_vyplneni->f_web.'</td></tr></tbody></table><hr>';
        }
        else {
            echo '<td style="width: 100px;"><input type="submit" name="uc_'.$zaznam['id_ucastnik'].'" value="Vyplnil dne:"></td>';
            echo '<td style="width: 100px;"><input type="text" name="datum_vyplneni_'.$zaznam['id_ucastnik'].'" value="'.$datum_dnes.'"></td></tr></tbody></table><hr>';
        }
    }
?>
    </form>
    </div>
            
