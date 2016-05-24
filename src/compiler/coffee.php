<?php
namespace Avane\Compiler;

class Coffee extends \Avane\Avane
{
    function __construct($thisOne)
    {
        if($thisOne) parent::__construct($thisOne);

        $this->coffeeListPath     = $this->compiledPath . 'coffee.json';
        $this->fileTrackingPath   = $this->compiledPath . 'coffee_file_tracking.txt';
    }




    function compile()
    {
        foreach($this->coffee as $destination => $path)
        {
            $coffees = '';

            if(is_array($path))
            {
                /** Collect all the coffee files */
                foreach($path as $single)
                    $coffees .= "\n\n" . file_get_contents($this->coffeePath . $single);

                /** Put all the coffee scripts into a single file */
                file_put_contents($this->compiledPath . md5($coffees) . '.coffee', $coffees);

                $targetFile     = $this->scriptsPath  . $destination  . '.js';
                $combinedCoffee = $this->compiledPath . md5($coffees) . '.coffee';

                /** Execute the coffee command with proc_open to catch the STDERR */
                exec("coffee -b -p $combinedCoffee > $targetFile 2>&1", $result, $status);

                /** Output the error message to the css file if needed */
                if($status === 1)
                    file_put_contents($targetFile, $this->prepareError(file_get_contents($targetFile), $combinedCoffee));
            }
        }
    }





    function check()
    {

    }




    function prepareError($stdErr, $combinedCoffee)
    {
        preg_match('/.*?:(.*?):(.*?):/', $stdErr, $matches);

        $lines = file($combinedCoffee);

        /** Get the error code */
        $errorBlock = implode("", array_slice($lines, $matches[1] - 5, 10));

        $errorLog = <<<EOF
/*
------------ COMPILE ERROR ------------

$errorBlock

------------ COMPILE ERROR ------------

$stdErr
*/

console.error('%c☕ COFFEE COMPILE ERROR\\n' +
              '%c\\n' + `$errorBlock`        +
              '\\n%c' + `$stdErr`,
              'background: red; color: #FFF; font-size: 1.5em; padding: 10px; box-sizing: border-box; line-height: 40px; border-radius: 1000em; overflow: hidden;'
            , 'color: #9E0000; font-size: 12px; box-sizing: border-box; line-height: 14px'
            , 'color: #9E0000; font-size: 12px; box-sizing: border-box; line-height: 14px; font-weight: bold');
EOF;

        return $errorLog;
    }
}




?>