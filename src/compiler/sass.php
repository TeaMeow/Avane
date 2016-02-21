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
                exec($this->sassc . ' -t "compressed" ' . $this->sassPath . $path . ' > ' . $this->stylesPath . $name . '.css 2>&1', $Output, $Code);
            }
        }

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
            if(!file_exists($this->stylesPath . $name))
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
        $listResult = $this->listResult();

        /** Creates a MD5 list file when there's no MD5 list file exists */
        if(!file_exists($this->fileTrackingPath))
        {
            file_put_contents($this->fileTrackingPath, $listResult);
            return false;
        }
        /** Returns true when the MD5 list file is same as now */
        if(file_get_contents($this->fileTrackingPath) == $listResult)
        {
            return true;
        }
        /** Otherwise just updated the MD5 list */
        else
        {
            file_put_contents($this->fileTrackingPath, $listResult);

            return false;
        }
    }

    
    
    
    /**
     * List Result
     * 
     * Collect all the sass files and convert them into a MD5 string.
     * 
     * @return string
     */

    function listResult()
    {
        $list      = '';
        $directory = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->sassPath));

        foreach ($directory as $info)
        {
            $filename = $info->getFilename();

            if($filename == '.' || $filename == '..')
                continue;

            $fileMD5  = md5_file($info->getRealPath());

            $list    .= $fileMD5;
        }

        return $list;
    }
}
?>