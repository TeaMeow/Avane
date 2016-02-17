<?php
class AvaneSassCompiler extends Avane
{
    function __construct($thisOne)
    {
        if($thisOne) parent::__construct($thisOne);
        
        $this->sassListPath     = $this->compiledPath . 'sass.json';
        $this->modifiedTimePath = $this->compiledPath . 'sass_modified_time.txt';
    }
    
    
    
    
    function compile()
    {
        if(!$this->checkTime())
        {
            exec('sass ' . $this->SCSSPath . $this->TemplateName . '.scss' . ' --load-path ' . $this->SCSSPath . ' 2>&1', $Output, $Code);
        }
        
        return $this;
    }
    
    
    function checkTime()
    {
        if(!file_exists($this->modifiedTimePath))
        {
            file_put_contents(filemtime($this->sassPath), $this->modifiedTimePath);
        
            return false;
        }
        
        return file_get_contents($this->modifiedTimePath) == filemtime($this->sassPath);
    }
    
    
    function listAndSave()
    {
        $list      = [];
        $directory = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->sassPath));
        
        foreach ($directory as $info)
        {
            $filename = $info->getFilename();
            $fileMD5  = md5_file($info->getRealPath());
            
            $list[]   = $FileMD5;
        }

        /** Remove unnecessary */
        unset($list['.']);
        unset($list['..']);
    }
    
    
    
    
    
    
    function hasListed()
    {
        return file_exists($this->sassListPath);
    }
    
} 
?>