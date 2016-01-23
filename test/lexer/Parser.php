<?php

abstract class Parser {
    public $input;     // from where do we get tokens?
    public $lookahead; // the current lookahead token

    public function Parser(ListLexer $input) {
        $this->input = $input;
        $this->consume();
    }
    
    /** If lookahead token type matches x, consume & return else error */
    public function match($x) {
        if ($this->lookahead->type == $x ) {
            $this->consume();
        } else {
            throw new Exception("Expecting token " .
                                $this->input->getTokenName($x) .
                                ":Found " . $this->lookahead);
        }
    }
    public function consume() {
        $this->lookahead = $this->input->nextToken();
    }
}

?>