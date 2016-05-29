<?php
//.atoum.php

use mageekguy\atoum;
use mageekguy\atoum\reports;

$coveralls = new reports\asynchronous\coveralls('src', getenv('COVERALLS_REPO_TOKEN'));
$defaultFinder = $coveralls->getBranchFinder();
$travis = getenv('TRAVIS');
if ($travis)
{
	$script->addDefaultReport();
	$coverallsToken = getenv('COVERALLS_REPO_TOKEN');
	if ($coverallsToken)
	{
		$coverallsReport = new reports\asynchronous\coveralls('classes', $coverallsToken);
		$defaultFinder = $coverallsReport->getBranchFinder();
		$coverallsReport
			->setBranchFinder(function() use ($defaultFinder) {
					if (($branch = getenv('TRAVIS_BRANCH')) === false)
					{
						$branch = $defaultFinder();
					}
					return $branch;
				}
			)
			->setServiceName('travis-ci')
			->setServiceJobId(getenv('TRAVIS_JOB_ID'))
			->addDefaultWriter()
		;
		$runner->addReport($coverallsReport);
	}
}
;?>