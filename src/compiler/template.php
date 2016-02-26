<?php
namespace Avane\Compiler;

class Template extends \Avane\Avane
{
    private $templateMD5 = '';

    function __construct($thisOne)
    {
        if($thisOne) parent::__construct($thisOne);

        $this->parser = new \Avane\Parser\Template();
    }




    /**
     * Compile
     *
     * Compile the templates to the cooked files.
     *
     * @return array   The array which brings the informations of the template.
     */

    function compile($templatePath, $raw = false)
    {
        $this->templateMD5        = md5_file($templatePath);
        $this->templateCachedPath = $this->cachedPath();

        /** Return the same informations when compiled already */
        if($this->hasCached() && !$this->forceCompile)
            return ['path'    => $this->templateCachedPath,
                    'content' => file_get_contents($this->templateCachedPath)];

        /** Otherwise we get the content of the template */
        $templateContent = file_get_contents($templatePath);

        /** Then throw it into the parser */
        $compiledContent = $this->parser->parse($templateContent);

        if($this->avaneTagsSwitch)
        {
            /** New the avane tag compiler when hasn't "new" it yet */
            if(!isset($this->avaneTagCompiler))
                $this->avaneTagCompiler = new AvaneTag($this);

            /** And parse the avane tags */
            $this->avaneTagCompiler->compile($compiledContent);
        }

        /** Save the compiled template */
        file_put_contents($this->templateCachedPath, $compiledContent);

        return ['path'    => $this->templateCachedPath,
                'content' => $compiledContent];
    }




    /**
     * Has Cached
     *
     * Check the template file has been cached or not.
     *
     * @return bool
     */

    function hasCached()
    {
        return file_exists($this->templateCachedPath);
    }




    /**
     * Cached Path
     *
     * Returns the path of a cached template.
     *
     * @return string
     */

    function cachedPath()
    {
        return $this->compiledPath . $this->templateMD5 . $this->templateExtension;
    }
}
?>