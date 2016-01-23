<?php

class AvaneTagVariable extends Avane
{
	public $period = 1;
	private $start = 0;
	
	public function check(){
		if($this->start === 0) $this->start = time();
		if(SSEUtils::time_mod($this->start,$this->period) == 0) return true;
		else return false;
	}
	public function update(){
		return;
	}
};

?>