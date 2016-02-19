<?php
class AvaneSassCompiler extends Avane
{
    function __construct($thisOne)
    {
        if($thisOne) parent::__construct($thisOne);

        $this->sassListPath     = $this->compiledPath . 'sass.json';
        $this->fileTrackingPath = $this->compiledPath . 'sass_file_tracking.txt';
    }




    function compile()
    {
        if(!$this->checkTime() || $this->hasNew())
        {
            foreach($this->sass as $name => $path)
            {
                $content = file_get_contents($this->sassPath . $path);
                $contentMD5 = md5($content);
                file_put_contents($this->compiledPath . $contentMD5 . '.sass', AvaneAvTagCompiler::outputCss($content));


                exec($this->sassc . ' ' . $this->compiledPath . $contentMD5 . '.sass' . ' > ' . $this->stylesPath . $name . '.css 2>&1', $Output, $Code);
            }
        }

        return $this;
    }


    function hasNew()
    {
        foreach($this->sass as $name => $path)
        {
            if(!file_exists($this->stylesPath . $name))
                return true;
        }
    }



    function checkTime()
    {
        $listResult = $this->listResult();

        if(!file_exists($this->fileTrackingPath))
        {
            file_put_contents($this->fileTrackingPath, $listResult);
            return false;
        }
        if(file_get_contents($this->fileTrackingPath) == $listResult)
        {
            return true;
        }
        else
        {
            file_put_contents($this->fileTrackingPath, $listResult);

            return false;
        }
    }


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






    function hasListed()
    {
        return file_exists($this->sassListPath);
    }

}
?>