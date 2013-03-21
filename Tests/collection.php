<?php

/*
 * @author http://www.php.net/manual/en/language.oop5.iterations.php, pes2704
 */
// test iterator
$values = array(1,2,3);
$it = new Projektor_Data_ItemIterator($values);

foreach ($it as $a => $b) {
    print "$a: $b\n";
}

// test collection
$coll = new Projektor_Data_Collection();
$coll->add('value 1');
$coll->add('value 2');
$coll->add('value 3');

foreach ($coll as $key => $val)
    {
    echo "key/value: [$key -> $val]\n\n";
    }

?>
