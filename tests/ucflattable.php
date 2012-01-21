<?php
    require_once("../autoload.php");
    
    class Data_Flat_TestFlatTable extends Data_Flat_FlatTable {
    public function __construct(Data_Ucastnik $ucastnik){
        parent::__construct("test_flat_table",$ucastnik);
        }
}
        echo "<pre>";
        $Ucastnik = Data_UcastnikMapper::find_by_id(111);
        //echo "Ucastnik:<br>";
        //print_r($Ucastnik);
        $testFlatTable = new Data_Flat_TestFlatTable($Ucastnik);
        //echo "=============================================================================================================<br>";
        echo "object typu Data_Flat_TestFlatTable:<br>";
	print_r($testFlatTable);
        echo "</pre>";
?>
