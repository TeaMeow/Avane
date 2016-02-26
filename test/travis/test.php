<?php
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

include 'src/autoload.php';

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

    function testFull()
    {
        $this->avane->header()
                    ->load('test')
                    ->footer();
    }
}

?>