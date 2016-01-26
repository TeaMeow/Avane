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
    
    
    
    
    function load($path)
    {
        $tplContent = file_get_contents($path);
        
        //e(file_get_contents($path));
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
    
    function get($key, $directive = null)
    {
        $directive = $directive ? '_' . $directive : null;
        
        return $directive ? AvaneDirectives::$directive($this->vault[$key]) : $this->vault[$key];
    }
}
?>