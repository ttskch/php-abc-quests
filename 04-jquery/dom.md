# jQuery で DOM を操作してみる

では、実際に JavaScript を使って HTML を動的に変化させてみましょう。

## DOM

その前に [DOM](http://ja.wikipedia.org/wiki/Document_Object_Model)（ドム）について簡単に理解しておく必要があります。

DOM とは、HTML 文書をプログラムから操作するための API です。JavaScript のプログラムから DOM を使って HTML を操作することで、ローカルの HTML を動的に変化させることができます。

サーバからダウンロードした HTML のソースコードは、ブラウザによって DOM で扱える状態（**DOM ツリー** と呼びます）にパースされ、ただの文字列だった HTML が、タグの一つ一つや各タグの属性など様々な単位で操作可能な状態になります。

この DOM ツリーを JavaScript から操作してブラウザのレンダリング結果を変更させるわけです。

### DOM ツリーを覗いてみる

いきなり DOM ツリーと言われてもあまりピンと来ないと思うので、実際にブラウザ上で DOM ツリーを覗いてみましょう。

Google Chrome でどこか適当なページを開いてから、デベロッパーツールを開いて `Elements` タブを選択してみてください。
HTML のソースコードがツリー状に表示されましたね。（カーソルキーの左右でツリーの開閉ができます）

これが、HTML コードをパースして構築された DOM ツリーです。

よく混同しがちですが、右クリック → `ページのソースを表示` で表示されるソースコードとは内容が異なるので注意してください。

* `ページのソースを表示` で表示されるのは、サーバから送られてきた HTML コードの文字列
* デベロッパーツールの `Elements` タブに表示されているのは、その HTML をパースして得られた DOM ツリー

です。

なお、DOM ツリーから特定の要素を見つけ出すのが面倒な場合は、ページ上の調べたい要素の上で右クリック → `要素の検証` で、その要素をにフォーカスした状態で DOM ツリーを表示することができます。

また、この DOM ツリーはただ見るだけではなく手動で編集することもできます。編集したい箇所をダブルクリックすれば編集状態になりますので、色々いじって遊んでみると DOM についての理解が深まるのではないかと思います。

## 実際に動かしてみる

では実際に動かしてみましょう。

```
~/workspace/php-abc-quests/practices/04/hello-dom.html
```

というファイルを作成して、以下のようなコードを書いてみてください。

```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>test</title>
</head>
<body>
<p id="target">ここが変更されます</p>

<script type="text/javascript">
    function modify() {
        // 'target' という id の要素を見つけて、その内容を '変更されました！' にする.
        document.getElementById('target').textContent = '変更されました！';
    }
    
    // 1 秒後に modify を実行する.
    setTimeout(modify, 1000);
</script>
</body>
</html>
```

保存したら、ブラウザで [http://localhost/php-abc-quests/practices/04/hello-dom.html](http://localhost/php-abc-quests/practices/04/hello-dom.html) にアクセスしてみてください。

ページを開いた 1 秒後に `<p>` タグ内のテキストが変更されますね。これが JavaScript による DOM 操作です。

### 解説

まず、HTML 上の `<p>` 要素に `target` という id をセットしてあります。

そして、JavaScript 上に `modify()` 関数を定義し、`target` という id の要素を探して、その内容を `変更されました！` にするという処理を実装しています。

最後に、JavaScript の組み込み関数である [setTimeout()](https://developer.mozilla.org/ja/docs/Web/API/Window/setTimeout) を使って、1000 ミリ秒後（= 1 秒後）に `modify()` を実行するようタイマーをセットしています。

これにより、もともとは `ここが変更されます` となっていた `<p>` 要素のテキストが、読み込みから 1 秒後に `変更されました！` に書き換えられたわけです。

## jQuery

さて、JavaScript を使って実際に DOM の操作をしてみたわけですが、実はさっきのコードの

```javascript
document.getElementById('target').textContent = '変更されました！';
```

この部分は、もっと簡潔に書くことができます。

```
~/workspace/php-abc-quests/practices/04/hello-jquery.html
```

というファイルを作成して、以下のコードを書いてみてください。処理内容はさっきと全く同じです。

```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>test</title>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body>
<p id="target">ここが変更されます</p>

<script type="text/javascript">
    function modify() {
        // 'target' という id の要素を見つけて、その内容を '変更されました！' にする.
        $('#target').text('変更されました！');
    }

    // 1 秒後に modify を実行する.
    setTimeout(modify, 1000);
</script>
</body>
</html>
```

同じように動作しましたね。コード的には、さっきと変わったのは以下の 2 箇所だけです。


```diff
+ <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
```

```diff
- document.getElementById('target').textContent = '変更されました！';
+ $('#target').text('変更されました！');
```

前者の

```html
<script src="jsファイルのURL"></script>
```

は、別のファイルから JavaScript のコードを読み込むときに使う記述です。何やら `jquery.min.js` というファイルを読み込んでいますね。

そして、これを読み込んだことによって、後者で

```javascript
$('#target').text('変更されました！');
```

という簡潔な書き方で DOM の操作ができるようになっているのです。これが、かの有名な [jQuery](http://ja.wikipedia.org/wiki/JQuery) です。

> [//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js](//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js) は、Google の [CDN](http://ja.wikipedia.org/wiki/%E3%82%B3%E3%83%B3%E3%83%86%E3%83%B3%E3%83%84%E3%83%87%E3%83%AA%E3%83%90%E3%83%AA%E3%83%8D%E3%83%83%E3%83%88%E3%83%AF%E3%83%BC%E3%82%AF) がホストしてくれている jQuery のバージョン 1.11.1 のファイルの URL です。
> 
> この使い方だと、ページを表示するために毎回 Google のコンテンツサーバと HTTP 通信する必要があって読み込みが遅くなるので、通常は jQuery のファイルをローカルサーバ上に置いて使います。

jQuery は、DOM 操作などブラウザ上で使うための JavaScript コードをより簡単に書けるようにするためのライブラリです。現在の JavaScript のデファクトスタンダード的な存在で、ほとんどの Web サイトで利用されています。

## 基本的な使い方

jQuery で DOM を操作する際の基本的な使い方を簡単に示しておきます。より詳細には、適宜ググッてみてください :smiley: 

### セレクタ

まず、操作したい対象の DOM 要素を指定します。指定には [CSS セレクタ](http://www.htmq.com/csskihon/005.shtml) と同じ記法を使用します。（[CSS3 の記法](http://www.htmq.com/css3/#sele) も使えます）

`$('#target')` のように、`$()` の中にセレクタを文字列として与えると、jQuery で操作可能なオブジェクト（jQuery オブジェクト）を取得することができます。

### 操作

jQuery オブジェクトが持つ各種メソッドを通して DOM 要素から値を取得したり、値を書き換えたりといった操作を行います。

`$('#target').text(hoge)` における `$('#target')` が jQuery オブジェクト、`text(hoge)` がメソッドですね。

よく使うものとしては

* `val()`
* `text()`
* `html()`
* `attr()`
* `hasClass()`
* `addClass()`
* `removeClass()`
* `css()`
* `show()`
* `hide()`

などでしょうか。

それぞれのメソッドの意味や、他にどんなメソッドがあるかなど、ぜひ自分で調べてみてください。

[こちらのサイト](http://semooh.jp/jquery/api/core/) などが参考になります。
