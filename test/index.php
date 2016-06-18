<?php
include '../src/autoload.php';

$Avane = new Avane\Main(__DIR__ . '/default');
$Avane->render('Ello', ['items' => [1, 2, 3, 4, 5, 6]]);
?>