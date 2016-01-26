<?php

class Token {
    public $type;
    public $text;
    
    public function Token($type, $text) {
        $this->type = $type;
        $this->text = $text;
    }
    
    public function __toString() {
        $tname = ListLexer::$tokenNames[$this->type];
        return "<'" . $this->text . "'," . $tname . ">";
    }
}

?>