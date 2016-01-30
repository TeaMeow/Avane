<?php
class Avane
{
    /**
     * Valut
     * 
     * Used to store the variables.
     * 
     * @var array
     */
     
    protected $vault = [];
    
    /**
     * Cache Bucket
     * 
     * We don't have to compile the same template again,
     * we can store the path of the compiled template into the bucket, and require the template directly.
     * 
     * @var array
     */
    
    protected $cacheBucket = [];
    
    /**
     * Categories Path
     * 
     * The path to the folder which stores many different categories.
     * 
     * @var string
     */
    
    public $categoriesPath = '';
    
    /**
     * Category Name
     * 
     * The name of the category which we selected.
     * 
     * @var string
     */
    
    public $categoryName = '';
    
    /**
     * PJAX Header
     * 
     * The name of the header which used to detect the PJAX request.
     * 
     * @var string
     */
    
    public $pjaxHeader = 'HTTP_X_PJAX';
    
    /**
     * Loop
     * 
     * Used to store the loop informations.
     * 
     * @var array
     */
    
    protected $loop = [];
    
    /**
     * Title
     * 
     * The title of the page.
     * 
     * @var string
     */
     
    protected $title = '';
    
    /**
     * Content Buffer
     * 
     * Used to stored the rendered content which without the header and the footer.
     * 
     * @var string
     */
    
    protected $contentBuffer = '';
    
    protected $isPJAX = false;
    
    
    /**
     * CONSTRUCT
     */
     
    function __construct($thisOne = null)
    {
            
        if($thisOne && !is_string($thisOne)) 
        {
            foreach (get_object_vars($thisOne) as $key => $value)
                $this->$key = $value;
        }
        else
        {
            $this->categoriesPath = $thisOne;
            $this->isPJAX         = array_key_exists($this->pjaxHeader, $_SERVER) ? $_SERVER[$this->pjaxHeader] : false;
            $this->setCategory();
        }
    }
    
    
    
    
    /**
     * Set Category
     * 
     * Tell us which folder you want us to load the templates from it.
     * 
     * templates
     *  └── default          // 模板名稱
     *      ├── compiled     // 編譯後的模板
     *      ├── scripts      // 放置 JavaScript 腳本
     *      ├── styles       // 放置 CSS 樣式表
     *      ├── tpls         // 各式各樣的模板放在這
     *      │
     *      ├── footer.php   // 頁腳
     *      ├── header.php   // 標頭
     *      └── variable.php // 此模板的變數設置
     *
     * @param string $categoryName
     * 
     * @return Avane
     */
    
    function setCategory($categoryName='default')
    {
        $this->categoryName       = $categoryName;
        $this->categoriesPath     = rtrim($this->categoriesPath, '/') . '/';
        $this->categoryPath       = $this->categoriesPath     . $categoryName . '/';
        $this->compiledPath       = $this->categoryPath       . 'compiled/';
        $this->scriptsPath        = $this->scriptPath         . 'scripts/';
        $this->stylesPath         = $this->categoryPath       . 'styles/';
        $this->templateFolderPath = $this->categoryPath       . 'tpls/';
        $this->headerPath         = $this->templateFolderPath . 'header' . $this->templateExtension;
        $this->footerPath         = $this->templateFolderPath . 'footer' . $this->templateExtension;
        $this->configPath         = $this->categoryPath       . 'config.php';
        $this->templateExtension  = '.tpl.php';
        
        return $this;
    }
    
    
    
    
    /**
     * Load
     * 
     * Load and require the template.
     * 
     * @param string $templateName   The name of the template (without the extension).
     * @param array  $variables      The variables.
     * 
     * @return Avane
     */
    
    function load($templateName, $variables = null)
    {
        /** Compile the template If the cache bucket doesn't have the template */
        if(!isset($this->cacheBucket[$templateName]))
        {
            $templatePath = $this->getTemplatePath($templateName);
            
            $this->setBucket($templateName, $this->templateCompile($templatePath));
        }
        
        /** Require the template from the cache bucket, so we don't need to compile it to get the path again */
        require($this->cacheBucket[$templateName]['path']);
        
        return $this;
    }
    
    
    
    
    /**
     * Set Bucket
     * 
     * Put the compiled result to the cache bucket.
     * 
     * @param string $templateName   The name of the template that we are going to store with.
     * @param string $path           The path of the compiled template.
     * 
     * @return Avane
     */
     
    function setBucket($templateName, $path)
    {
        $this->cacheBucket[$templateName] = $path;
        
        return $this;
    }
    
    

    
    /**
     * Fetch
     * 
     * Same as the load(), the only different is that fetch() returns the rendered content instead of output it.
     * 
     * @param string $templateName   The name of the template (without the extension).
     * @param array  $variables      The variables.
     * 
     * @return Avane
     */
    
    function fetch($templateName, $variables = null)
    {
        $this->capture();
        $this->load($templateName, $variables);
        $this->endCapture();
    }
        
    
    
    
    /**
     * Header
     * 
     * The header of the page, and we do something here also.
     * 
     * @param 
     */
     
    function header($title = '', $variables = null)
    {
        /** Set the title */
        $this->title = $title;
        
        /** And set the title as the avane variable */
        $this->set('title', $title);
        
        /** Load the configs of the category */
        @include_once($this->configPath);
        
        /** Set variables **/
        $this->groupSet($variables);
        
        /** Set the json header if it's a PJAX request, otherwise load the header template */
        if($this->isPJAX())
            header('Content-Type: application/json; charset=utf-8');

        /** Capture the rendered content from now on */
        $this->capture()
             /** Load the header and require it */
             ->load('header')
             /** Stop capturing, and store the captured content to the output buffer array */
             ->endCapture('header')
             /** Start another capture action for the content part */
             ->capture();
        
        return $this;
    }
    
    
    
    
    /**
     * Footer
     *
     * @return Avane
     */
     
    function footer()
    {
        /** Stop capturing, what we got here is a content-only part, store it either */
        $this->endCapture('content')
             /** Now capture the footer part */
             ->capture()
             /** Require the footer template */
             ->load('footer')
             /** And stop capturing, you know what's next right? */
             ->endCapture('footer');
        
        /** Return the rendered content if it's a PJAX request */
        if($this->isPJAX)
            echo json_encode($this->returnPJAX());

        //flush();
        
        return $this;
    }
    
    
    
    
    
    
    
    /**
     * Get Template Path
     * 
     * Input a template name, output the whole path to the template.
     * 
     * @param string $templateName   The name of the template.
     * 
     * @return Avane
     */
    
    function getTemplatePath($templateName)
    {
        return $this->templateFolderPath . $templateName . $this->templateExtension;
    }
    
    
    
    
    /**
     * Compile
     * 
     * Compile anything like: a template, styles, scripts.
     * 
     * @param string $compileType   The type of the thing which we are going to compile with.
     * 
     * @return Avane
     */
    
    
    
    /**
     * Template Compile
     * 
     * Compile a temple.
     * 
     * @param string $templatePath   The path of the template or the raw content if we are going to compile a raw template.
     * @param bool   $raw            Set true if we are going to compile the raw content instead of a file.
     * 
     * @return array
     */
    
    function templateCompile($templatePath, $raw = false)
    {
        if(!isset($this->templateCompiler))
            $this->templateCompiler = new AvaneTemplateCompiler($this);
       
        $info = $this->templateCompiler->compile($templatePath, $raw);
        
        return ['path'    => $info['path'],
                'content' => $info['content']];
    }
    
    
    
    
    /***********************************************
    /***********************************************
    /****************** P J A X ********************
    /***********************************************
    /***********************************************
    
    /**
     * Return PJAX
     * 
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
        
        if(in_array('title', $types))
            $data['title'] = $this->title;
        
        if(in_array('html', $types))
            $data['html'] = $this->outputBuffer['header']  . 
                            $this->outputBuffer['content'] . 
                            $this->outputBuffer['footer'];
        
        if(in_array('header', $types))
            $data['header'] = $this->outputBuffer['header'];
        
        if(in_array('content', $types))
            $data['content'] = $this->outputBuffer['content'];
        
        if(in_array('footer', $types))
            $data['footer'] = $this->outputBuffer['footer'];
        
        return $data;
    }
    
    
    
    
    /***********************************************
    /***********************************************
    /**************** C A P T U R E ****************
    /***********************************************
    /***********************************************
    
    /**
     * Capture
     * 
     * Capture the rendered content after this function.
     * 
     * @return Avane
     */
     
    function capture()
    {
        if(!$this->isPJAX)
            return $this;
            
        ob_start();
        
        return $this;
    }
    
    
    
    
    /**
     * End Capture
     * 
     * Stop capturing, and store the captured content to the right array.
     */
    
    function endCapture($position = null)
    {
        if(!$this->isPJAX && $position)
            return this;
        
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
    
    
    
    
    /***********************************************
    /***********************************************
    /****************** L O O P ********************
    /***********************************************
    /***********************************************
    
    /**
     * Loop Front
     * 
     * Initialize a new loop.
     * 
     * @param array $mainArray   The new array.
     *  
     * @return Avane
     */
    
    function loopFront($mainArray)
    {
        $this->loop[] = ['index'     => 1,
                         'index0'    => 0,
                         'revindex'  => count($mainArray),
                         'revindex0' => count($mainArray) - 1,
                         'first'     => true,
                         'last'      => count($mainArray) == 1,
                         'length'    => count($mainArray),
                         'even'      => false,
                         'odd'       => true];
        
        return $this;
    }
    
    
    
    
    /**
     * Loop Start
     * 
     * Explode this array, turn all the values to the avane variables.
     * 
     * @param array  $array   The array of 'this round'.
     * @param string $name    The name of the array.
     * 
     * @return Avane
     */
     
    function loopStart($array, $name)
    {
        $this->set($name, $array);
        
        return $this;
    }
    
    
    
    
    /**
     * Loop End
     * 
     * 'This round' is ended, now update the information of the loop, and unset those variables for this round.
     * 
     * @return Avane
     */
     
    function loopEnd()
    {
        $this->cleanSet($name);
        
        $latest = count($this->loop) - 1;

        $this->loop[$latest]['index']      += 1;
        $this->loop[$latest]['index0']     += 1;
        $this->loop[$latest]['revindex']   -= 1;
        $this->loop[$latest]['revindex0']  -= 1;
        $this->loop[$latest]['first']       = $this->loop[$latest]['index']    == 1;
        $this->loop[$latest]['last']        = $this->loop[$latest]['revindex'] == 1;
        $this->loop[$latest]['even']        = $this->loop[$latest]['index'] % 2 === 0;
        $this->loop[$latest]['odd']         = $this->loop[$latest]['index'] % 2 !== 0;
        
        return $this;
    }
    
    
    
    
    /**
     * Loop Back
     * 
     * The end of the whole loop, remove this loop from the loop array.
     * 
     * @return Avane
     */
     
    function loopBack()
    {
        array_pop($this->loop);
        
        return $this;
    }
    

    
    
    /***********************************************
    /***********************************************
    /************* V A R I A B L E S ***************
    /***********************************************
    /***********************************************
    
    /**
     * Set
     * 
     * Store a custom variable for Avane.
     * 
     * @param string $key     The name of the variable.
     * @param mixed  $value   The value of the variable.
     * 
     * @return Avane
     */
    
    function set($key, $value)
    {
        $this->vault[$key] = $value;
        
        return $this;
    }
    
    
    
    
    /**
     * Group Set
     * 
     * Same as the set() just changed to an array this time.
     * 
     * @param array $vars   The variables, key as the name, value as the value of the variable.
     * 
     * @return Avane
     */
    
    function groupSet($vars = null)
    {
        if(!empty($vars))
            foreach($vars as $name => $value)
                $this->vault[$name] = $value;
        
        return $this;
    }
    
    
    
    
    /**
     * Clean Set
     * 
     * Unset an avane variable.
     * 
     * @param string $key   The name of the variable.
     * 
     * @return Avane
     */
     
    function cleanSet($key)
    {
        unset($this->vault[$key]);
        
        return $this;
    }
    
    
    
    
    /**
     * Get
     * 
     * Get a variable from the vault.
     * 
     * @param string $key   The name of the variable.
     * 
     * @return Avane
     */
    
    function get($key)
    {
        if($key == 'loop')
            return $this->loop[count($this->loop) - 1];
        else
            return $this->vault[$key];
    }
    
    
    
    
    /**
     * Directive
     * 
     * Return a filtered variable.
     * 
     * @param string $value       The value.
     * @param string $directive   The name of the filter.
     * 
     * @return string
     */
    
    function directive($value, $directive)
    {
        //$directive = str_replace(' ', '', $directive);
        //$directive = '_' . $directive;
        
        return AvaneDirectives::$directive($value);
    }

}
?>