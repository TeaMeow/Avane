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
    
    
    
    
    /**
     * Set the settings.
     * 
     * @param string $name    The name of the setting.
     * @param mixed  $value   The value of the setting.
     * 
     * @return Main
     */
     
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
    
    
    
    
    /**
     * Initialize Avane.
     * 
     * @return Main
     */
    
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
        
        /** Load the configures and store to the variable */
        $this->config = yaml_parse(file_get_contents($this->configPath));
        
        /** Apply the path settings */
        if(isset($this->config['paths']))
            foreach($this->config['paths'] as $name => $path)
                $this->setSetting($name, $path);
        
        /** Apply the common configures */
        if(isset($this->config['configs']))
            foreach($this->config['configs'] as $name => $value)
                $this->setSetting($name, $value);
   
        /** Create the folders if do not exist */
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
    
    
    
    
    /**
     * Compile the coffees.
     * 
     * @return Main
     */
    
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
    
    
    
    
    /**
     * Compile the sass.
     * 
     * @return Main
     */
     
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
    
    
    
    
    /**
     * Render the template files and output.
     * 
     * @param string $templateFile   The path to the template file, can be a shortname when setted a shortname.
     * @param array  $variables      The variables.
     * 
     * @return Main
     */
    
    public function render($templateFile, $variables = null)
    {
        echo $this->fetch($templateFile, $variables);
        
        return $this;
    }
    
    
    
    
    /**
     * Return the rendered content instead of output it.
     * 
     * @param  string $templateFile   The path to the template file, can be a shortname when setted a shortname.
     * @param  array  $variables      The variables.
     * 
     * @return string                 The rendered content
     */
     
    public function fetch($templateFile, $variables = null)
    {
        return $this->templateEngine->render($this->tplPath . $this->getShortnames($templateFile) . $this->tplExtension, $variables);
    }
    
    
    
    
    /**
     * Get the path of the shortname,
     * returns the original path if there's no shortname for the path.
     * 
     * @param  string $templateFile   Colud be the shortname or the full path of the template file.
     * 
     * @return string                 Returns the full path.
     */
     
    public function getShortnames($templateFile)
    {
        return isset($this->config['shortnames'][$templateFile]) ? $this->config['shortnames'][$templateFile]
                                                                 : $templateFile;
    }
}
?>