<?php
include '../src/autoload.php';

$Avane = new Avane\Main(__DIR__ . '/default');
$Avane->header('Ello', ['title' => 'dsfsdfsdfsd'])
      ->render('Ello', ['items' => range(0, 1000)])
      ->footer('Ello');
?>