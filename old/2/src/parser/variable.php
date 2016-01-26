<?php


class AvaneVariableAnalyzer extends Avane
{
    protected static $validTypes = ['T_OPEN_TAG T_IDENTIFIER T_CLOSE_TAG'                            => 'T_TAG_VARIABLE',
                                    'T_OPEN_TAG T_IDENTIFIER T_BETWEEN T_IDENTIFIER T_CLOSE_TAG'     => 'T_TAG_DIRECTIVE_VARIABLE',
                                    'T_OPEN_TAG T_PHP_IDENTIFIER T_CLOSE_TAG'                        => 'T_TAG_PHP_VARIABLE',
                                    '(?<=T_OPEN_TAG)(?=.*T_DOT)(?!.*(T_QUOTE|T_DOUBLE_QUOTE)).*(?=T_CLOSE_TAG)' => 'T_TAG_CHILD_VARIABLE',
                                    'T_OPEN_TAG .* T_SECONDARY_SEPARATOR .* T_SEPARATOR .* T_CLOSE_TAG' => 'T_TAG_SHORTHAND_CONDITION',
                                    ];
    
	function validate($combinedToken)
	{
	    foreach(self::$validTypes as $type => $tokenType)
	    {
	        if(preg_match('/' . $type . '/', $combinedToken))
	            return $tokenType;
	    }
	    
	    return false;
	    
	}
	
	static function parse($tokenGroup)
	{
	    return self::$tokenGroup['type']($tokenGroup);
	    
	}
	
	
	
	
	
	
	
	
	
	
	static function T_TAG_DIRECTIVE_VARIABLE($tokenGroup)
	{
	    //T_OPEN_TAG T_IDENTIFIER T_BETWEEN T_IDENTIFIER T_CLOSE_TAG
	    
	    $variableName  = AvaneParser::firstToken($tokenGroup['tokens'], 'T_IDENTIFIER')['match'];
	    $directiveName = AvaneParser::nthToken($tokenGroup['tokens'], 2, 'T_IDENTIFIER')['match'];
	    
	    
	    $tokenGroup['phpOutput'] = '<?= $this->get(\''. $variableName . '\', \'' . $directiveName .'\'); ?>';
	    
	    return $tokenGroup;
	}
	
	static function T_TAG_VARIABLE($tokenGroup)
	{
		$variableName  = AvaneParser::firstToken($tokenGroup['tokens'], 'T_IDENTIFIER')['match'];
		
		$tokenGroup['phpOutput'] = '<?= $this->get(\''. $variableName . '\'); ?>';
		
		
		
		return $tokenGroup;
	}
	
	static function T_TAG_PHP_VARIABLE($tokenGroup)
	{
		$variableName  = AvaneParser::firstToken($tokenGroup['tokens'], 'T_PHP_IDENTIFIER')['match'];
		
		$tokenGroup['phpOutput'] = '<?= ' . $variableName . '; ?>';

		return $tokenGroup;
	}
	
	static function T_TAG_CHILD_VARIABLE($tokenGroup)
	{
		$identifiers    = AvaneParser::eachToken($tokenGroup['tokens'], 'T_IDENTIFIER');
		$mainIdentifier = NULL;
		$subIdentifiers = '';
		

		foreach($identifiers as $identifier)
		{
			if(!$mainIdentifier)
			{
				$mainIdentifier = $identifier['match'];
				continue;
			}
			
			$subIdentifiers .= '[\'' . $identifier['match'] . '\']';
		}
		
		$tokenGroup['phpOutput'] = '<?= $this->get(\''. $mainIdentifier . '\')' . $subIdentifiers . '; ?>';
		
		
		
		return $tokenGroup;
	}
	
	static function T_TAG_SHORTHAND_CONDITION($tokenGroup)
	{
		return $tokenGroup;
	}
};


?>