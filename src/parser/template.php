<?php
namespace Avane\Parser;

class Template
{
    private $tplContent;
    private $basicTags = ['/{% else %}/'      => '<?php else: ?>',

                          /*'/{% \/block %}/'   => '<?php $this->blockEnd(); ?>',*/
                          '/{% countinue %}/' => '<?php countinue; ?>',
                          '/{% break %}/'     => '<?php break; ?>'];

    private $endTags = ['/if'      => '<?php endif; ?>',
                        '/for'     => '<?php endfor; ?>',
                        '/foreach' => '<?php $this->loopEnd(); endforeach; $this->loopBack(); ?>',
                        '/repeat'  => '<?php $this->loopEnd(); endfor; $this->loopBack(); ?>',
                        '/while'   => '<?php endwhile; ?>'];




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


        $this->replaceEndTags();

        $this->replaceBasicTag()        // {% /if %}, {% else %}
             ->replaceIf()              // {% if %}
             ->replaceElseIf()          // {% elseif %}
             ->replaceShorthandIf()     // { a ? b : c }
             ->replaceEchoShorthandIf() // { a >> b : c }
             ->replaceDirectiveVar()    // { var | upper }
             ->replaceVar()             // { var }
             ->replaceForeach()         // {% foreach %}
             ->replaceRepeat()          // {% repeat %}
             ->replaceWhile()           // {% while %}
             ->replaceIncludes()        // {% include %}
             ->replaceImport()          // {% import %}
             ->replaceExtends()         // {% extends %}
             ->replaceBlock()           // {% block %}
             ->replaceYield()           // {% yield %}
             ->replaceNope();           // {% nope %}


        return $this->tplContent;
    }


    function replaceEndTags()
    {
        $this->tplContent = preg_replace_callback('/{% (\/.*) %}/', function($matched)
        {
            foreach($this->endTags as $endTag => $replacment)
                if($matched[1] == $endTag)
                    return $replacment;

            return $matched[0];

        }, $this->tplContent);
    }




    function replaveVariableTags()
    {

    }

    function replaceHelperTags()
    {

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

            return '<?= ' . $matched[1] . ' ? ' . $matched[2] . ' : ' . $matched[3] . ' ?>';

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

            return '<?php $this->loopFront(' . "$matched[1]); foreach($matched[1] as $$matched[2]): " . '$this->loopStart(' . "$$matched[2], '$matched[2]'); ?>";
        }, $this->tplContent);

        return $this;
    }




    /**
     * Replace Repeat
     *
     * @return AvaneTemplateParser
     */

    function replaceRepeat()
    {
        $this->tplContent = preg_replace_callback('/{% repeat (.*?) %}/', function($matched)
        {
            $matched[1] = $this->analyzeVariable($matched[1]);
            $uniqueID   = substr('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(0, 50), 1) . substr(md5(time()), 1) . time();
            $length     = $matched[1] - 1;

            return '<?php $this->loopFront(' . "range(0, $length)); for($$uniqueID = 0; $$uniqueID < $matched[1]; $$uniqueID++): " . '$this->loopStart(' . "$$uniqueID, '$uniqueID'); ?>";
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
        $this->tplContent = preg_replace_callback('/{% include (.*?) %}/', function($matched)
        {
            $variableName = '$this->get(\'TEMPLATE_INCLUDE_' . $matched[1] . '\')';

            return "<?php if($variableName){ include $variableName; } ?>";

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
            return '<?php $this->output(\'' . $matched[1] . '\'); ?>';
        }, $this->tplContent);

        return $this;
    }




    /**
     * Replace Extends
     *
     * @return AvaneTemplateParser
     */

    function replaceExtends()
    {
        $this->tplContent = preg_replace_callback('/{% extends (.*?) %}/', function($matched)
        {
            $variableName = '$this->get(\'TEMPLATE_INCLUDE_' . $matched[1] . '\')';

            return "<?php if($variableName){" . 'array_push($this->blockStatus, true);' . "include $variableName;" . 'array_pop($this->blockStatus); } ?>';

        }, $this->tplContent);

        return $this;
    }




    /**
     * Replace Block
     *
     * @return AvaneTemplateParser
     */

    function replaceBlock()
    {
        $this->tplContent = preg_replace_callback('/{% block (.*?) %}([\s\S]*?.*?[\s\S]*?){% \/block %}/s', function($matched)
        {
            $block        = explode(' ', $matched[1]);
            $blockName    = $block[0];
            $echoType     = isset($block[1]) && !empty($block[1]) ? $block[1] : false;
            $blockContent = $matched[2];

            $content  = '';
            $content .= '<?php ob_start(); ?>';
            $content .= $blockContent;
            $content .= '<?php $AVANE_BLOCK_CONTENT = ob_get_contents(); ob_end_clean(); ?>';
            $content .= '<?php $this->blockHandler(\''.$blockName.'\', $AVANE_BLOCK_CONTENT, \''.$echoType.'\'); ?>';

            return $content;

        }, $this->tplContent);

        return $this;
    }




    /**
     * Replace Yield
     *
     * @return AvaneTemplateParser
     */

    function replaceYield()
    {
        $this->tplContent = preg_replace_callback('/{% yield (.*?) %}/', function($matched)
        {
            $block        = explode(' ', $matched[1]);
            $blockName    = $block[0];
            $echoType     = isset($block[1]) && !empty($block[1]) ? $block[1] : false;
            //$blockContent = $matched[2];

            return '<?php $this->blockHandler(\''.$blockName.'\', \'\', \''.$echoType.'\'); ?>';

        }, $this->tplContent);

        return $this;
    }




    /**
     * Replace Block
     *
     * @return AvaneTemplateParser
     */

    function replaceNope()
    {
        $this->tplContent = preg_replace_callback('/{% nope %}(.*?){% \/nope %}/s', function($matched)
        {
            return '';

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
        $grouped = \Avane\Lexer::run($matched);

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

            if(empty($single))
                continue;

            foreach($single as $each)
            {
                $length    = mb_strlen($each['match'], 'UTF-8');
                $totalLen += $length;

                //$output  .= $each['match'];


                if($isFirst)
                {
                    $output .= '$this->get(\'' . $each['match'] . '\')';
                }
                else
                {
                    $output .= '[\'' . $each['match'] . '\']';
                    $totalLen++;
                }

                $isFirst = false;
            }

            //$totalLen = $isMany ? $totalLen + 1 * ($totalLen - 1)

              //$totalLen = $isMany ? $totalLen + (1 * $totalLen) : $totalLen;

            $prepared[] = ['startPos' => $single[0]['position'],
                           'length'   => $totalLen,
                           'output'   => $output];
        }

        $prepared = array_reverse($prepared);

        foreach($prepared as $replace)

            if($replace['startPos'] !== null)
                //echo(var_dump($string . '|' . $replace['output'] . '|' . $replace['startPos'] . '|'.  $replace['length'] ));
                $string = substr_replace($string, $replace['output'], $replace['startPos'], $replace['length']);


        return $string;
    }
}
?>