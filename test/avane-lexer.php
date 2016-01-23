<?php
xdebug_disable();
include '../src/avane.php';
include '../src/lexer.php';

$first = microtime(true);
$avane = new Avane();
$input = 
['
{% if a + a == b %}
 <a href="#" class="g-2 g-s alb-photo-single--con" style="background-image: url(\'http://localhost/social2/contents/test/avatar.jpg\')">
            <div class="alb-photo-single--mask">
                <div class="alb-photo-mask--content album">
                    動漫
                </div>
            </div>
        </a>
{% elseif c + d == kk %}
{ asdasd }
'];

AvaneLexer::run($input);


var_dump(microtime(true) - $frist)
?>