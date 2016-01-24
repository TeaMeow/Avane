<?php


class AvaneVariableAnalyzer extends Avane
{
    protected static $validTypes = ['T_OPEN_TAG T_IDENTIFIER T_CLOSE_TAG'                        => 'T_VARIABLE',
                                    'T_OPEN_TAG T_IDENTIFIER T_BETWEEN T_IDENTIFIER T_CLOSE_TAG' => 'T_VARIABLE_DIRECTIVE'];
    
	function validate($combinedToken)
	{
	    foreach(self::$validTypes as $type => $tokenType)
	    {
	        if($type == $combinedToken)
	            return $tokenType;
	    }
	    
	    return false;
	    
	}
	
	static function parse($tokenGroup)
	{
	    switch($tokenGroup['type'])
	    {
	        case 'T_VARIABLE':
	            self::T_VARIABLE($tokenGroup);
	            break;
	            
	        case 'T_VARIABLE_DIRECTIVE':
	            self::T_VARIABLE_DIRECTIVE($tokenGroup);
	            break;
	    }
	   
	   
	    
	}
	
	static function T_VARIABLE_DIRECTIVE($tokenGroup)
	{
	    //T_OPEN_TAG T_IDENTIFIER T_BETWEEN T_IDENTIFIER T_CLOSE_TAG
	    
	    $variableName  = AvaneParser::firstToken($tokenGroup['tokens'], 'T_IDENTIFIER')['match'];
	    $directiveName = AvaneParser::nthToken($tokenGroup['tokens'], 2, 'T_IDENTIFIER')['match'];
	    
	    
	    //<?= $this->Get(variable, directive); 
	    
	    exit(var_dump(['T_VARIABLE_DIRECTIVE' => ['variable' => $variableName,
	                                              'directive' => $directiveName,
	                                              'phpOutput' => '<?= $this->get(\''. $variableName . '\', \'' . $directiveName .'\'); ?>']]));
	}
};


?>