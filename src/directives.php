<?php
class AvaneDirectives
{
    //nl2br, escape, upper, lower, whitespace
    
    
    /**
     * nl2br
     * 
     * @param string $content   The content that we want to nl2br() with.
     * 
     * @return string
     */
     
    static function _nl2br($content)
    {
        return nl2br($content);
    }
    
    
    
    
    /**
     * escape
     * 
     * @param string $content   The content that we want to escape with.
     * 
     * @return string
     */
     
    static function _escape($content)
    {
        return htmlspecialchars($content);
    }
    
    
    
    
    /**
     * upper
     * 
     * @param string $string   The string that we want to convert to uppercase.
     * 
     * @return string
     */
    
    static function _upper($string)
    {
        return strtoupper($string);
    }
    
    
    
    
    /**
     * lower
     * 
     * @param string $string   The string that we want to convert to lowercase.
     * 
     * @return string
     */
    
    static function _lower($string)
    {
        return strtolower($string);
    }
    
    
    
    
    /**
     * whitespace
     * 
     * @param string $string   The string that we want to strip the whitespaces.
     * 
     * @return string
     */
    
    static function _whitespace($string)
    {
        return preg_replace('!\s+!', ' ', $string) ?: $string;
    }
}
?>