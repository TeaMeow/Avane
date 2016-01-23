<?php
class Lexer 
{
    protected statis $_open
    
    protected static $_terminals = 
    [
        '/^[a-zA-Z](\.)[a-zA-Z]/' => 'T_CHILD',
        '/^(==)/'                 => 'T_IS_EQUAL',
        '/^(!=)/'                 => 'T_NOT_EQUAL',
        '/^(=)/'                 => 'T_EQUAL',
        
        
        '/^(\+)/'                 => 'T_PLUS',
        '/^(\-)/'                 => 'T_MINUS',
        '/^(if)/'             => 'T_IF',
        '/^(\s+)/'                => "T_WHITESPACE",
        
        '/^(endif)/'          => 'T_ENDIF',
        '/^(else)/'           => 'T_ELSE',
        '/^(elseif)/'         => 'T_ELSEIF',
        '/^(for)/'            => 'T_FOR',
        '/^(foreach)/'        => 'T_FOREACH',
        '/^(endforeach)/'     => 'T_ENDFOREACH',
        '/^(while)/'          => 'T_WHILE',
        '/^(endwhile)/'       => 'T_ENDWHILE',
        '/^(endif)/'          => 'T_ENDIF',
        '/^({%)/'                 => 'T_HELPER_OPEN_TAG',
        '/^(%})/'                 => 'T_HELPER_CLOSE_TAG',
        '/^({)/'                  => 'T_OPEN_TAG',
        '/^(})/'                  => 'T_CLOSE_TAG',
        '/^(:)/'                  => 'T_SEPARATOR',
        '/^(\|)/'                 => 'T_BETWEEN',
        '/^(\?)/'                 => 'T_SECONDARY_SEPARATOR',
        '/^([+-]?(?=\d*[.eE])(?=\.?\d)\d*\.?\d*(?:[eE][+-]?\d+)?)/' => 'T_DNUMBER',
        '/^(\d+)/'                => 'T_LNUMBER',
        '/^(\w+)/'                => 'T_IDENTIFIER',
        '/^(\$\w+)/'              => 'T_PHP_IDENTIFIER',
        '/^(\>)/'                 => 'T_GREATHER',
        '/^(\<)/'                 => 'T_LESSER',
        '/^(\()/'                 => 'T_OPEN_PARENTHESES',
        '/^(\))/'                 => 'T_CLOSE_PARENTHESES',
        '/^(\")/'                 => 'T_DOUBLE_QUOTE',
        '/^(\')/'                 => 'T_QUOTE',
        '/^(\\\)/'                 => 'T_BACKSLASH',
        '/^(\/)/'                 => 'T_SLASH',
        '/^(\#)/'                 => 'T_SHARP',
        '/^(\.)/'                 => 'T_DOT',
        '/^([\pL])/u'                 => 'T_STRING',
    ];
    
    
    


    
    
    public static function run($source)
    {
        $tokens = [];
    
        foreach($source as $number => $line)
        {            
            $offset = 0;
            while($offset < strlen($line))
            {
                $result = static::_match($line, $number, $offset);
                if($result === false) 
                {
                    throw new Exception("Unable to parse line " . ($line + 1) . ", offset: ". substr($line, $offset) .".");
                }
                
                $tokens[] = $result;
                $offset  += strlen($result['match']);
            }
        }

        static::_replace($tokens);
        
    }

    protected static function _match($line, $number, $offset) 
    {
        $string = substr($line, $offset);
    
        foreach(static::$_terminals as $pattern => $name)
        {
            if(preg_match($pattern, $string, $matches))
            {
                return ['match' => $matches[1],
                        'token' => $name,
                        'line'  => $number + 1,
                        'position' => $offset];
            }
        }
    
        return false;
    }
    
    protected static function _replace($tokens)
    {
        $collects = static::_collect($tokens)
        $A = array_keys(array_column($tokens, 'token'), 'T_HELPER_OPEN_TAG');
        var_dump(array_column($tokens, 'token'));
    }
    
    
    protected statis function _collect($tokens)
    {
        
    }
    


}
$A = microtime(true);
$input = ['
{% if a + a == b %}
 <a href="#" class="g-2 g-s alb-photo-single--con" style="background-image: url(\'http://localhost/social2/contents/test/avatar.jpg\')">
            <div class="alb-photo-single--mask">
                <div class="alb-photo-mask--content album">
                    動漫
                </div>
            </div>
        </a>
'];
$result = Lexer::run($input);

var_dump(microtime(true) - $A)
?>