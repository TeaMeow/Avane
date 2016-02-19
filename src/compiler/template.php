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
        
        
        $this->collectAvaneTags($compiledContent);
        
        
        file_put_contents($this->templateCachedPath, $compiledContent);
        
        return ['path'    => $this->templateCachedPath,
                'content' => $compiledContent];
    }
    
    
    
    function collectAvaneTags($compiledContent)
    {
        if(file_exists($this->avNamesPath))
            $names = json_decode(file_get_contents($this->avNamesPath), true);
        else
            $names = [];
        
        $a = str_get_html($compiledContent);
        
        foreach($a->find('*[av-name]') as $element)
        {
            
            
            $avGroup = $element->attr['av-group'] ? $element->attr['av-group']
                                                  : '%';
            
            if(!isset($names[$avGroup]))
                $names[$avGroup] = [];
             
             
              
            array_push($names[$avGroup], $element->attr['av-name']);
        }
        
        file_put_contents($this->avNamesPath, json_encode($names));
        
        $this->jsAvaneTags($names);
    }
    
    
    
    function jsAvaneTags($names)
    {
        $js = '';
        
        foreach($names as $group => $nameList)
        {
            foreach($nameList as $name)
            {//var $$group_$name = $('[av-name=\"$name\"][av-group=\"$group\"]');
                $js .= 'var $' . $group .'_' . $name . ' = $(\'[av-name="' . $name .'"][av-group="' . $group . '"]\');' . "\n";
            }
        }
        
        file_put_contents($this->avScriptPath, $js);
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