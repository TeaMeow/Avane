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




    /**
     * Compile
     *
     * Compile the coffeescript to javascript.
     *
     * @return Coffee
     */

    function compile()
    {
        if(!$this->checkTime() || $this->hasNew())
        {
            foreach($this->coffee as $name => $path)
            {

                $scriptPath  = $this->scriptsPath . $name . '.js';

                if(is_array($path))
                {
                    $collects = '';

                    foreach($path as $singlePath)
                        $collects .= ' ' . $this->coffeePath . $singlePath;
                    echo 'cat ' . $collects . ' | coffee --compile --stdio > ' . $scriptPath;
                    exec('cat ' . $collects . ' | coffee --compile --stdio > ' . $scriptPath);
                }
                else
                {
                    $coffeePath  = $this->coffeePath . $path;

                    $this->coffee("coffee -b -p $coffeePath", $scriptPath);
                }
            }
        }

        return $this;
    }




    /**
     * Coffee
     *
     * Compile a coffeescript by the coffee.
     *
     * @param string $command      The command to execute.
     * @param string $outputPath   The path of the script.
     *
     * @return Coffee
     */

    function coffee($command, $outputPath)
    {
        /** Execute the coffee command with proc_open to catch the STDERR */
        $proc   = proc_open($command, [1 => ['pipe','w'], 2 => ['pipe','w']], $pipes);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);

        /** Close the pipes and the proc */
        fclose($pipes[2]);
        proc_close($proc);

        /** Output the error message to the css file if needed */
        if($stderr != '')
            file_put_contents($outputPath, $stderr);
        else
            file_put_contents($outputPath, $stdout);

        return $this;
    }




    /**
     * Has New
     *
     * Returns true when there's a coffeescript file hasn't compiled.
     *
     * @return bool
     */

    function hasNew()
    {
        foreach($this->coffee as $name => $path)
        {
            if(!file_exists($this->scriptsPath . $name . '.js'))
                return true;
        }

        return false;
    }




    /**
     * Check Time
     *
     * Returns true when the MD5 list file is same as now.
     *
     * @return bool
     */

    function checkTime()
    {
        $scannedResult = md5($this->scan());

        /** Creates a MD5 list file when there's no MD5 list file exists */
        if(!file_exists($this->fileTrackingPath))
        {
            file_put_contents($this->fileTrackingPath, $scannedResult);
            return false;
        }
        /** Returns true when the MD5 list file is same as now */
        if(file_get_contents($this->fileTrackingPath) == $scannedResult)
        {
            return true;
        }
        /** Otherwise just updated the MD5 list */
        else
        {
            file_put_contents($this->fileTrackingPath, $scannedResult);

            return false;
        }
    }




    /**
     * Scan
     *
     * Scan all the coffeescript folders.
     *
     * @return string
     */

    function scan()
    {
        $list = '';

        $list .= $this->listResult($this->coffeePath);

        if($this->coffeeTracker)
            foreach($this->coffeeTracker as $path)
                $list .= $this->listResult($path);

        return $list;
    }




    /**
     * List Result
     *
     * Collect all the coffee files and convert them into a MD5 string.
     *
     * @param string $folder   The path of the folder to scan with.
     *
     * @return string
     */

    function listResult($folder)
    {
        $list      = '';
        $directory = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder));

        foreach ($directory as $info)
        {
            $filename = $info->getFilename();

            if($filename == '.' || $filename == '..')
                continue;

            $realpath = $info->getRealPath();

            if(!$realpath)
                continue;

            $fileMD5  = md5_file($realpath);

            $list    .= $fileMD5;
        }

        return $list;
    }
}
?>