<?php
error_reporting(E_ALL & ~E_STRICT & ~E_DEPRECATED);

include 'src/autoload.php';


class Test extends atoum
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
     * Test full load with if tags.
     */

    function testIfTags()
    {
        $this->avane->header()
                    ->load('if-tags', ['I_AM_FALSE' => false, 'I_AM_TRUE' => true])
                    ->footer();
    }




    /**
     * Test full load with if tags.
     */

    function testForeachTags()
    {
        $datas      = ['A', 'B', 'C', 'D', 'E', 'F'];
        $datas2     = ['A' => ['AA', 'AB', 'AC', 'AD', 'AE', 'AF'],
                       'B' => ['BA', 'BB', 'BC', 'BD', 'BE', 'BF'],
                       'C' => ['CA', 'CB', 'CC', 'CD', 'CE', 'CF']];
        $mixedDatas = ['A', 2, 5, 'B', '1', 7, 'C'];


        $this->avane->header()
                    ->load('foreach-tags', ['datas'      => $datas,
                                             'datas2'     => $datas2,
                                             'mixedDatas' => $mixedDatas])
                    ->footer();
    }




    /**
     * Test full load with include tags.
     */

    function testIncludeTags()
    {
        $this->avane->header()
                    ->load('include-tags')
                    ->footer();
    }




    /**
     * Test full load with block tags.
     */

    function testBlockTags()
    {
        $this->avane->header()
                    ->load('block-tags')
                    ->footer();
    }




    /**
     * Test full load with import tags.
     */

    function testImportTags()
    {
        $this->avane->header()
                    ->load('import-tags')
                    ->footer();
    }

}

?>