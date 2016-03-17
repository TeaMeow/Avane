<?php
namespace Avane;

class Lexer
{
    protected static $tokens =
    [
        '/^([+-]?(?=\d*[.eE])(?=\.?\d)\d*\.?\d*(?:[eE][+-]?\d+)?)/' => 'T_DNUMBER',
        '/^(\d+)/'                => 'T_LNUMBER',
        '/^(\w+)/'                => 'T_IDENTIFIER',
        '/^(\$\w+)/'              => 'T_PHP_IDENTIFIER',
        '/^(\")/'                 => 'T_DOUBLE_QUOTE',
        '/^(\')/'                 => 'T_QUOTE',
        '/^(\.)/'                 => 'T_DOT',
        '/^([\pL])/u'             => 'T_STRING'
    ];


    static function run($source)
    {
        
        $tokens = [];
        $offset = 0;
        $length = mb_strlen($source, 'UTF-8');


        while($offset < $length)
        {
            $result = self::scan($source, $offset);

            if($result === false)
            {
                $offset++;
                continue;
            }

            $tokens[] = $result;
            $offset  += strlen($result['match']);
        }



        return self::group($tokens);
    }




    static function scan($source, $offset)
    {
        $string = substr($source, $offset);

        foreach(self::$tokens as $pattern => $name)
        {
            if(preg_match($pattern, $string, $matches))
            {
                return ['match'    => $matches[1],
                        'token'    => $name,
                        'position' => $offset];
            }
        }

        return false;
    }




    protected static function group($tokens)
    {

        $allDots   = [[]];
        $cleaned   = [];
        $root      = null;
        $isInQuote = false;

        foreach($tokens as $tokenKey => $token)
        {

            if($isInQuote)
                if($token['token'] == 'T_QUOTE')
                    $isInQuote = false;


            if(!$isInQuote)
                if($token['token'] == 'T_QUOTE')
                    $isInQuote = true;


            if($isInQuote)
                continue;

            $prev  = isset($tokens[$tokenKey - 1]) ? $tokens[$tokenKey - 1] : null;
            $next  = isset($tokens[$tokenKey + 1]) ? $tokens[$tokenKey + 1] : null;
            $right = isset($tokens[$tokenKey + 2]) ? $tokens[$tokenKey + 2] : null;


            $latest = count($allDots) - 1 > 0 ? count($allDots) - 1 : 0;

            if(!$root && $token['token'] == 'T_IDENTIFIER')
            {
                $root = $token;
                $allDots[$latest][] = $token;
                continue;
            }


            if($token['token'] == 'T_IDENTIFIER' && $root)
                $allDots[$latest][] = $token;

            if(($token['token'] == 'T_IDENTIFIER' && $prev['token'] == 'T_DOT' && $next['token'] != 'T_DOT') ||
               ($token['token'] == 'T_IDENTIFIER' && $next['token'] != 'T_DOT'))
            {

                array_push($allDots, []);
            }
        }



         //array_pop($allDots);


        return $allDots;

    }
}

?>