<?php
namespace Avane\Compiler;

class Sass
{
    /**
     * The array which stores the path of the sass files.
     *
     * @var array
     */

    private $sass          = [];

    /**
     * The array which stores the path of the extra sass folders.
     *
     * @var array
     */

    private $sassTracker   = [];

    /**
     * Set true to use sassc instead of ruby sass.
     *
     * @var bool
     */

    private $isSassc       = false;

    /**
     * The path of the sass folder.
     *
     * @var string
     */

    private $sassPath      = '';

    /**
     * The path of the style folder.
     *
     * @var string
     */

    private $stylePath     = '';

    /**
     * The extension of the sass file.
     *
     * @var string
     */

    private $sassExtension = '.sass';

    /**
     * The path to the sassc.
     *
     * @var string
     */

    private $sasscPath     = 'sassc';

    /**
     * The path of the compiled folder.
     *
     * @var string
     */

    private $compiledPath  = '';




    /**
     * Initialize the configs, and start the compilation.
     *
     * @param array  $sass            The path of sass to compile.
     * @param array  $sassTracker     The extra track paths.
     * @param bool   $isSassc         Set true to use sassc instead of ruby sass.
     * @param string $sassPath        The path of the sass files.
     * @param string $stylePath       The path of the styles.
     * @param string $sassExtension   The extension of the sass.
     * @param string $sasscPath       The path to the sassc.
     * @param string $compiledPath    The compiled path.
     *
     * @return Sass
     */

    public function initialize($sass, $sassTracker, $isSassc, $sassPath, $stylePath, $sassExtension, $sasscPath, $compiledPath)
    {
        $this->sass          = $sass;
        $this->sassTracker   = $sassTracker;
        $this->isSassc       = $isSassc;
        $this->sassPath      = $sassPath;
        $this->stylePath     = $stylePath;
        $this->sassExtension = $sassExtension;
        $this->sasscPath     = $sasscPath;
        $this->compiledPath  = $compiledPath;
        $this->trackerPath   = $compiledPath . 'sass_tracker.txt';

        /** Return if the cache is available */
        $this->currentMD5 = $this->getCurrentMD5();

        if($this->validateCache())
            return $this;

        /** Compile the coffees */
        foreach($sass as $destination => $raws)
            $this->compile($destination, $raws);

        /** Create a cache mark file after the compilation */
        $this->buildCache();

        return $this;
    }




    /**
     * Compile the sass.
     *
     * @param  string $destination   The name of the final compiled css.
     * @param  array  $raws          The sass to compile.
     *
     * @return Sass
     */

    private function compile($destination, $raws)
    {
        $cookedName = $destination;
        $raw        = '';
        $cooked     = '';

        /** Collect all the sass */
        foreach($raws as &$sass)
           $raw .= "\n\n" . file_get_contents($this->sassPath . $sass . $this->sassExtension);

        /** Prepare the paths */
        $destination = $this->stylePath . $cookedName . '.css';
        $rawPath     = $this->compiledPath . md5($raw) . $this->sassExtension;

        /** Store the raw file */
        file_put_contents($rawPath, $raw);

        /** Execute the coffee command */
        if($this->isSassc)
            $this->sassc($this->sasscPath . ' -t "compressed" ' . $rawPath . ' > ' . $destination, $destination);
        else
            $this->rubySass('sass ' . $rawPath . ' --load-path ' . $this->sassPath . ' 2>&1', $destination);

        return $this;
    }




    /**
     * Compile a sass using ruby sass.
     *
     * @param string $command      The command to execute.
     * @param string $outputPath   The path of the css.
     *
     * @return Sass
     */

    private function rubySass($command, $outputPath)
    {
        exec($command, $output, $code);

        /** Convert the output to the string */
        $output = implode("\n", $output);

        file_put_contents($outputPath, $output);

        return $this;
    }




    /**
     * Compile a sass using sassC.
     *
     * @param string $command      The command to execute.
     * @param string $outputPath   The path of the css.
     *
     * @return Sass
     */

    private function sassc($command, $outputPath)
    {
        /** Execute the sassC command with proc_open to catch the STDERR */
        $proc   = proc_open($command, [2 => ['pipe','w']], $pipes);
        $stdErr = stream_get_contents($pipes[2]);

        /** Close the pipes and the proc */
        fclose($pipes[2]);
        proc_close($proc);

        /** Output the error message to the css file if needed */
        if($stdErr != '')
            file_put_contents($outputPath, $stdErr);

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
        $paths      = $this->sassTracker;

        $paths[]    = $this->sassPath;

        foreach($paths as $folder)
        {
            $directory  = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder));

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
        }

        return md5($currentMD5);
    }

}
?>