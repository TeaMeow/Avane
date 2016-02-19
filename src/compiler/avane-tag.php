<?php
class AvaneAvTagCompiler extends Avane
{
    protected $avaneNames;
    protected $content;

    function __construct($thisOne)
    {
        if($thisOne) parent::__construct($thisOne);

        if(!$this->anaveNames)
            $this->avaneNames = file_exists($this->avNamesPath) ? json_decode(file_get_contents($this->avNamesPath), true)
                                                                : [];
    }


    function compile($htmlContent)
    {
        if(!$htmlContent)
            return false;

        $this->content = $htmlContent;

        $this->collect()
             ->outputJs();
    }

    function collect()
    {
        $content = str_get_html($this->content);



        foreach($content->find('*[av-group]') as $element)
        {
            $group = $element->attr['av-group'];

            foreach($element->find('*[av-name]') as $child)
            {
                $this->pushGroup($group, $child->attr['av-name']);
            }
        }

        /*foreach($content->find('*[av-name]') as $element)
        {
            if(!isset($element->attr['av-group']))
                $this->pushGroup('%', $element->attr['av-name']);
            else
                $this->pushGroup($element->attr['av-group'], $element->attr['av-name']);
        }*/

        file_put_contents($this->avNamesPath, json_encode($this->avaneNames));

        return $this;
    }


    function pushGroup($group, $name)
    {
        if(!isset($this->avaneNames[$group]))
            $this->avaneNames[$group] = [];

        array_push($this->avaneNames[$group], $name);
        $this->avaneNames[$group] = array_unique($this->avaneNames[$group]);

        return $this;
    }




    function outputJs()
    {
        $js = '$(document).ready(function(){';

        foreach($this->avaneNames as $group => $nameList)
        {
            foreach($nameList as $name)
            {
                $js .= "window.\${$group}_$name = $('[av-group=\"$group\"] *:not([av-group]) [av-name=\"$name\"], [av-group=\"$group\"] > [av-name=\"$name\"]'); \n";
                $js .= "window.{$group}_$name = \"[av-group='$group'] *:not([av-group]) [av-name='$name'], [av-group='$group'] > [av-name='$name']\"; \n";
            }
        }

        $js .= '});';


        file_put_contents($this->avScriptPath, $js);
    }

    static function outputCss($string)
    {
        return preg_replace('/%%(.*?)%%/', '[av-group="$1"] *:not([av-group]),'."\n".'[av-group="$1"] >', $string);
    }
}
?>