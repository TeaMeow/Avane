<?php
class AvaneSassCompiler extends Avane
{
    function __construct($thisOne)
    {
        if($thisOne) parent::__construct($thisOne);

        $this->sassListPath     = $this->compiledPath . 'sass.json';
        $this->fileTrackingPath = $this->compiledPath . 'sass_file_tracking.txt';
    }




    /**
     * Compile
     *
     * Compile the sass to css.
     *
     * @return AvaneSassCompiler
     */

    function compile()
    {
        if(!$this->checkTime() || $this->hasNew())
        {
            foreach($this->sass as $name => $path)
            {
                $sassPath = $this->sassPath . $path;
                $cssPath  = $this->stylesPath . $name . '.css';

                if($this->forceRubySass)
                    $this->sassCompile('sass ' . $sassPath . ' --load-path ' . $this->sassPath . ' 2>&1', $cssPath);
                else
                    $this->sasscCompile($this->sassc . ' -t "compressed" ' . $sassPath . ' > ' . $cssPath, $cssPath);
            }
        }

        return $this;
    }




    /**
     * Sass Compile
     *
     * Compile a sass by the ruby sass.
     *
     * @param string $command      The command to execute.
     * @param string $outputPath   The path of the css.
     *
     * @return AvaneSassCompiler
     */

    function sassCompile($command, $outputPath)
    {
        exec($command, $output, $code);

        /** Convert the output to the string */
        $output = implode("\n", $output);

        file_put_contents($outputPath, $output);

        return $this;
    }




    /**
     * SassC Compile
     *
     * Compile a sass by the sassC.
     *
     * @param string $command      The command to execute.
     * @param string $outputPath   The path of the css.
     *
     * @return AvaneSassCompiler
     */

    function sasscCompile($command, $outputPath)
    {
        /** Execute the sassC command with proc_open to catch the STDERR */
        $proc   = proc_open($command, [2 => ['pipe','w']], $pipes);
        $stderr = stream_get_contents($pipes[2]);

        /** Close the pipes and the proc */
        fclose($pipes[2]);
        proc_close($proc);

        /** Output the error message to the css file if needed */
        if($stderr != '')
            file_put_contents($outputPath, $stderr);

        return $this;
    }




    /**
     * Has New
     *
     * Returns true when there's a sass file hasn't compiled.
     *
     * @return bool
     */

    function hasNew()
    {
        foreach($this->sass as $name => $path)
        {
            if(!file_exists($this->stylesPath . $name . '.css'))
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
     * Scan all the sass folders.
     *
     * @return string
     */

    function scan()
    {
        $list = '';

        $list .= $this->listResult($this->sassPath);

        if($this->sassTracker)
            foreach($this->sassTracker as $path)
                $list .= $this->listResult($path);

        return $list;
    }




    /**
     * List Result
     *
     * Collect all the sass files and convert them into a MD5 string.
     *
     * @param string $folder   The path of the folder to scan with.
     *
     * @return string
     */

    function listResult($folder)
    {
        $list      = '';
        $directory = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder));

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