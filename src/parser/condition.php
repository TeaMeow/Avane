<?php


class AvaneConditionAnalyzer extends Avane
{
    protected static $validTypes = ['T_HELPER_OPEN_TAG T_IF .* T_HELPER_CLOSE_TAG'     => 'T_TAG_IF',
                                    'T_HELPER_OPEN_TAG T_ELSEIF .* T_HELPER_CLOSE_TAG' => 'T_TAG_ELSEIF',
                                    'T_HELPER_OPEN_TAG T_ENDIF T_HELPER_CLOSE_TAG'     => 'T_TAG_ENDIF',
                                    'T_HELPER_OPEN_TAG T_ELSE T_HELPER_CLOSE_TAG'      => 'T_TAG_ELSE'];
    
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
		return self::$tokenGroup['type']($tokenGroup);
		
	}
	
	static function T_TAG_IF($tokenGroup)
	{
		exit(var_dump(AvaneParser::variable($tokenGroup)));
		preg_match('/^{% if (.*?) %}/', $tokenGroup['match'], $matches);
		
		$calculation = $matches ? $matches[1] : false;
		
		$tokenGroup['phpOutput'] = '<?php if(' . $calculation . '): ?>';
		
		
		return $tokenGroup;
	}
	
	static function T_TAG_ELSEIF($tokenGroup)
	{
		preg_match('/^{% elseif (.*?) %}/', $tokenGroup['match'], $matches);
		
		$calculation = $matches ? $matches[1] : false;
		
		$tokenGroup['phpOutput'] = '<?php elseif(' . $calculation . '): ?>';
		
		
		return $tokenGroup;
	}
	
	static function T_TAG_ELSE($tokenGroup)
	{
		$tokenGroup['phpOutput'] = '<?php else: ?>';

		return $tokenGroup;
	}
	
	static function T_TAG_ENDIF($tokenGroup)
	{
		$tokenGroup['phpOutput'] = '<?php endif; ?>';

		return $tokenGroup;
	}
};


?>