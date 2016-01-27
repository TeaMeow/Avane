<?php
ini_set('xdebug.var_display_max_depth', '10'); 
function e($shit)
{
    exit(var_dump($shit));
}

include '../src/lexer.php';
include '../src/avane.php';
include '../src/directives.php';
include '../src/parser.php';

$avane = new Avane();
$avane->set('hello', 'Foobar')
      ->set('world', 'Moon, dalan!')
      ->load('a.tpl.php');

?>