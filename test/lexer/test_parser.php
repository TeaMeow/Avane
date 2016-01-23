<?php

require_once('ListLexer.php');
require_once('Token.php');
require_once('ListParser.php');


$input = '[ a, b, c, d]';
$lexer = new ListLexer($input);
$parser = new ListParser($lexer);
$parser->rlist(); // begin parsing at rule list

?>