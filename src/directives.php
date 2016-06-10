<?php
namespace Avane;

class Directives
{
    //nl2br, escape, upper, lower, whitespace
    static $functions = [];


    static function constructor()
    {
        self::$functions = ['_nl2br'      => self::_nl2br,
                            '_escape'     => self::_escape,
                            '_upper'      => self::_upper,
                            '_lower'      => self::_lower,
                            '_whitespace' => self::_whitespace];
    }


    public static function __callStatic($name, $args)
    {

        if(strpos($name, '_') !== false)
        {
            $function = self::$functions[$name];

            return call_user_func_array($function, $args[0]);
        }
    }


    static function customDirective($name, $function)
    {
        self::$functions[$name] = $function;
    }



    /**
     * nl2br
     *
     * @param string $content   The content that we want to nl2br() with.
     *
     * @return string
     */

    static function _nl2br($content)
    {
        if(is_array($content))
            $content = $content[0];

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
        $content = is_array($content) ? $content[0] : $content;

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