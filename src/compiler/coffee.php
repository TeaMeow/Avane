<?php
namespace Avane\Compiler;

class Coffee
{
    private $coffees         = [];
    private $coffeePath      = '';
    private $scriptPath      = '';
    private $coffeeExtension = '.coffee';
    private $compiledPath    = '';
    
    
    
    
    /**
     * Initialize the configs, and start the compilation.
     * 
     * @param  array  $coffees           The coffees to compile.
     * @param  string $coffeePath        The coffee path.
     * @param  string $scriptPath        The script path.
     * @param  string $coffeeExtension   The extension of the coffee files.
     * @param  string $compiledPath      The compiled path.
     * 
     * @return Coffee
     */
     
    public function initialize($coffees, $coffeePath, $scriptPath, $coffeeExtension, $compiledPath)
    {
        $this->coffees         = $coffees;
        $this->coffeePath      = $coffeePath;
        $this->scriptPath      = $scriptPath;
        $this->coffeeExtension = $coffeeExtension;
        $this->compiledPath    = $compiledPath;
        $this->trackerPath     = $compiledPath . 'coffee_tracker.txt';
        
        /** Return if the cache is available */
        if($this->validateCache())
            return $this;
        
        /** Compile the coffees */
        foreach($coffees as $destination => $raws)
            $this->compile($destination, $raws);
        
        /** Create a cache mark file after the compilation */
        $this->buildCache();
        
        return $this;
    }
    
    
    
    
    /**
     * Compile the coffees.
     * 
     * @param  string $destination   The name of the final compiled js.
     * @param  array  $raws          The coffees to compile.
     * 
     * @return Coffee
     */
     
    private function compile($destination, $raws)
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
        
        return $this;
    }
    
    
    
    
    /**
     * Validate the cache.
     * 
     * @return bool   Returns true when the cache is available.
     */
     
    private function validateCache()
    {
        if(!file_exists($this->trackerPath))
            return false;
            
        $this->currentMD5 = $this->getCurrentMD5();
        
        return $this->currentMD5 === file_get_contents($this->trackerPath);
    }
    
    
    
    
    /**
     * Build the cache.
     * 
     * @return Coffee
     */
     
    private function buildCache()
    {
        file_put_contents($this->trackerPath, $this->currentMD5);
        
        return $this;
    }
    
    
    
    
    /**
     * Get the MD5 of the coffees.
     * 
     * @return string   The MD5.
     */
     
    private function getCurrentMD5()
    {
        $currentMD5 = '';
        $directory = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->coffeePath));
        
        foreach ($directory as $info)
        {
            $filename = $info->getFilename();
            
            if($filename == '.' || $filename == '..')
                continue;
                
            $realpath = $info->getRealPath();
            
            if(!$realpath)
                continue;
                
            $currentMD5 .= md5_file($realpath);
        }
        
        return md5($currentMD5);
    }
    
    
    
    
    /**
     * Prepare the error message.
     * 
     * @param  string $stdErr    The content of the error.
     * @param  string $rawPath   The path of the raw combined coffee.
     * 
     * @return string            The error message.
     */
    
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