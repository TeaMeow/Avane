<p align="center">
  <img src="http://imgur.com/ANJiiSB.png"/>
</p>
<p align="center">
  <i>It will be different if you stand behind.</i>
</p>

&nbsp;

# Avane [![GitHub release](https://img.shields.io/github/release/TeaMeow/Avane.svg?maxAge=2592000)](https://github.com/TeaMeow/Avane/releases) [![Coverage Status](https://coveralls.io/repos/github/TeaMeow/Avane/badge.svg?branch=master)](https://coveralls.io/github/TeaMeow/Avane?branch=master)

Avane is a template engine written in PHP. It supports random CSS style names,

and packing JS files.

&nbsp;

# Features

1. PJAX - single page technology

2. Less PHP codes.

3. Multiple templates.

4. Supports packing JS and CSS files.

5. Compiling Coffee and Sass files automatically.

6. Jade-like coding style, though you are still able to write templates in HTML.

7. Configure your templates in YAML configuration files, without YAML modules.

&nbsp;

# Build status

| Service          | Status         |
| ------------- |:-------------|
| Travis CI     | [![Build Status](https://travis-ci.org/TeaMeow/Avane.svg?branch=master)](https://travis-ci.org/TeaMeow/Avane) |
| Caris Events  | [![Build Status](http://drone.caris.events/api/badges/TeaMeow/Avane/status.svg)](http://drone.caris.events/TeaMeow/Avane)      |

&nbsp;

# Tutorials

We have moved the tutorials to Gitbook.

**You can [read a complete Avane document on Gitbook](https://yamiodymel.gitbooks.io/avane/content/)**.

Or even download the document as PDF files to read them at anytime.

&nbsp;

# Examples

Construct an Avane class, and pass the path of templates directory.

```php
$avane = new Avane\Main('default');
```

&nbsp;

Create a template like below and save it to `default/tpls/homepage.jade`.

```php
div
    Hey, I'm #{$name}！
```

&nbsp;

Next, create a Jade file named `index.jade` and copy the code below into it.

```php
$avane = new Avane\Main('default');

$avane->render('homepage', ['name' => 'Yami Odymel']);
```

&nbsp;

Finally, access `index.php` with your browser and you should get this:

```html
<div>Hey, I'm Yami Odymel!</div>
```

&nbsp;

# References

Get inspired, use Avane better by reading these.

[Writing a simple lexer in PHP](http://nitschinger.at/Writing-a-simple-lexer-in-PHP/)

[超简单实用的php 模板引擎](http://www.cnphp.info/simple-php-template-engine.html)

[自制php模板引擎第二版](http://www.cnphp.info/simple-php-template-engine-version-2.html)

[Latte: amazing template engine for PHP](https://latte.nette.org/)

[Roll Your Own Templating System in PHP](http://code.tutsplus.com/tutorials/roll-your-own-templating-system-in-php--net-16596)

[Creating a Simple Template Engine with OO PHP.](http://ianburris.com/tutorials/oophp-template-engine/)

[Simple PHP Template Engine](http://chadminick.com/articles/simple-php-template-engine.html#sthash.miLYug6M.dpbs)

[Creating your own template engine in JavaScript: part 1](http://www.angrycoding.com/2012/03/creating-your-own-template-engine-in.html)

[Nunjucks](https://mozilla.github.io/nunjucks/cn/templating.html)

[How to Use PHP instead of Twig for Templates](http://symfony.com/doc/current/cookbook/templating/PHP.html)

[Dust PHP](http://cretz.github.io/dust-php/)

[TWIG](http://twig.sensiolabs.org/doc/tags/for.html)

[Getting Started With PHP Templating](https://www.smashingmagazine.com/2011/10/getting-started-with-php-templating/)

[Templating Engines in PHP](http://fabien.potencier.org/templating-engines-in-php.html)

[Talesoft/tale-jade](https://github.com/Talesoft/tale-jade)
