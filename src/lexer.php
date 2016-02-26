<?php
namespace Avane;

class Lexer
{
    protected static $grouped          = [];
    protected static $openAndCloseTags = [['open' => 'T_HELPER_OPEN_TAG', 'close' => 'T_HELPER_CLOSE_TAG'],
                                          ['open' => 'T_OPEN_TAG', 'close' => 'T_CLOSE_TAG']];
    protected static $sortFormat       = ['tokens' => [], 'length' => 0, 'position' => 0, 'line' => 0, 'match' => '', 'combinedToken' => ''];
    protected static $spaceToken       = 'T_WHITESPACE';

    protected static $tokens =
    [
        //'/^[a-zA-Z](\.)[a-zA-Z]/' => 'T_CHILD',
        '/^(==)/'                 => 'T_IS_EQUAL',
        '/^(!=)/'                 => 'T_NOT_EQUAL',
        '/^(=)/'                  => 'T_EQUAL',
        '/^(\+\+)/'               => 'T_INCREASE',
        '/^(\+)/'                 => 'T_PLUS',
        '/^(\-\-)/'               => 'T_DECENT',
        '/^(\-)/'                 => 'T_MINUS',
        '/^(elseif)/'             => 'T_ELSEIF',
        '/^(endif)/'              => 'T_ENDIF',
        '/^(if)/'                 => 'T_IF',
        '/^(null)/i'              => 'T_NULL',
        '/^(true)/i'              => 'T_TRUE',
        '/^(false)/i'             => 'T_FALSE',
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
        '/^(\[)/'                 => 'T_ARRAY_OPEN_TAG',
        '/^(\])/'                 => 'T_ARRAY_CLOSE_TAG',
        '/^(,)/'                  => 'T_COMMA',
        '/^(:)/'                  => 'T_SEPARATOR',
        '/^(;)/'                  => 'T_LINE_END',
        '/^(\$)/'                 => 'T_DOLLARSIGN',
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
        '/^(\%)/'                 => 'T_PERCENTAGE'
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

        return static::group($tokens);
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


    protected static function group($tokens)
    {

        $allDots = [[]];
        $cleaned = [];
        $root = null;
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