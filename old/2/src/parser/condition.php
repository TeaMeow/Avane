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
		exit(var_dump(self::G_IDENTIFIER(AvaneParser::betweenToken($tokenGroup, 'T_IF', 'T_HELPER_CLOSE_TAG'))));
		preg_match('/^{% if (.*?) %}/', $tokenGroup['match'], $matches);
		
		$calculation = $matches ? $matches[1] : false;
		
		$tokenGroup['phpOutput'] = '<?php if(' . $calculation . '): ?>';
		
		
		return $tokenGroup;
	}
	
	
	static function G_IDENTIFIER($tokenGroup)
	{
		$group = [[]];
		
		foreach($tokenGroup as $tokenKey => $token)
		{
			$next  = isset($tokenGroup[$tokenKey + 1]) ? $tokenGroup[$tokenKey + 1] : null;
            $right = isset($tokenGroup[$tokenKey + 2]) ? $tokenGroup[$tokenKey + 2] : null;
            
            $latest = count($group) - 1 > 0 ? count($group) - 1 : 0;
            
            $group[$latest]['G_IDENTIFIER'][] = $token;
            
          	if($next['token'] != 'T_DOT' && $next['token'] != 'T_IDENTIFIER')
          		array_push($group, []);
            
		}
		
		
		return $group;
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