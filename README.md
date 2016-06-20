<p align="center">
  <img src="http://imgur.com/ANJiiSB.png"/>
</p>
<p align="center">
  <i>It will be different if you stand behind.</i>
</p>

&nbsp;
 
# Avane [![GitHub release](https://img.shields.io/github/release/TeaMeow/Avane.svg?maxAge=2592000)]() 

亞凡芽是基於 PHP 的一套模板引擎，其功能支援隨機 CSS 樣式名稱，

同時整合 JS 檔案。

&nbsp;

# 特色

1. 支援 PJAX（換網頁不重整）

2. 減少撰寫 PHP 程式的次數。

3. 支援多個模板。

4. 支援整合 JS 和 CSS 檔案。

5. 支援自動編譯 Coffee、Sass。

6. 採用類似 Jade 的標籤，但你仍可以使用 HTML 撰寫。

&nbsp;

# 建置狀況

| 服務          | 標籤         |
| ------------- |:-------------|
| Travis CI     | [![Build Status](https://travis-ci.org/TeaMeow/Avane.svg?branch=master)](https://travis-ci.org/TeaMeow/Avane) |
| Caris Events  | [![Build Status](http://drone.caris.events/api/badges/TeaMeow/Avane/status.svg)](http://drone.caris.events/TeaMeow/Avane)      |

&nbsp;

# 教學

我們將教學從 README.md 中切割出來了，

**你可以[在 Gitbook 上閱讀詳細的亞凡芽教學](https://yamiodymel.gitbooks.io/avane/content/)**，

甚至是下載成 PDF 檔在任何時候都可以觀看。

&nbsp;

# 範例

你需要先初始化亞凡芽，並且傳入一個模板資料夾的路徑。

```php
$avane = new Avane\Main('default');
```

&nbsp;

然後撰寫模板。

```php
div
    嗨，我是 #{$name}！
```

好了，然後我們把它存入 `default/tpls/homepage.jade`。

&nbsp;

接下來假設我們有個 `index.jade`，而這是他的內容。

```php
$avane = new Avane\Main('default');

$avane->render('homepage', ['name' => '小安']);
```

&nbsp;

接下來透過你的瀏覽器檢視 `index.php`，會得到下列結果。

```html
<div>嗨，我是 小安！</div>
```

&nbsp;

# 可參考文件

這裡是幾個可能會啟發你的創意，或者是更有利於你使用亞凡芽的連結。

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
