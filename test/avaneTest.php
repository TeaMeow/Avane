<?php
class AvaneTest extends \PHPUnit_Framework_TestCase
{
    function testRender()
    {
        $Avane = new Avane\Main(__DIR__ . '/template');

        $Avane->render('test');
    }

    function testPjax()
    {
        $Avane = new Avane\Main(__DIR__ . '/template');

        /** Simulate the PJAX header content */
        $Avane->isPJAX = ''title, html, content, footer, wasted';

        $Avane->header('header')
              ->render('test')
              ->footer('footer');
    }

    function testCoffee()
    {
        $Avane = new Avane\Main(__DIR__ . '/template_coffee');

        $Avane->render('test');
    }

    function testRubySass()
    {
        $Avane = new Avane\Main(__DIR__ . '/template_sass');

        $Avane->render('test');
    }

    function testSassC()
    {
        $Avane = new Avane\Main(__DIR__ . '/template_sassc');

        $Avane->render('test');
    }

    function testSassTracker()
    {
        $Avane = new Avane\Main(__DIR__ . '/template_sassTracker');

        $Avane->render('test');
    }
}
?>