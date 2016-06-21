<?php
$start =  microtime(true);
include '../src/autoload.php';


$Avane = new Avane\Main(__DIR__ . '/default');


$A = ['A', 'B', 'C', 'D'];


for($i = 0; $i < 100; $i++)
    $Avane->render('Ello');
    //include 'cache/views/home/ubuntu/workspace/test/default/tpls/test.phtml';
    
$end = microtime(true) - $start;

echo "END: $end"; 
?>