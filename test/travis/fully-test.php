<?php
$startTime = microtime(true);


ini_set('xdebug.var_display_max_depth', '10');
ini_set('xdebug.var_display_max_data', '5000');

function e($shit)
{
    exit(var_dump($shit));
}
include 'simple-html-dom.php';
include 'src/lexer.php';

include 'src/avane.php';
include 'src/compiler/sass.php';
include 'src/compiler/avane-tag.php';
include 'src/compiler/template.php';
include 'src/parser/template.php';
include 'src/directives.php';

$avane = new Avane('templates', ['forceCompile' => true, 'avaneTags' => true]);


$avane->header()
      ->load('test');
?>