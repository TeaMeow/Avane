<?php
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

include 'src/autoload.php';

class Test extends PHPUnit_Framework_TestCase
{
    function __construct()
    {
        $this->avane = new \Avane\Avane(__DIR__ . '/templates', ['forceCompile' => true]);
    }




    /**
     * Test fetch().
     */

    function testFetch()
    {
        $this->avane->fetch('basic');
    }




    /**
     * Test load().
     */

    function testLoad()
    {
        $this->avane->load('basic');
    }




    /**
     * Test full load.
     */

    function testFull()
    {
        $this->avane->header()
                    ->load('basic')
                    ->footer();
    }




    /**
     * Test full load with basic tags.
     */

    function testBasicTags()
    {
        $this->avane->header()
                    ->load('if-tags', ['I_AM_FALSE' => false, 'I_AM_TRUE' => true])
                    ->footer();
    }
}

?>