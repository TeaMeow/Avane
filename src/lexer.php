<?php
class AvaneLexer extends Avane
{
    protected static $scannedAll    = [];
    protected static $scannedTokens = [];
    protected static $sortedTokens  = ['useful' => [], 'trash' => [[]]];
    protected static $cleanedTokens = [];
    protected static $openAndCloseTags = [['open' => 'T_HELPER_OPEN_TAG', 'close' => 'T_HELPER_CLOSE_TAG'],
                                          ['open' => 'T_OPEN_TAG', 'close' => 'T_CLOSE_TAG']];
    protected static $sortFormat    = ['tokens' => [], 'length' => 0, 'position' => 0, 'line' => 0, 'match' => ''];
    protected static $spaceToken = 'T_WHITESPACE';
    
    protected static $tokens = 
    [
        '/^[a-zA-Z](\.)[a-zA-Z]/' => 'T_CHILD',
        '/^(==)/'                 => 'T_IS_EQUAL',
        '/^(!=)/'                 => 'T_NOT_EQUAL',
        '/^(=)/'                  => 'T_EQUAL',
        '/^(\+)/'                 => 'T_PLUS',
        '/^(\-)/'                 => 'T_MINUS',
        '/^(elseif)/'             => 'T_ELSEIF',
        '/^(endif)/'              => 'T_ENDIF',
        '/^(if)/'                 => 'T_IF',
        '/^(\s+)/'                => "T_WHITESPACE",
        '/^(else)/'               => 'T_ELSE',
        '/^(endforeach)/'         => 'T_ENDFOREACH',
        '/^(foreach)/'            => 'T_FOREACH',
        '/^(for)/'                => 'T_FOR',
        '/^(endwhile)/'           => 'T_ENDWHILE',
        '/^(while)/'              => 'T_WHILE',
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
        '/^(\\\)/'                => 'T_BACKSLASH',
        '/^(\/)/'                 => 'T_SLASH',
        '/^(\#)/'                 => 'T_SHARP',
        '/^(\.)/'                 => 'T_DOT',
        '/^([\pL])/u'             => 'T_STRING',
    ];
    
    
    
    
    public static function run($source)
    {
        $tokens = [];
    
        foreach($source as $number => $line)
        {            
            $offset = 0;
            
            while($offset < strlen($line))
            {
                $result = static::scan($line, $number, $offset);
                
                if($result === false) 
                {
                    throw new Exception("Unable to parse line " . ($line + 1) . ", offset: ". substr($line, $offset) .".");
                }
                
                $tokens[] = $result;
                $offset  += strlen($result['match']);
            }
        }
        
        static::collect($tokens);
    }
    
    

    protected static function scan($line, $number, $offset) 
    {
        $string = substr($line, $offset);
    
        foreach(static::$tokens as $pattern => $name)
        {
            if(preg_match($pattern, $string, $matches))
            {
                return ['match'    => $matches[1],
                        'token'    => $name,
                        'line'     => $number + 1,
                        'position' => $offset];
            }
        }
    
        return false;
    }
    
    protected static function _replace($tokens)
    {
       // $collects = static::_collect($tokens)
        //static::$scannedTokens = array_keys(array_column($tokens, 'token'), 'T_HELPER_OPEN_TAG');
        var_dump(array_column($tokens, 'token'));
    }
    
    
    protected static function collect($tokens)
    {
        static::$scannedAll    = $tokens;
        static::$scannedTokens = array_column($tokens, 'token');
        

            static::surrounder();
    }
    
    protected static function surrounder()
    {
        $isUseful = false;
  
        
        foreach(static::$openAndCloseTags as $pair)
        {
            foreach(static::$scannedAll as $key => $single)
            {
                $token = $single['token'];
                $line  = $single['line'];
                $match = $single['match'];
                $position = $single['position'];
                
                if($token == $pair['open'])
                {
                    $isUseful = true;
                }
                
                
                $category = $isUseful ? 'useful' : 'trash';
                $array    = &static::$sortedTokens[$category];
                
                if($token == $pair['open'])
                    array_push($array, static::$sortFormat);
                    
                    
                if($isUseful)
                {
                    $currentPosition = &$array[count($array) - 1];
           
                    array_push($currentPosition['tokens'], $single);
                    
                    $currentPosition['line']     = $line;
                    $currentPosition['length']  += mb_strlen($match, 'UTF-8');
                    $currentPosition['match']   .= $match;
                    $currentPosition['position'] = $token == $pair['open'] ? $position : $currentPosition['position'];
                }
                
                if($token == $pair['close'])
                {
                    $isUseful = false;
                }
            }
        }
        
        
        static::parse();
        
    }
    
    protected static function stripSpaces()
    {
        $sortedTokens = static::$sortedTokens['useful'];
        
        foreach($sortedTokens as $groupKey => $group)
        {
            foreach($group['tokens'] as $tokenKey => $token)
            {
                if($token['token'] != static::$spaceToken)
                    continue;

                unset($sortedTokens[$groupKey]['tokens'][$tokenKey]);
               
            }
            
            //reindex tokens
             $sortedTokens[$groupKey]['tokens'] = array_values($sortedTokens[$groupKey]['tokens']);
            
        }
        
        static::$sortedTokens = $sortedTokens;

    }
    
    protected static function parse()
    {
        static::stripSpaces();
        
        
        
        
        //var_dump(static::$sortedTokens);
    }


}

?>