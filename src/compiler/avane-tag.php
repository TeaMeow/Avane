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
                if(!isset($this->avaneNames[$group]))
                    $this->avaneNames[$group] = [];

                array_push($this->avaneNames[$group], $child->attr['av-name']);
                $this->avaneNames[$group] = array_unique($this->avaneNames[$group]);
            }
        }

        file_put_contents($this->avNamesPath, json_encode($this->avaneNames));

        return $this;
    }




    function outputJs()
    {
        $js = '';

        foreach($this->avaneNames as $group => $nameList)
        {
            foreach($nameList as $name)
            {
                $js .= "var \${$group}_$name = $('[av-group=\"$group\"] [av-name=\"$name\"]'); \n";
                $js .= "var {$group}_$name = \"[av-group='$group'] [av-name='$name']\" \n";
            }
        }

        file_put_contents($this->avScriptPath, $js);
    }

    function outputCss()
    {

    }
}
?>