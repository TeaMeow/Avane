<?php
class AvaneTest extends \PHPUnit_Framework_TestCase
{
    function testRender()
    {
        $Avane = new Avane\Main(__DIR__ . '/template');

        $this->assertEquals($Avane->fetch('test'), '<div>Hello, World!</div>');
    }

    function testPjax()
    {
        $Avane = new Avane\Main(__DIR__ . '/template');

        /** Simulate the PJAX header content */
        $Avane->isPJAX = 'title, html, content, footer';

        ob_start();

        $Avane->header('header')
              ->render('test')
              ->footer('footer');

        $returned = ob_get_clean();

        $this->assertEquals($returned, '{"title":null,"html":"<html><head><\/head><body><div>Hello, World!<\/div><\/body><\/html>","content":"<div>Hello, World!<\/div>","footer":"<\/body><\/html>"}');
    }

    function testFullyPjax()
    {
        $Avane = new Avane\Main(__DIR__ . '/template');

        /** Simulate the PJAX header content */
        $Avane->isPJAX = 'title, html, content, footer, wasted';

        $Avane->header('header')
              ->render('test')
              ->footer('footer');
    }

    function testCoffee()
    {
        $Avane = new Avane\Main(__DIR__ . '/template_coffee');

        $this->assertEquals($Avane->fetch('test'), '<div>Hello, World!</div>');

        echo file_get_content(__DIR__ . '/template_coffee/scripts/a.js');
        echo file_get_content(__DIR__ . '/template_coffee/scripts/c.js')
    }

    function testCoffeeError()
    {
        $Avane = new Avane\Main(__DIR__ . '/template_coffeeError');

        $this->assertEquals($Avane->fetch('test'), '<div>Hello, World!</div>');
    }

    function testRubySass()
    {
        $Avane = new Avane\Main(__DIR__ . '/template_sass');

        $this->assertEquals($Avane->fetch('test'), '<div>Hello, World!</div>');
    }

    function testSassC()
    {
        $Avane = new Avane\Main(__DIR__ . '/template_sassc');

        $this->assertEquals($Avane->fetch('test'), '<div>Hello, World!</div>');
    }

    function testSassTracker()
    {
        $Avane = new Avane\Main(__DIR__ . '/template_sassTracker');

        $this->assertEquals($Avane->fetch('test'), '<div>Hello, World!</div>');
    }
}
?>