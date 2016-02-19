<?php
class AvaneTemplateCompiler extends Avane
{
    private $templateMD5 = '';

    function __construct($thisOne)
    {
        if($thisOne) parent::__construct($thisOne);

        $this->parser = new AvaneTemplateParser();
    }



    function compile($templatePath, $raw = false)
    {
        $this->templateMD5        = md5_file($templatePath);
        $this->templateCachedPath = $this->cachedPath();

        if($this->hasCached() && !$this->forceCompile)
            return ['path'    => $this->templateCachedPath,
                    'content' => file_get_contents($this->templateCachedPath)];


        $templateContent = file_get_contents($templatePath);


        $compiledContent = $this->parser->parse($templateContent);

        $this->avaneTagCompiler = new AvaneAvTagCompiler($this);

        $this->avaneTagCompiler->compile($compiledContent);


        file_put_contents($this->templateCachedPath, $compiledContent);

        return ['path'    => $this->templateCachedPath,
                'content' => $compiledContent];
    }






    function hasCached()
    {
        return file_exists($this->templateCachedPath);
    }

    function cachedPath()
    {
        return $this->compiledPath . $this->templateMD5 . $this->templateExtension;
    }
}
?>