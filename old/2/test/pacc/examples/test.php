<?php

/**
 * Fills grammar from token stream
 */

class PaccParser
{
    private function _syntax_() {
        $__0__ = TRUE;
        if (($__1__ = $this->_toplevels_()) !== NULL) {
            if (($__2__ = $this->_rules_()) !== NULL) {
                $__0__ = TRUE;
            }
            else {
                $__0__ = NULL;
            }

        }
        else {
            $__0__ = NULL;
        }
        return $__0__;
    }

    private function _toplevels_() {
        $__0__ = TRUE;
        if (($__1__ = $this->_toplevel_()) !== NULL) {
            if (($__2__ = $this->_toplevels_()) !== NULL) {
                $__0__ = TRUE;
            }
            else {
                $__0__ = TRUE;
            }

        }
        else {
            $__0__ = NULL;
        }
        return $__0__;
    }

    private function _optsem_() {
        $__0__ = TRUE;
        if ($this->_currentTokenLexeme() === ';') {
            $__1__ = $this->_currentToken();
            $this->_nextToken();
            $__0__ = TRUE;
        }
        else {
            $__0__ = TRUE;
        }
        return $__0__;
    }

    private function _toplevel_() {
        $__0__ = TRUE;
        if ($this->_currentTokenLexeme() === 'grammar') {
            $__1__ = $this->_currentToken();
            $this->_nextToken();
            if (($__2__ = $this->_backslash_separated_name_()) !== NULL) {
                if (($__3__ = $this->_optsem_()) !== NULL) {
                    $this->grammar_name = $__2__;
                }
                else {
                    $__0__ = NULL;
                }

            }
            else {
                $__0__ = NULL;
            }

        }
        else if ($this->_currentTokenLexeme() === 'option') {
            $__1__ = $this->_currentToken();
            $this->_nextToken();
            if (($__2__ = $this->_options_()) !== NULL) {
                if (($__3__ = $this->_optsem_()) !== NULL) {
                    $__0__ = TRUE;
                }
                else {
                    $__0__ = NULL;
                }

            }
            else {
                $__0__ = NULL;
            }

        }
        else if ($this->_currentTokenLexeme() === '@') {
            $__1__ = $this->_currentToken();
            $this->_nextToken();
            if (($__2__ = $this->_period_separated_name_()) !== NULL) {
                if ($this->_currentTokenLexeme() === '{') {
                    $__3__ = $this->_currentToken();
                    $this->_nextToken();
                    if ($this->_currentTokenType() === self::CODE) {
                        $__4__ = $this->_currentToken();
                        $this->_nextToken();
                        if ($this->_currentTokenLexeme() === '}') {
                            $__5__ = $this->_currentToken();
                            $this->_nextToken();
                            if (($__6__ = $this->_optsem_()) !== NULL) {
                                $this->grammar_options[$__2__] = $__4__->value;
                            }
                            else {
                                $__0__ = NULL;
                            }

                        }
                        else {
                            $__0__ = NULL;
                        }

                    }
                    else {
                        $__0__ = NULL;
                    }

                }
                else {
                    $__0__ = NULL;
                }

            }
            else {
                $__0__ = NULL;
            }

        }
        else {
            $__0__ = NULL;
        }
        return $__0__;
    }

    private function _period_separated_name_() {
        $__0__ = TRUE;
        if ($this->_currentTokenType() === self::ID) {
            $__1__ = $this->_currentToken();
            $this->_nextToken();
            if ($this->_currentTokenLexeme() === '.') {
                $__2__ = $this->_currentToken();
                $this->_nextToken();
                if (($__3__ = $this->_period_separated_name_()) !== NULL) {
                    $__0__ = $__1__->value . '.' . $__3__;
                }
                else {
                    $__0__ = NULL;
                }

            }
            else {
                $__0__ = $__1__->value;
            }

        }
        else {
            $__0__ = NULL;
        }
        return $__0__;
    }

    private function _backslash_separated_name_() {
        $__0__ = TRUE;
        if ($this->_currentTokenType() === self::ID) {
            $__1__ = $this->_currentToken();
            $this->_nextToken();
            if ($this->_currentTokenLexeme() === '\\') {
                $__2__ = $this->_currentToken();
                $this->_nextToken();
                if (($__3__ = $this->_backslash_separated_name_()) !== NULL) {
                    $__0__ = $__1__->value . '\\' . $__3__;
                }
                else {
                    $__0__ = NULL;
                }

            }
            else {
                $__0__ = $__1__->value;
            }

        }
        else {
            $__0__ = NULL;
        }
        return $__0__;
    }

    private function _options_() {
        $__0__ = TRUE;
        if (($__1__ = $this->_single_option_()) !== NULL) {
            $__0__ = TRUE;
        }
        else if ($this->_currentTokenLexeme() === '(') {
            $__1__ = $this->_currentToken();
            $this->_nextToken();
            if (($__2__ = $this->_more_options_()) !== NULL) {
                if ($this->_currentTokenLexeme() === ')') {
                    $__3__ = $this->_currentToken();
                    $this->_nextToken();
                    $__0__ = TRUE;
                }
                else {
                    $__0__ = NULL;
                }

            }
            else {
                $__0__ = NULL;
            }

        }
        else {
            $__0__ = NULL;
        }
        return $__0__;
    }

    private function _more_options_() {
        $__0__ = TRUE;
        if (($__1__ = $this->_single_option_()) !== NULL) {
            if ($this->_currentTokenLexeme() === ';') {
                $__2__ = $this->_currentToken();
                $this->_nextToken();
                if (($__3__ = $this->_more_options_()) !== NULL) {
                    $__0__ = TRUE;
                }
                else {
                    $__0__ = TRUE;
                }

            }
            else {
                $__0__ = TRUE;
            }

        }
        else {
            $__0__ = NULL;
        }
        return $__0__;
    }

    private function _single_option_() {
        $__0__ = TRUE;
        if (($__1__ = $this->_period_separated_name_()) !== NULL) {
            if ($this->_currentTokenLexeme() === '=') {
                $__2__ = $this->_currentToken();
                $this->_nextToken();
                if ($this->_currentTokenType() === self::STRING) {
                    $__3__ = $this->_currentToken();
                    $this->_nextToken();
                    $this->grammar_options[$__1__] = $__3__->value;
                }
                else if ($this->_currentTokenLexeme() === '{') {
                    $__3__ = $this->_currentToken();
                    $this->_nextToken();
                    if ($this->_currentTokenType() === self::CODE) {
                        $__4__ = $this->_currentToken();
                        $this->_nextToken();
                        if ($this->_currentTokenLexeme() === '}') {
                            $__5__ = $this->_currentToken();
                            $this->_nextToken();
                            $this->grammar->options[$__1__] = $__4__->value;
                        }
                        else {
                            $__0__ = NULL;
                        }

                    }
                    else {
                        $__0__ = NULL;
                    }

                }
                else {
                    $__0__ = NULL;
                }

            }
            else {
                $__0__ = NULL;
            }

        }
        else {
            $__0__ = NULL;
        }
        return $__0__;
    }

    private function _rules_() {
        $__0__ = TRUE;
        if (($__1__ = $this->_rule_()) !== NULL) {
            if (($__2__ = $this->_rules_()) !== NULL) {
                $__0__ = TRUE;
            }
            else {
                $__0__ = TRUE;
            }

        }
        else {
            $__0__ = NULL;
        }
        return $__0__;
    }

    private function _rule_() {
        $__0__ = TRUE;
        if ($this->_currentTokenType() === self::ID) {
            $__1__ = $this->_currentToken();
            $this->_nextToken();
            if ($this->_currentTokenLexeme() === ':') {
                $__2__ = $this->_currentToken();
                $this->_nextToken();
                if (($__3__ = $this->_expressions_()) !== NULL) {
                    if ($this->_currentTokenLexeme() === ';') {
                        $__4__ = $this->_currentToken();
                        $this->_nextToken();
                        $name = new PaccNonterminal($__1__->value);
                        if (($found = $this->nonterminals->find($name)) !== NULL) { $name = $found; }
                        else { $this->nonterminals->add($name); }
                        
                        if ($this->start === NULL) {
                        $this->start = $name;
                        }
                        
                        foreach ($__3__ as $expression) {
                        list($terms, $code) = $expression;
                        $production = new PaccProduction($name, $terms, $code);
                        if (($found = $this->productions->find($production)) === NULL) {
                        $this->productions->add($production);
                        }
                        }
                    }
                    else {
                        $__0__ = NULL;
                    }

                }
                else {
                    $__0__ = NULL;
                }

            }
            else {
                $__0__ = NULL;
            }

        }
        else {
            $__0__ = NULL;
        }
        return $__0__;
    }

    private function _expressions_() {
        $__0__ = TRUE;
        if (($__1__ = $this->_expression_()) !== NULL) {
            if ($this->_currentTokenLexeme() === '|') {
                $__2__ = $this->_currentToken();
                $this->_nextToken();
                if (($__3__ = $this->_expressions_()) !== NULL) {
                    $__0__ = array_merge(array($__1__), $__3__);
                }
                else {
                    $__0__ = NULL;
                }

            }
            else {
                $__0__ = array($__1__);
            }

        }
        else {
            $__0__ = NULL;
        }
        return $__0__;
    }

    private function _expression_() {
        $__0__ = TRUE;
        if (($__1__ = $this->_terms_or_nothing_()) !== NULL) {
            if ($this->_currentTokenLexeme() === '{') {
                $__2__ = $this->_currentToken();
                $this->_nextToken();
                if ($this->_currentTokenType() === self::CODE) {
                    $__3__ = $this->_currentToken();
                    $this->_nextToken();
                    if ($this->_currentTokenLexeme() === '}') {
                        $__4__ = $this->_currentToken();
                        $this->_nextToken();
                        $__0__ = array($__1__, $__3__->value);
                    }
                    else {
                        $__0__ = NULL;
                    }

                }
                else {
                    $__0__ = NULL;
                }

            }
            else {
                $__0__ = array($__1__, NULL);
            }

        }
        else {
            $__0__ = NULL;
        }
        return $__0__;
    }

    private function _terms_or_nothing_() {
        $__0__ = TRUE;
        if (($__1__ = $this->_terms_()) !== NULL) {
            $__0__ = $__1__;
        }
        else {
            $__0__ = array();
        }
        return $__0__;
    }

    private function _terms_() {
        $__0__ = TRUE;
        if (($__1__ = $this->_term_()) !== NULL) {
            if (($__2__ = $this->_terms_()) !== NULL) {
                $__0__ = array_merge(array($__1__), $__2__);
            }
            else {
                $__0__ = array($__1__);
            }

        }
        else {
            $__0__ = NULL;
        }
        return $__0__;
    }

    private function _term_() {
        $__0__ = TRUE;
        if ($this->_currentTokenType() === self::ID) {
            $__1__ = $this->_currentToken();
            $this->_nextToken();
            if (ord($__1__->value[0]) >= 65 /* A */ && ord($__1__->value[0]) <= 90 /* Z */) { // terminal
            $term = new PaccTerminal($__1__->value, $__1__->value, NULL);
            if (($found = $this->terminals->find($term)) !== NULL) { $term = $found; }
            else { $this->terminals->add($term); }
            
            } else { // nonterminal
            $term = new PaccNonterminal($__1__->value);
            if (($found = $this->nonterminals->find($term)) !== NULL) { $term = $found; }
            else { $this->nonterminals->add($term); }
            }
            
            $__0__ = $term;
        }
        else if ($this->_currentTokenType() === self::STRING) {
            $__1__ = $this->_currentToken();
            $this->_nextToken();
            $term = new PaccTerminal($__1__->value, NULL, $__1__->value);
            if (($found = $this->terminals->find($term)) !== NULL) { $term = $found; }
            else { $this->terminals->add($term); }
            
            $__0__ = $term;
        }
        else {
            $__0__ = NULL;
        }
        return $__0__;
    }

    private function doParse() {
        return $this->_syntax_();
    }
    private function _currentToken() {

    return $this->stream->current();

    }

    private function _currentTokenType() {

    return get_class($this->stream->current());

    }

    private function _currentTokenLexeme() {

    return $this->stream->current()->lexeme;

    }

    private function _nextToken() {

    return $this->stream->next();

    }




    const
        ID = 'PaccIdToken',
        STRING = 'PaccStringToken',
        CODE = 'PaccCodeToken';

    /**
     * Token stream
     * @var PaccTokenStream
     */
    private $stream;

    /**
     * @var PaccGrammar
     */
    private $grammar;

    /**
     * @var string
     */
    private $grammar_name;

    /**
     * @var array
     */
    private $grammar_options = array();

    /**
     * @var PaccSet<PaccNonterminal>
     */
    private $nonterminals;

    /**
     * @var PaccSet<PaccTerminal>
     */
    private $terminals;

    /**
     * @var PaccSet<PaccProduction>
     */
    private $productions;

    /**
     * Start symbol
     * @var PaccNonterminal
     */
    private $start;

    /**
     * Initializes instance
     * @param PaccTokenStream
     */
    public function __construct(PaccTokenStream $stream)
    {
        $this->stream = $stream;
        $this->terminals = new PaccSet('PaccTerminal');
        $this->nonterminals = new PaccSet('PaccNonterminal');
        $this->productions = new PaccSet('PaccProduction');
    }

    /**
     * Parse
     * @return PaccGrammar
     */
    public function parse()
    {
        if ($this->grammar === NULL) {
            $this->doParse();
            $this->grammar = new PaccGrammar($this->nonterminals, $this->terminals, $this->productions, $this->start);
            $this->grammar->name = $this->grammar_name;
            $this->grammar->options = $this->grammar_options;
        }

        return $this->grammar;
    }




}


$a = new PaccParser();
$a->parse();
?>