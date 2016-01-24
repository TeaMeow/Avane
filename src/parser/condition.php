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
	    exit(var_dump($tokenGroup));
	}
};


?>