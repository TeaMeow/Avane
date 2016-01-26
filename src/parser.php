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
    
    
    static function variable($tokens)
    {
        $allDots = [[]];
        $cleaned = [];
        $root = null;
        
        foreach($tokens['tokens'] as $tokenKey => $token)
        {
            
            
            $prev  = isset($tokens['tokens'][$tokenKey - 1]) ? $tokens['tokens'][$tokenKey - 1] : null;
            $next  = isset($tokens['tokens'][$tokenKey + 1]) ? $tokens['tokens'][$tokenKey + 1] : null;
            $right = isset($tokens['tokens'][$tokenKey + 2]) ? $tokens['tokens'][$tokenKey + 2] : null;

            
            $latest = count($allDots) - 1 > 0 ? count($allDots) - 1 : 0;
            
            if(!$root && $token['token'] == 'T_IDENTIFIER')
            {
                $root = $token;
                $allDots[$latest]['G_IDENTIFIER'][] = self::simplizeToken($token);
                continue;
            }
                
            
            
            
            if($token['token'] == 'T_IDENTIFIER' && $root)
                $allDots[$latest]['G_IDENTIFIER'][] = self::simplizeToken($token);
                
            if(($token['token'] == 'T_IDENTIFIER' && $prev['token'] == 'T_DOT' && $next['token'] != 'T_DOT') || 
               ($token['token'] == 'T_IDENTIFIER' && $next['token'] != 'T_DOT'))
            {
                echo $token['match'];
                array_push($allDots, []);
            }
        }
        
        
     array_pop($allDots);
     
exit(var_dump(json_encode($allDots)));

        
      
        return $allDots;
        
    

    }
    
    
    static function simplizeToken($token)
    {
        return [$token['token'] => $token['match']];
    }
    
    
    static function firstToken($tokens, $targetToken)
    {
        return self::nthToken($tokens, 1, $targetToken);
    }
    
    
    static function betweenToken($tokens, $startToken, $endToken, $targetToken=null)
    {
        
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