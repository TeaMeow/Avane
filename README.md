<p align="center">
  <img src="http://i.imgur.com/bZsISPS.png"/>
</p>
<p align="center">
  <i>x.</i>
</p>

&nbsp;
 
# Koru [![GitHub release](https://img.shields.io/github/release/TeaMeow/Koru.svg?maxAge=2592000)]() 

Koru 是一個將資料轉換成 stdClass 的資料建構類別，用來更方便地與資料溝通。

&nbsp;

# 特色

1. 支援輸出指定資料。

2. 可僅輸出資料名稱。

3. 可標記資料損毀。

&nbsp;

# 建置狀況

| 服務          | 標籤         |
| ------------- |:-------------|
| Travis CI     | [![Build Status](https://travis-ci.org/TeaMeow/Koru.svg?branch=master)](https://travis-ci.org/TeaMeow/Koru) |
| Caris Events  | [![Build Status](http://drone.caris.events/api/badges/TeaMeow/Koru/status.svg)](http://drone.caris.events/TeaMeow/Koru)      |

&nbsp;

# 教學

我們將教學從 README.md 中切割出來了，

**你可以[在 Gitbook 上閱讀詳細的 Koru 教學](https://yamiodymel.gitbooks.io/koru/content/)**，

甚至是下載成 PDF 檔在任何時候都可以觀看。

&nbsp;

# 範例

你需要先從指定來源建立 Koru。

```php
$data = new Koru::build($_POST);
```

&nbsp;

然後這樣使用你建置後的資料。

```php
$data->username;
```

&nbsp;

# 可參考文件

這裡是幾個可能會啟發你的創意，或者是更有利於你使用 Koru 的連結。

無
