<?php
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

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

class Test extends PHPUnit_Framework_TestCase
{
    function __construct()
    {
        $this->avane = new \Avane\Avane(__DIR__ . '/templates', ['forceCompile' => true, 'avaneTags' => true]);
    }

    function testFetch()
    {
        $this->avane->fetch('test');
    }

    function testLoad()
    {
        $this->avane->load('test');
    }
}

?>