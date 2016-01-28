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
     * When user wants to load the same template, we have no needed to parse it again,
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
        $this->categoryPath       = $this->categoriesPath . $categoryName . '/';
        $this->compiledPath       = $this->categoryPath   . 'compiled/';
        $this->scriptsPath        = $this->scriptPath     . 'scripts/';
        $this->stylesPath         = $this->categoryPath   . 'styles/';
        $this->templateFolderPath = $this->categoryPath   . 'tpls/';
        $this->headerPath         = $this->categoryPath   . 'header.php';
        $this->footerPath         = $this->categoryPath   . 'footer.php';
        $this->variablesPath      = $this->categoryPath   . 'variables.php';
        $this->templateExtension  = '.tpl.php';
        
        return $this;
    }
    
    
    
    
    /**
     * Load
     * 
     * Load and require the template.
     * 
     * @param string $templateName   The name of the template (without the extension).
     * 
     * @return Avane
     */
    
    function load($templateName)
    {
        /** Compile the template If the cache bucket doesn't have the template */
        if(!$this->inBucket($templateName))
        {
            $templatePath = $this->getTemplatePath($templateName);
            
            $this->setBucket($templateName, $this->templateCompile($templatePath));
        }
        
        
        /** Require the template from the cache bucket, so we don't need to compile it to get the path again */
        require($this->cacheBucket[$templateName]['path']);
        
        return $this;
    }
    
    
    function setBucket($templateName, $path)
    {
        $this->cacheBucket[$templateName] = $path;
        
        return $this;
    }

    
    /**
     * Fetch
     * 
     */
    
    function fetch($templateName, $variables = null)
    {
        /** Compile the template If the cache bucket doesn't have the template */
        if(!isset($this->cacheBucket[$templateName]))
        {
            $templatePath = $this->getTemplatePath($templateName);
            
            $this->setBucket($templateName, $this->templateCompile($templatePath));
        }
        
        
        return $this->cacheBucket[$templateName]['content'];
        
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
    
    
    
    
    
    function Unzip($array, $arrayName)
    {
        $this->set($arrayName, $array);
    }
    

    
    
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
     * Get
     * 
     * Get a variable from the vault.
     * 
     * @param string      $key         The name of the variable.
     * @param string|null $directive   The name of the directive.
     * 
     * @return Avane
     */
    
    function get($key)
    {
        return $this->vault[$key];
    }
    
    
    
    
    function directive($value, $directive)
    {
        //$directive = str_replace(' ', '', $directive);
        //$directive = '_' . $directive;
        
        return AvaneDirectives::$directive($value);
    }
    
    function header()
    {
        return $this;
    }
    
    function footer()
    {
        return $this;
    }
}
?>