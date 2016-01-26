<?php
ini_set('xdebug.var_display_max_depth', '10'); 

include '../src/avane.php';
include '../src/lex2php.php';
include '../src/parser/condition.php';
include '../src/parser/variable.php';
include '../src/parser.php';
include '../src/lexer.php';


$first = microtime(true);
$avane = new Avane();
$input = 
['
{% if a.b.c.d == e.f.g.h + a + b %}
 <a href="#" class="g-2 g-s alb-photo-single--con" style="background-image: url(\'http://localhost/social2/contents/test/avatar.jpg\')">
            <div class="alb-photo-single--mask">{% endif %}
                <div class="alb-photo-mask--content album">
                    {% foreach users in user %}
                        { user.status }
                    { caris } { variable.love.all.code } { $ok }
                    { caris ? "ok" : "fine" }
                    {% endforeach %}
                </div>
            </div>
        </a>
{% elseif c + d == kk %}
{ asdasd | nl2br }
'];

AvaneLexer::run($input);


var_dump(microtime(true) - $first)
?>