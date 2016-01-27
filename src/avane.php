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

        $n = ['/{% \/if %}/'      => '<?php endif; ?>',
              '/{% \/for %}/'     => '<?php endfor; ?>',
              '/{% \/foreach %}/' => '<?php endforeach; ?>',
              '/{% \/while %}/'   => '<?php endwhile; ?>',
              ];
        
        foreach($n as $regEx => $to)
        {
            $tplContent = preg_replace($regEx, $to, $tplContent);
        }
        
        
        
        $tplContent = preg_replace_callback('/{% if (.*?) %}/', function($matched)
        {
            $grouped = AvaneLexer::run([$matched[1]]);
            
            $matched[1] = $this->lex2var($matched[1], $grouped);
            
            
            return "<?php if($matched[1]): ?>";
        }, $tplContent);


        $tplContent = preg_replace_callback('/{(.*?)\?(.*?)\:(.*?)}/', function($matched)
        {
           
            
            $grouped    = AvaneLexer::run([$matched[1]]);
            $matched[1] = $this->lex2var($matched[1], $grouped);
            
            $grouped2    = AvaneLexer::run([$matched[2]]);
            $matched[2] = $this->lex2var($matched[2], $grouped2);
            
            $grouped3    = AvaneLexer::run([$matched[3]]);
            $matched[3] = $this->lex2var($matched[3], $grouped3);
            
           
            return "<?= $matched[1] ? $matched[2] : $matched[3] =?>";
        }, $tplContent);

        
        
        $tplContent = preg_replace_callback('/{(.*?)\|(.*?)}/', function($matched)
        {
            
            
            $grouped    = AvaneLexer::run([$matched[1]]);
            $matched[1] = $this->lex2var($matched[1], $grouped);
            $matched[2] = str_replace(' ', '', $matched[2]);
            $matched[2] = '_' . $matched[2];
            
            
            return '<?= $this->directive' . "($matched[1], '$matched[2]'); =?>";
        }, $tplContent);
        
        
        
        
        
        
        $tplContent = preg_replace_callback('/{([^%].*?)}/', function($matched)
        {
            
            $grouped = AvaneLexer::run([$matched[1]]);
            
            
            
            $matched[1] = $this->lex2var($matched[1], $grouped);
            
            
            return "<?= $matched[1]; =>";
        }, $tplContent);
        
        
        
        $tplContent = preg_replace_callback('/{% foreach (.*?) as (.*?) %}/', function($matched)
        {
            $grouped = AvaneLexer::run([$matched[1]]);
            
            $matched[1] = $this->lex2var($matched[1], $grouped);
            
            //$grouped2 = AvaneLexer::run([$matched[2]]);
            //$matched[2] = $this->lex2var($matched[2], $grouped2);
            
            
            
            return '<?php foreach(' . $matched[1] . ' as $' . $matched[2] . '): $this->loopUnzip($' . $matched[2] . ', \'' . $matched[2] . '\'); ?>';
        }, $tplContent);
        
        
        
       

         e($tplContent);
       
    }
    
    
    
    function lex2var($string, $grouped)
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
            
            $totalLen = $isMany ? $totalLen + 1 * ($totalLen - 1) 
                                : $totalLen;
            
            $prepared[] = ['startPos' => $single[0]['position'],
                           'length'   => $totalLen,
                           'output'   => $output];
        }
        
        $prepared = array_reverse($prepared);
        
        foreach($prepared as $replace)
            $string = substr_replace($string, $replace['output'], $replace['startPos'], $replace['length']);
        
        return $string;
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
    
    function get($key, $directive = null)
    {
        $directive = $directive ? '_' . $directive : null;
        
        return $directive ? AvaneDirectives::$directive($this->vault[$key]) : $this->vault[$key];
    }
    
    
    
    
    function directive($value, $directive)
    {
        //$directive = str_replace(' ', '', $directive);
        //$directive = '_' . $directive;
        
        return AvaneDirectives::$directive($value);
    }
}
?>