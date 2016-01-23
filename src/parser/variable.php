<?php


class AvaneVariableAnalyzer extends Avane
{
    protected static $validTypes = ['T_OPEN_TAG T_IDENTIFIER T_CLOSE_TAG'                        => 'T_VARIABLE',
                                    'T_OPEN_TAG T_IDENTIFIER T_BETWEEN T_IDENTIFIER T_CLOSE_TAG' => 'T_VARIABLE'];
    
	function validate($combinedToken)
	{
	    foreach(self::$validTypes as $type => $tokenType)
	    {
	        if($type == $combinedToken)
	            exit($combinedToken);
	    }
	    
	}
};


?>