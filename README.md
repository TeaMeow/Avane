<p align="center">
  <img src="http://imgur.com/ANJiiSB.png"/>
</p>
<p align="center">
  <i>x.</i>
</p>

&nbsp;

# Avane
亞凡芽是基於 PHP 的一套模板引擎，其功能支援隨機 CSS 樣式名稱，

同時整合 JS 檔案。

&nbsp; 

# 特色

1. 支援 PJAX（換網頁不重整）

2. 減少撰寫 PHP 程式的次數。 

3. 支援多重模板主題。 

4. 支援整合 JS 和 CSS 檔案。

5. 支援隨機 CSS 樣式名稱編譯。 


&nbsp; 

# 索引

1. [範例](#範例)

2. [初始化](#初始化)
  
  * [設置模板主題](#設置模板主題)

3. [輸出](#輸出)

  * [只取得內容而不輸出](#只取得內容而不輸出)
  
  * [傳入模板原始碼取得編譯後的內容](#傳入模板原始碼取得編譯後的內容)

4. [模板資料夾結構](#模板資料夾結構)

5. [模板標籤](#模板標籤)

  * [目前支援度](#目前支援度)
  
  * [變數](#變數)
  
    * [修飾詞](#修飾詞)
    
    * [運算子](#運算子)
    
  * [條件式：if, elseif, else, endif](#條件式if-elseif-else-endif)
  
    * [縮寫](#縮寫)
    
  * [迴圈：for, foreach, while, do while](#迴圈for-foreach-while-do-while)
  
    * [for](#for)
    
    * [foreach](#foreach)
   
    * [while](#while)
    
    * [迴圈變數](#迴圈變數)
    
  * [引用模板：include](#引用模板include)
   
  * [匯入：import](#匯入import)
   
  * [保留字](#保留字)

6. [亞凡芽構造](#亞凡芽構造)
 
7. [可參考文件](#可參考文件)

&nbsp; 

# 範例

你可以透過下列方式讀取一個模板。

```php
$avane = new Avane();

$avane->header('相簿') // 設定標題為「相簿」
      ->load('album')  // 讀取名為 album 的模板檔案
      ->footer();      // 讀取頁腳
```

&nbsp; 

# 初始化

你需要先初始化亞凡芽，之後才能對它進行一些設置。

```php
$avane = new Avane();
```

&nbsp; 

## 設置模板主題

亞凡芽支援多個模板主題，在沒有設置模板主題的情況下，會指向 default 這個主題。

```php
->setTemplate('模板資料夾名稱')
```

&nbsp; 

# 輸出

要輸出亞凡芽成為一個網頁內容，請透過下列方式，可以順便在輸出時傳入變數給予該模板。

```php
$avane->header('網頁標題', ['變數1' => 'Caris', 
                           '變數2' => 'Dalan!'])
      ->load('模板名稱')
      ->footer();
```

&nbsp; 

## 只取得內容而不輸出

有時候你可能想要取得輸出內容**而不是將它展現在網頁上**，這個時候，請透過 `fetch()`，

請注意這個時候 `header()` 和 `footer()` 將會不管用。

```php
$content = $avane->fetch(模板名稱, 變數陣列);
```

&nbsp; 

## 傳入模板原始碼取得編譯後的內容

這個方法和 `fetch()` 略微相同，其差異在於 `rawFetch()` 編譯的來源**不是來自一個模板檔案**，

而是將傳入的文字編譯成模板，然後回傳其結果而不是輸出。

```php
$content = $avane->rawFetch(模板內容, 變數陣列);
```

&nbsp; 

# 模板資料夾結構

亞凡芽支援多個不同模板資料夾（你也可以稱呼他們為佈景），

很不幸的是，你必須按照這個資料夾結構，否則亞凡芽沒辦法正確執行。

```
templates
└── default          // 模板名稱
    ├── compiled     // 編譯後的模板
    ├── scripts      // 放置 JavaScript 腳本
    ├── styles       // 放置 CSS 樣式表
    ├── tpls         // 各式各樣的模板放在這
    │
    ├── footer.php   // 頁腳
    ├── header.php   // 標頭
    └── variable.php // 此模板的變數設置
```

&nbsp;

## variable.php

&nbsp;

# 模板標籤

模板標籤是亞凡芽裡最重要的一部份，模板標籤省下了你撰寫 PHP 程式的時間。

標籤分為兩類：

1. **直式標籤**：這個標籤通常是用來輸出變數內容用的，例如 { name }。

2. **輔助標籤**：這個標籤通常是一種函式，例如 {% if %} 或 {* 註釋 *}。

&nbsp; 

## 目前支援度

- [x] 變數

  - [x] 一般變數 `{ var }`

  - [x] 陣列變數 `{ var.child }`

  - [x] 修飾詞變數 `{ var | filter }`

  - [ ] 運算子變數 `{ var++ }`
  
- [x] 條件式 

  - [x] 一般條件式 `{% if %}`, `{% elseif %}`, `{% else %}`

  - [x] 縮寫條件式 `{ if ? true : false }`
  
  - [x] 輸出條件式 `{ if >> true : false}` 
  
- [ ] 迴圈

  - [ ] For `{% for %}`
  
  - [x] Foreach
    
    - [ ] 帶組鍵 `{% foreach vars as var %}`
  
    - [x] 單一 `{% foreach as var %}`
  
  - [x] While `{% while %}`
  
  - [ ] 索引變數 `{% loop.index %}` 

- [x] 引用模板 `{% include %}`

- [x] 匯入 `{% import %}`


&nbsp; 

## 變數

在亞凡芽裡要輸出一個變數是十分簡單地，透過 `{ 變數名稱 }` 即可。

```html
<span>你好！我是 { name } 喔！</span>
```

&nbsp; 

倘若你要輸出一個 PHP 變數而不是亞凡芽變數，則在變數名稱前加上金錢符號。

```html
<span>為什麼我會知道？不是因為我叫 Trivago，而是因為我是 {$name}。</span>
```

&nbsp; 

### 修飾詞

修飾詞是對於一個變數的修飾，在其他程式可能稱為「過濾器」，實際上就是對一個變數的內容進行更動

例如 `nl2br` 會將 `\n` 轉換成 `<br>`，而 `nl2br` 就是修飾詞。

下列方法將會自動替 content 變數套用 nl2br 函式，更多修飾詞請參考稍後的更多說明。

```html
<div> { content | nl2br } </div>
```

&nbsp; 

nl2br, lower, upper, escape, unescape, strlen, truncate

&nbsp; 

### 運算子

一個變數可以透過像是 `{ i++ }` 來達到在模板中加減乘除的作用。

目前支援的有：

i++, i--

&nbsp; 

## 條件式：if, elseif, else, endif

透過 `{% if %}`, `{% elseif %}`, `{% else %}`, `{% endif %}` 來進行條件式的判斷。

```php
{% if author == 'Caris' %}
  <span>我是 Caris 喔！</span>
{% elseif author == 'Iknore' %}
  <span>其實我是 Iknore！</span>
{% else %}
  <span>或者我誰都不是。</span>
{% endif %}
```

&nbsp; 

### 縮寫

同時，亞凡芽也支援條件式的縮寫，像是：`{條件 ? True : False}`。

```php
<span> { isLoggedIn ? '已經登入' : '尚未登入' } </span>
```

&nbsp; 

如果你要輸出的是字串而非變數，而又希望省略雙引號，請透過 `{條件 >> True : False}`

```php
<span> { isLoggedIn >> 已登入 : 尚未登入 } </span>
```

&nbsp; 

**別忘記！**你也可以在其中混入 PHP 變數，只要變數前有金錢符號的都算是 PHP 變數喔！

```php
<span> { author == $Author ? '是同一個作者' : '非相同作者' } </span>
```

&nbsp; 

## 迴圈：for, foreach, while, do while

&nbsp; 

### for

透過 `{% for 索引 in 陣列 %}` 來進行基本 for 迴圈的運用。

```php
<ul>
  {% for i as users %}
    <li> { users.i } </li>
  {% endfor % }
</ul>
```

&nbsp; 

### foreach

```php
<ul>
  {% foreach users as user %}
    <li> { user.i } </li>
  {% endforeach % }
</ul>
```

&nbsp; 

### while

```php
<ul>
  {% while i < 10 %}
    <li> { i } </li>
    {i++}
  {% endwhile % }
</ul>
```

&nbsp; 

### 迴圈變數

你可以透過 `loop` 變數來取得目前迴圈的狀況。

| 變數名稱       | 用途                                        |
|----------------|---------------------------------------------|
| loop.index     | 迴圈目前的索引（由 1 開始算起）。           |
| loop.index0    | 迴圈目前的索引（由 0 開始算起）。           |
| loop.revindex  | 迴圈由最後數到目前的索引（由 1 開始算起。） |
| loop.revindex0 | 迴圈由最後數到目前的索引（由 0 開始算起。） |
| loop.first     | 回傳 True 如果目前是迴圈中第一個資料。      |
| loop.last      | 回傳 True 如果目前是迴圈中最後一個資料。    |
| loop.length    | 迴圈總共的內容長度。                        |
| loop.even      | 回傳 True 如果目前索引是偶數。              |
| loop.odd       | 回傳 True 如果目前索引是奇數。              |


例如像這樣在迴圈內 ..

```
{% for users as user %}
  { loop.index }
{% /for %}
```

&nbsp; 

## 引用模板：include

你可以透過 `{% include 模板名稱 %}` 方式，在 A 模板載入 B 模板。

```php
{% include header %}
```

&nbsp; 

## 匯入：import

你可以透過 `{% import css %}` 來匯入你所設定的 CSS 或者 JS 檔案，

此舉會 `echo` 許多 `<link>` 或是 `<script>` 來引用檔案。

```php
{% include css %}
{% include js %}
```

&nbsp; 

## 保留字

在亞凡芽中有幾個保留字，是設計於特殊用途的，你不應該在定義變數名稱時使用到他們。

1. loop
2. null
3. false
4. true

&nbsp; 

# 亞凡芽構造

亞凡芽裡有詞語分析器和解析器，這些是只有進階工程師需要知道的，因此**一般讀者可略過此部分**。

## ::AvaneLexer - 詞語分析器
## ::AvaneParser - 解譯器
### ::AvaneAnalyzer - 分析器
#### ::AvaneValidator - 模狀器
#### ::AvaneCombiner - 組合器

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
