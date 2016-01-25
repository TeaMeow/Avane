<?php


class AvaneConditionAnalyzer extends Avane
{
    protected static $validTypes = ['T_HELPER_OPEN_TAG T_IF .* T_HELPER_CLOSE_TAG'     => 'T_CONDITION_IF',
                                    'T_HELPER_OPEN_TAG T_ELSEIF .* T_HELPER_CLOSE_TAG' => 'T_CONDITION_ELSEIF',
                                    'T_HELPER_OPEN_TAG T_ELSE T_HELPER_CLOSE_TAG'      => 'T_CONDITION_ELSE'];
    
	static function validate($combinedToken)
	{
	    foreach(self::$validTypes as $type => $tokenType)
	    {
	        if(preg_match('/^' . $type . '$/', $combinedToken))
	            return $tokenType;
	    }
	    
	    return false;
	    
	}
	
	
	static function parse($tokenGroup)
	{
	    switch($tokenGroup['type'])
	    {
	        case 'T_CONDITION_IF':
	            return self::T_CONDITION_IF($tokenGroup);
	            break;
	            
	        case 'T_CONDITION_ELSEIF':
	            return self::T_CONDITION_ELSEIF($tokenGroup);
	            break;
	        
	        case 'T_CONDITION_ELSE':
	        	return self::T_CONDITION_ELSE($tokenGroup);
	        	break;
	    }
	}
	
	static function T_CONDITION_IF($tokenGroup)
	{
		preg_match('/^{% if (.*?) %}/', $tokenGroup['match'], $matches);
		
		$calculation = $matches ? $matches[1] : false;
		
		$tokenGroup['phpOutput'] = '<?php if(' . $calculation . '): ?>';
		
		
		return $tokenGroup;
	}
	
	static function T_CONDITION_ELSEIF($tokenGroup)
	{
		preg_match('/^{% elseif (.*?) %}/', $tokenGroup['match'], $matches);
		
		$calculation = $matches ? $matches[1] : false;
		
		$tokenGroup['phpOutput'] = '<?php elseif(' . $calculation . '): ?>';
		
		
		return $tokenGroup;
	}
	
	static function T_CONDITION_ELSE($tokenGroup)
	{
		
	}
};


?>