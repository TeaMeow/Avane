<?php
include '../src/autoload.php';

$Avane = new Avane\Main(__DIR__ . '/default');
$Avane->render('Ello', ['items' => range(0, 1000)]);
?>