<?php
$this->import('test', 'test.css', true)
     ->import('test', 'test.js', true)
     ->import('test', '//ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js');

$this->sassSet('music', 'music.sass')
     ->sassSet('musics', 'music.sass');
?>