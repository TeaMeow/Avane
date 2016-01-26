<?php

require_once('Parser.php');

class ListParser extends Parser {
    public function ListParser(Lexer $input) {
        parent::__construct($input);
    }
    
    /** list : '[' elements ']' ; // match bracketed list */
    public function rlist() {
        $this->match(ListLexer::LBRACK);
        $this->elements();
        $this->match(ListLexer::RBRACK);
    }
    /** elements : element (',' element)* ; */
    function elements() {
        $this->element();
        while ($this->lookahead->type == ListLexer::COMMA ) {
            $this->match(ListLexer::COMMA);
            $this->element();
        }
    }
    /** element : name | list ; // element is name or nested list */
    function element() {
        if ($this->lookahead->type == ListLexer::NAME ) {
            $this->match(ListLexer::NAME);
        }
        else if ($this->lookahead->type == ListLexer::LBRACK) {
            $this->rlist();
        }
        else {
            throw new Exception("Expecting name or list : Found "  . $this->lookahead);
        }
    }
}

?>