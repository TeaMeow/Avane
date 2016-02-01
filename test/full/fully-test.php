<?php
$startTime = microtime(true);  
    
    
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

include '../../vendor/autoload.php';
//$_SERVER['HTTP_X_PJAX'] = 'title, content, header, wasted';
use PHPHtmlParser\Dom;
$avane = new Avane('templates');


$A = $avane->fetch('test');

$dom = new Dom;
$dom->load('<div class="all"><p>Hey bro, <a>asdasd</a> <a class="donzo" href="google.com">click here</a><br /> :)</p></div>');
$a   = $dom->find('a');

foreach($a as $single)
{
    echo $single->getAttribute('class');
}

e(count($a));
?>