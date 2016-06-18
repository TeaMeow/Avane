<?php
namespace Avane\Compiler;

class Coffee
{
    private $coffees         = [];
    private $coffeePath      = '';
    private $scriptPath      = '';
    private $coffeeExtension = '.coffee';
    private $compiledPath    = '';
    
    public function initialize($coffees, $coffeePath, $scriptPath, $coffeeExtension, $compiledPath)
    {
        $this->coffees         = $coffees;
        $this->coffeePath      = $coffeePath;
        $this->scriptPath      = $scriptPath;
        $this->coffeeExtension = $coffeeExtension;
        $this->compiledPath    = $compiledPath;
        

        foreach($coffees as $destination => $raws)
            $this->compile($destination, $raws);
        
        return $this;
    }
    
    
    public function compile($destination, $raws)
    {
        $cookedName = $destination;
        $raw        = '';
        $cooked     = '';

        /** Collect all the coffees */
        foreach($raws as &$coffee)
           $raw .= "\n\n" . file_get_contents($this->coffeePath . $coffee . $this->coffeeExtension);
        
        /** Prepare the paths */
        $destination = $this->scriptPath . $cookedName . '.js';
        $rawPath     = $this->compiledPath . md5($raw) . $this->coffeeExtension;
        
        /** Store the raw file */
        file_put_contents($rawPath, $raw);
        
        /** Execute the coffee command */
        exec("coffee -b -p $rawPath > $destination 2>&1", $result, $status);
        
        /** Output the error message to the css file if needed */
        if($status === 1)
            file_put_contents($destination, $this->prepareError(file_get_contents($destination), $rawPath));
    }
    
    
    private function prepareError($stdErr, $rawPath)
    {
        preg_match('/.*?:(.*?):(.*?):/', $stdErr, $matches);
        $lines = file($rawPath);
        
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