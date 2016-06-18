<?php
include '../src/autoload.php';

$Avane = new Avane\Main(__DIR__ . '/default');
$Avane->render('test.jade');
?>