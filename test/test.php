<?php

function e($shit)
{
    exit(var_dump($shit));
}

include '../src/avane.php';
include '../src/directives.php';
include '../src/parser.php';

$avane = new Avane();
$avane->set('hello', 'Foobar')
      ->set('world', 'Moon, dalan!')
      ->load('a.tpl.php');

?>