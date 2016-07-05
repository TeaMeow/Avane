<?php
class AvaneTest extends \PHPUnit_Framework_TestCase
{
    function testRender()
    {
        $Avane = new Avane\Main(__DIR__ . '/default');

        $Avane->render('test');
    }

    function testPjax()
    {
        $Avane = new Avane\Main(__DIR__ . '/default');

        $Avane->header('header')
              ->render('test')
              ->footer('footer');
    }
}
?>