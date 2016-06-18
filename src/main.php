<?php
namespace Avane;

class Main
{
    function __construct($path)
    {
        $this->templateEngine = new \Tale\Jade\Renderer();
        
        $this->setPath($path)
             ->initialize();
    }
    
    
    
    
    function setPath($path)
    {   
        $p                  = rtrim($path, '/') . '/';
        $this->mainPath     = $p;
        $this->configPath   = $p . 'config.yml';
        $this->compiledPath = $p . 'compiled/';
        $this->scriptPath   = $p . 'scripts/';
        $this->stylePath    = $p . 'styles/';
        $this->coffeePath   = $p . 'coffees/';
        $this->sassPath     = $p . 'sass/';
        $this->tplPath      = $p . 'tpls/';
        $this->tplExtension = '.jade';

        return $this;
    }
    
    function initialize()
    {
        if(!is_dir($this->compiledPath))
            mkdir($this->compiledPath, 0755, true);
        if(!is_dir($this->scriptPath))
            mkdir($this->scriptPath, 0755, true);
        if(!is_dir($this->stylePath))
            mkdir($this->stylePath, 0755, true);
        if(!is_dir($this->sassPath))
            mkdir($this->sassPath, 0755, true);
        if(!is_dir($this->tplPath))
            mkdir($this->tplPath, 0755, true);
        
        return $this;
    }
    
   
    
    public function render($templateFile, $variables = null)
    {
        echo $this->templateEngine->render($this->tplPath . $templateFile, $variables);
    }
}
?>