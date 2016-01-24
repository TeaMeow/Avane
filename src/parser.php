<?php
class AvaneParser extends Avane
{
    protected static $analyzers = ['AvaneVariableAnalyzer', 'AvaneConditionAnalyzer'];
    
    static function parse($tokenGroup)
    {
        $combinedToken = $tokenGroup['combinedToken'];
        
        foreach(static::$analyzers as $analyzer)
        {
            $groupType = $analyzer::validate($combinedToken);
            
            if(!$groupType) break;
            
            $tokenGroup['type'] = $groupType;
            
            $analyzer::parse($tokenGroup);

            
        }
    }
    
    
    static function firstToken($tokens, $targetToken)
    {
        return self::nthToken($tokens, 1, $targetToken);
    }
    
    static function nthToken($tokens, $nth, $targetToken)
    {
        $count = 1;
        
        foreach($tokens as $token)
        {
            if($token['token'] == $targetToken)
            {
                if($count == $nth)
                    return $token;
                
                $count++;
            }
        }
        
        return false;
    }

}
?>