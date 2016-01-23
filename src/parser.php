<?php
class AvaneParser extends Avane
{
    protected static $analyzers = ['AvaneVariableAnalyzer'];
    
    static function parse($tokenGroup)
    {
        foreach(static::$analyzers as $analyzer)
        {
           
            $validated = $analyzer::validate($tokenGroup['combinedToken']);
            
            //$type = $analyzer::validate($tokenGroup);
        }
    }

}
?>