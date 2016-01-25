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

            if($groupType)
            {
                $tokenGroup['type'] = $groupType;
                
                $tokenGroup = $analyzer::parse($tokenGroup);
                break;
            }
            
        }
        
        return $tokenGroup;
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
    
    static function eachToken($tokens, $targetToken)
    {
        $allTargetToken = [];
        
        foreach($tokens as $token)
        {
            if($token['token'] == $targetToken)
                $allTargetToken[] = $token;
        }
        
        return $allTargetToken;
    }
    
    
    static function singleOperator($tokens)
    {
        foreach($tokens as $tokenKey => $token)
        {
            $last = isset($tokens[$tokenKey - 1]) ? $tokens[$tokenKey - 1] : false;
            
            
            if($token['token'] == 'T_DECENT')
                if($last)
                    return ['T_SINGLE_OPEATOR' => ['variable'  => $last['match'], 
                                                   'directive' => $token['match']]];
            
            if($token['token'] == 'T_INCREASE')
                if($last)
                    return ['T_SINGLE_OPEATOR' => ['variable'  => $last['match'], 
                                                   'directive' => $token['match']]];
                
            
        }
        
    }
    
    static function multipleOperator()
    {
        
    }
    
  
    
    
    

}
?>