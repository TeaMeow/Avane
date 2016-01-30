<?php

class AvaneTemplateParser
{
    private $tplContent;
    private $basicTags = ['/{% else %}/'      => '<?php else: ?>',
                          '/{% \/if %}/'      => '<?php endif; ?>',
                          '/{% \/for %}/'     => '<?php endfor; ?>',
                          '/{% \/foreach %}/' => '<?php $this->loopEnd(); endforeach; $this->loopBack(); ?>',
                          '/{% \/while %}/'   => '<?php endwhile; ?>',
                          '/{% countinue %}/' => '<?php countinue; ?>',
                          '/{% break %}/'     => '<?php break; ?>'];
    
    
    
    
    /**
     * Parse
     * 
     * Parse a tpl file.
     * 
     * @param  string $tplContent   The content of the template file.
     * 
     * @return string              The parsed content.
     */
     
    function parse($tplContent)
    {
        $this->tplContent = $tplContent;

        $this->replaceBasicTag()        // {% /if %}, {% else %}
             ->replaceIf()              // {% if %}
             ->replaceElseIf()          // {% elseif %}
             ->replaceShorthandIf()     // { a ? b : c }
             ->replaceEchoShorthandIf() // { a >> b : c }
             ->replaceDirectiveVar()    // { var | upper }
             ->replaceVar()             // { var }
             ->replaceForeach()         // {% foreach %}
             ->replaceWhile()           // {% while %}
             ->replaceIncludes()        // {% include %}
             ->replaceImport();         // {% import %}
        
        return $this->tplContent;
    }
    
    
    
    
    /**
     * Replace Basic Tags
     * 
     * @return AvaneTemplateParser
     */
    
    function replaceBasicTag()
    {
        foreach($this->basicTags as $regEx => $replacement)
            $this->tplContent = preg_replace($regEx, $replacement, $this->tplContent);
        
        return $this;
    }
    
    
    
    
    /**
     * Replace If
     * 
     * @return AvaneTemplateParser
     */
     
    function replaceIf()
    {
        $this->tplContent = preg_replace_callback('/{% if (.*?) %}/', function($matched)
        {
            $matched[1] = $this->analyzeVariable($matched[1]);
            
            return "<?php if($matched[1]): ?>";
            
        }, $this->tplContent);
        
        return $this;
    }
    
    
    
    
    /**
     * Replace Else If
     * 
     * @return AvaneTemplateParser
     */
     
    function replaceElseIf()
    {
        $this->tplContent = preg_replace_callback('/{% elseif (.*?) %}/', function($matched)
        {
            $matched[1] = $this->analyzeVariable($matched[1]);

            return "<?php if($matched[1]): ?>";
            
        }, $this->tplContent);
        
        return $this;
    }
    
    
    
    
    /**
     * Replace Shorthand If
     * 
     * @return AvaneTemplateParser
     */
     
    function replaceShorthandIf()
    {
        $this->tplContent = preg_replace_callback('/{(.*?)\?(.*?)\:(.*?)}/', function($matched)
        {
            $matched[1] = $this->analyzeVariable($matched[1]);
            $matched[2] = $this->analyzeVariable($matched[2]);
            $matched[3] = $this->analyzeVariable($matched[3]);
            
            return "<?= $matched[1] ? $matched[2] : $matched[3] ?>";
            
        }, $this->tplContent);
        
        return $this;
    }
    
    
    
    
    /**
     * Replace Shorthand If
     * 
     * @return AvaneTemplateParser
     */
     
    function replaceEchoShorthandIf()
    {
        $this->tplContent = preg_replace_callback('/{(.*?)>>(.*?)\:(.*?)}/', function($matched)
        {
            $matched[1] = $this->analyzeVariable($matched[1]);
            //$matched[2] = $this->analyzeVariable($matched[2]);
            //$matched[3] = $this->analyzeVariable($matched[3]);
            
            return "<?= $matched[1] ? '$matched[2]' : '$matched[3]'; ?>";
            
        }, $this->tplContent);
        
        return $this;
    }
    
    
    
    
    /**
     * Replace Directive Variables
     * 
     * @return AvaneTemplateParser
     */
     
    function replaceDirectiveVar()
    {
        $this->tplContent = preg_replace_callback('/{(.*?)\|(.*?)}/', function($matched)
        {
            $matched[1] = $this->analyzeVariable($matched[1]);
            $matched[2] = str_replace(' ', '', $matched[2]);
            $matched[2] = '_' . $matched[2];

            return '<?= $this->directive' . "($matched[1], '$matched[2]'); ?>";
            
        }, $this->tplContent);
        
        return $this;
    }
    
    
    
    
    /**
     * Replace Variables
     * 
     * @return AvaneTemplateParser
     */
     
    function replaceVar()
    {
        $this->tplContent = preg_replace_callback('/{([^%].*?)}/', function($matched)
        {
            $matched[1] = $this->analyzeVariable($matched[1]);

            return "<?= $matched[1]; ?>";
            
        }, $this->tplContent);
        
        return $this;
    }
    


    
    /**
     * Replace Foreach
     * 
     * @return AvaneTemplateParser
     */
     
    function replaceForeach()
    {
        $this->tplContent = preg_replace_callback('/{% foreach (.*?) as (.*?) %}/', function($matched)
        {
            $matched[1] = $this->analyzeVariable($matched[1]);
            
            return '<?php $this->loopFront(' . "$matched[1], '$matched[2]'); foreach($matched[1] as $$matched[2]): " . '$this->loopStart(' . "$$matched[2], '$matched[2]'); ?>";
        }, $this->tplContent);
        
        return $this;
    }
    
    
    
    
    /**
     * Replace While
     * 
     * @return AvaneTemplateParser
     */
    
    function replaceWhile()
    {
        $this->tplContent = preg_replace_callback('/{% while (.*?) %}/', function($matched)
        {
            $matched[1] = $this->analyzeVariable($matched[1]);
            
            return "<?php while($matched[1]): ?>";
        }, $this->tplContent);
        
        return $this;
    }
    
    
    
    
    /**
     * Replace Includes
     * 
     * @return AvaneTemplateParser
     */
     
    function replaceIncludes()
    {
        $this->tplContent = preg_replace_callback('/{% includes (.*?) %}/', function($matched)
        {
            return "<?php include '$matched[1]'; ?>";
        }, $this->tplContent);
        
        return $this;
    }
    
    
    
    
    /**
     * Replace Import
     * 
     * @return AvaneTemplateParser
     */
     
    function replaceImport()
    {
        $this->tplContent = preg_replace_callback('/{% import (.*?) %}/', function($matched)
        {
            return '<?php $this->Output(\'' . $matched[1] . '\'); ?>';
        }, $this->tplContent);
        
        return $this;
    }
    
    
    
    
    /**
     * Analyze Variable
     * 
     * Lex the avane variables and return a result.
     * 
     * @param string $matched   The string which we will look in to it.
     * 
     * @return array
     */
    
    function analyzeVariable($matched)
    {
        $grouped = AvaneLexer::run([$matched]);
        
        return $this->lexerToPHP($matched, $grouped);
    }
    
    
    
    
    /**
     * Lexer To PHP
     * 
     * Convert the lexer variable result to php echo.
     * 
     * @param string $string    The text which we are going to modify with.
     * @param array  $grouped   The array which generated by the lexer.
     * 
     * @return string
     */
    
    function lexerToPHP($string, $grouped)
    {
        $prepared = [];
        
        foreach($grouped as $single)
        {
            $totalLen = 0;
            $isFirst  = true;
            $isMany   = count($single) > 1;
            $output   = '';
            
            foreach($single as $each)
            {
                $length    = mb_strlen($each['match'], 'UTF-8');
                $totalLen += $length;
                
                $replace  .= $each['match'];
                
                
                if($isFirst)
                    $output .= '$this->get(\'' . $each['match'] . '\')';
                else
                    $output .= '[\'' . $each['match'] . '\']';
                
                $isFirst = false;
            }
            
            //$totalLen = $isMany ? $totalLen + 1 * ($totalLen - 1) 
            
              $totalLen = $isMany ? $totalLen + (1 * $totalLen) : $totalLen;
            
            $prepared[] = ['startPos' => $single[0]['position'],
                           'length'   => $totalLen,
                           'output'   => $output];
        }
        
        $prepared = array_reverse($prepared);
        
        foreach($prepared as $replace)
            if($replace['startPos'] !== null)
                $string = substr_replace($string, $replace['output'], $replace['startPos'], $replace['length']);
        
        return $string;
    }
}
?>