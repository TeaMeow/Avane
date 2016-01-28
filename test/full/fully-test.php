<meta charset="utf-8">
<?php
ini_set('xdebug.var_display_max_depth', '10'); 
ini_set('xdebug.var_display_max_data', '5000'); 

function e($shit)
{
    exit(var_dump($shit));
}

include '../../src/lexer.php';

include '../../src/avane.php';
include '../../src/compiler/template.php';
include '../../src/parser/template.php';
include '../../src/directives.php';


$avane = new Avane('templates');



$startTime = microtime(true);  
    
    for($i = 0; $i < 10000; $i++)
        echo $avane->fetch('a');
    
    $endTime = microtime(true);  
    $elapsed = number_format($endTime - $startTime, 10);
    
    echo "測試時間 : $elapsed 秒 <br>";
    
    


?>