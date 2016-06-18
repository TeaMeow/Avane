<?php
namespace Avane;

class Main
{
    function __construct($path)
    {
        $this->templateEngine = new \Tale\Jade\Renderer();
        
        $path           = rtrim($path, '/') . '/';
        $this->mainPath = $path;
        
        $this->initialize()
             ->compileSass()
             ->compileCoffee();
    }
    
    function setSetting($name, $value)
    {   
        switch($name)
        {
            case 'compiled'       : $this->compiledPath    = $value; break;
            case 'script'         : $this->scriptPath      = $value; break;
            case 'style'          : $this->stylePath       = $value; break;
            case 'coffee'         : $this->coffeePath      = $value; break;
            case 'sass'           : $this->sassPath        = $value; break;
            case 'tpl'            : $this->tplPath         = $value; break;
            case 'extension'      : $this->tplExtension    = $value; break;
            case 'config'         : $this->configPath      = $value; break;
            case 'enableCoffee'   : $this->enableCoffee    = $value; break;
            case 'enableSass'     : $this->enableSass      = $value; break;
            case 'enableSassc'    : $this->enableSassc     = $value; break;
            case 'sasscPath'      : $this->sasscPath       = $value; break;
            case 'coffeeExtension': $this->coffeeExtension = $value; break;
            case 'sassExtension'  : $this->sassExtension   = $value; break;
        }

        return $this;
    }
    
    
    
    function initialize()
    {
        
        $this->setSetting('compiled'       , $this->mainPath . 'compiled/')
             ->setSetting('script'         , $this->mainPath . 'scripts/')
             ->setSetting('style'          , $this->mainPath . 'styles/')
             ->setSetting('coffee'         , $this->mainPath . 'coffees/')
             ->setSetting('sass'           , $this->mainPath . 'sass/')
             ->setSetting('tpl'            , $this->mainPath . 'tpls/')
             ->setSetting('config'         , $this->mainPath . 'config.yml')
             ->setSetting('coffeeExtension', '.coffee')
             ->setSetting('sassExtension'  , '.sass')
             ->setSetting('extension'      , '.jade');
        
        $this->config = yaml_parse(file_get_contents($this->configPath));
        
        if(isset($this->config['paths']))
            foreach($this->config['paths'] as $name => $path)
                $this->setSetting($name, $path);
        
        if(isset($this->config['configs']))
            foreach($this->config['configs'] as $name => $value)
                $this->setSetting($name, $value);
   
        if(!is_dir($this->compiledPath))
            mkdir($this->compiledPath, 0755, true);
        if(!is_dir($this->scriptPath))
            mkdir($this->scriptPath, 0755, true);
        if(!is_dir($this->stylePath))
            mkdir($this->stylePath, 0755, true);
        if(!is_dir($this->coffeePath))
            mkdir($this->coffeePath, 0755, true);
        if(!is_dir($this->sassPath))
            mkdir($this->sassPath, 0755, true);
        if(!is_dir($this->tplPath))
            mkdir($this->tplPath, 0755, true);
        
        return $this;
    }
    
    
    public function compileCoffee()
    {
        if(!$this->enableCoffee)
            return $this;
        
        $coffees = isset($this->config['coffees']) ? $this->config['coffees'] 
                                                   : null;
        
        if(!$coffees)
            return $this;
        
        $Coffee = new Compiler\Coffee();
        $Coffee->initialize($coffees, 
                            $this->coffeePath, 
                            $this->scriptPath,
                            $this->coffeeExtension,
                            $this->compiledPath);
        
        return $this;
    }
    
    public function compileSass()
    {
        if(!$this->enableSass && !$this->enableSassc)
            return $this;
        
        $sass = isset($this->config['sass']) ? $this->config['sass'] 
                                             : null;
        
        if(!$sass)
            return $this;
        
        $sassTracker = isset($this->config['sassTracker']) ? $this->config['sassTracker'] 
                                                           : [];
        
        $Sass = new Compiler\Sass();
        $Sass->initialize($sass,
                          $sassTracker,
                          $this->enableSassc,
                          $this->sassPath,
                          $this->stylePath,
                          $this->sassExtension,
                          $this->sasscPath,
                          $this->compiledPath);
        
        return $this;
    }
    
   
    
    public function render($templateFile, $variables = null)
    {
        echo $this->fetch($templateFile, $variables);
    }
    
    public function fetch($templateFile, $variables = null)
    {
        return $this->templateEngine->render($this->tplPath . $this->getShortnames($templateFile) . $this->tplExtension, $variables);
    }
    
    public function getShortnames($templateFile)
    {
        return isset($this->config['shortnames'][$templateFile]) ? $this->config['shortnames'][$templateFile]
                                                                 : $templateFile;
    }
}
?>