<?php
namespace Avane;

class Main
{
    private $outputBuffer = [];
    
    function __construct($path)
    {
        $this->startTime = microtime(true);
        $this->templateEngine = new \Tale\Jade\Renderer();
        
        $path           = rtrim($path, '/') . '/';
        $this->mainPath = $path;
        
        $this->initialize()
             ->compileSass()
             ->compileCoffee();
        
        if(is_array(getallheaders()))
                $this->isPJAX = array_key_exists(strtolower($this->pjaxHeader), getallheaders()) ? getallheaders()[strtolower($this->pjaxHeader)]
                                                                                                 : false;
            else
                $this->isPJAX = false;
    }
    
    
    
    
    /**
     * Set the settings.
     * 
     * @param string $name    The name of the setting.
     * @param mixed  $value   The value of the setting.
     * 
     * @return Main
     */
     
    public function setSetting($name, $value)
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
            case 'sassc'          : $this->sasscPath       = $value; break;
            case 'coffeeExtension': $this->coffeeExtension = $value; break;
            case 'sassExtension'  : $this->sassExtension   = $value; break;
            case 'pjaxHeader'     : $this->pjaxHeader      = $value; break;
            case 'titleVariable'  : $this->titleVariable   = $value; break;
        }

        return $this;
    }
    
    
    
    
    /**
     * Initialize Avane.
     * 
     * @return Main
     */
    
    private function initialize()
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
             ->setSetting('extension'      , '.jade')
             ->setSetting('titleVariable'  , 'title')
             ->setSetting('pjaxHeader'     , 'HTTP_X_PJAX');
        
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
    
    
    
    
    //***********************************************
    //***********************************************
    //*************** C O M P I L E R ***************
    //***********************************************
    //***********************************************
    
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
    
    
    
    
    //***********************************************
    //***********************************************
    //**************** C A P T U R E ****************
    //***********************************************
    //***********************************************

    /**
     * Capture the rendered content after this function.
     *
     * @param bool $force   Force capture.
     *
     * @return Main
     */
     
    public function capture($force = false)
    {
        if(!$this->isPJAX && !$force)
            return $this;
            
        ob_start();
        
        return $this;
    }
    
    
    
    
    /**
     * Stop capturing, and store the captured content to the specified array.
     * 
     * @param  string $position   The place to store the captured content.
     * 
     * @return Main
     */
     
    public function endCapture($position = null)
    {
        if(!$this->isPJAX && $position)
            return $this;
            
        switch($position)
        {
            case 'header':
                $this->outputBuffer['header']  = ob_get_clean();
                break;
            case 'content':
                $this->outputBuffer['content'] = ob_get_clean();
                break;
            case 'footer':
                $this->outputBuffer['footer']  = ob_get_clean();
                break;
            case null:
                return ob_get_clean();
                break;
        }
        
        return $this;
    }
    
    
  
  
    //***********************************************
    //***********************************************
    //****************** P J A X ********************
    //***********************************************
    //***********************************************
    
    /**
     * 
     */
     
    public function header($templateFile, $variables = null)
    {
        /** Set the json header if it's a PJAX request */
        if($this->isPJAX)
            header('Content-Type: application/json; charset=utf-8');

        /** Set the title */
        $this->title = isset($variables[$this->titleVariable]) ? $variables[$this->titleVariable]
                                                               : null;
        
        /** Capture the rendered content from now on */
        $this->capture()
             /** Load the header and require it */
             ->render($templateFile, $variables)
             /** Stop capturing, and store the captured content to the output buffer array */
             ->endCapture('header')
             /** Start another capture action for the content part */
             ->capture();
             
        return $this;
    }
    
    /**
     * 
     * 
     */
     
    public function footer($templateFile, $variables = null)
    {
        /** Stop capturing, what we got here is a content-only part, store it either */
        $this->endCapture('content')
             /** Now capture the footer part */
             ->capture()
             /** Require the footer template */
             ->render($templateFile, $variables)
             /** And stop capturing, you know what's next right? */
             ->endCapture('footer');
             
        $this->endTime   = microtime(true);
        $this->totalTime = $this->endTime - $this->startTime;
        
        /** Return the rendered content if it's a PJAX request */
        if($this->isPJAX)
            echo json_encode($this->returnPJAX());

        return $this;
    }
    
    
    
    
    /**
     * Combine the different informations based on the PJAX header content.
     *
     * @return array   The datas.
     */
     
    function returnPJAX()
    {
        if(!$this->isPJAX)
            return false;
            
        $types = explode(', ', $this->isPJAX);
        $data  = [];
        
        if(strpos($this->isPJAX, 'title') !== false)
            $data['title'] = $this->title;
            
        if(strpos($this->isPJAX, 'html') !== false)
            $data['html'] = $this->outputBuffer['header']  .
                            $this->outputBuffer['content'] .
                            $this->outputBuffer['footer'];
                            
        if(strpos($this->isPJAX, 'header') !== false)
            $data['header'] = $this->outputBuffer['header'];
            
        if(strpos($this->isPJAX, 'content') !== false)
            $data['content'] = $this->outputBuffer['content'];

        if(strpos($this->isPJAX, 'footer') !== false)
            $data['footer'] = $this->outputBuffer['footer'];
            
        if(strpos($this->isPJAX, 'wasted') !== false)
            $data['wasted'] = $this->totalTime;
            
        return $data;
    }
    
    
    
    
    //***********************************************
    //***********************************************
    //****************** B A S I C ******************
    //***********************************************
    //***********************************************
    
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
    
    
    
    
    //***********************************************
    //***********************************************
    //**************** H E L P E R S ****************
    //***********************************************
    //***********************************************
    
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