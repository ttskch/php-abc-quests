# イベントハンドラを使ってみる

前節では `1 秒後に自動で DOM が書き換わる` という処理を作りましたが、これだけだと正直あまり使いどころがないですね。

フロントエンドの DOM 操作の醍醐味はやっぱり、ユーザの操作に応じてインタラクティブに HTML を変化させることだと思います。

例えば、

* ボタンをクリックしたら表示が変わる
* 特定の場所をマウスオーバーしたら表示が変わる
* スクロールしてもサイドメニューが付いてくる

のように。

これを実装するには、**イベントハンドラ** というものを使います。

## イベントとイベントハンドラ

DOM オブジェクトには、クリックされたときに発生する `click` イベントや、マウスオーバーされたときに発生する `mouseover` イベントなど、[様々なイベントが定義されて](https://developer.mozilla.org/ja/docs/Web2/Reference/Events) います。これらのイベントの発生を検知してくれるのがイベントハンドラです。

実際に例を見てみましょう。

```
~/workspace/php-abc-quests/practices/04/hello-event.html
```

というファイルを作成して、以下のコードを書いてみてください。

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
<input type="button" value="変更する" onclick="modify()"/>

<script type="text/javascript">
    function modify() {
        $('#target').text('変更されました！');
    }
</script>
</body>
</html>
```

「変更する」ボタンをクリックすると `<p>` 要素のテキストが変化しますね。

さっきまでのコードとほとんど変わっていませんが、`setTimeout()` で 1 秒後に自動で実行されるようになっていた部分を、`<input>` タグの `onclick` 属性を使って、ボタンがクリックされたときに実行されるようになっています。

ここでは `onclick` を使いましたが、イベントの数だけ対応するハンドラがあります（[参考](http://phpjavascriptroom.com/?t=js&p=event)）ので、色々使って遊んでみてください。

## JavaScript コード内でイベントをバインドする

これくらいシンプルなページであれば HTML のタグに `onclick=""` などと書いてイベントをバインドする方法でもよいのですが、もっとページが複雑になってくると、HTML のコードと JavaScript のコードをしっかり分離したくなってきます。

jQuery には、指定した要素にイベントをバインドするメソッドも用意されているので、`onclick` 等の HTML 属性を使わなくても、JavaScript コード内で完結させることができます。

`hello-event.html` の `<body>` 内を以下のように書き換えてみてください。

```html
<p id="target">ここが変更されます</p>
<input type="button" value="変更する" id="trigger"/>

<script type="text/javascript">
    function modify() {
        $('#target').text('変更されました！');
    }

    $('#trigger').on('click', modify);
</script>
```

あるいは、JavaScript の [無名関数](http://ja.wikipedia.org/wiki/%E7%84%A1%E5%90%8D%E9%96%A2%E6%95%B0#JavaScript.E3.81.AE.E7.84.A1.E5.90.8D.E9.96.A2.E6.95.B0) を使って、以下のように書くこともできます。

```html
<p id="target">ここが変更されます</p>
<input type="button" value="変更する" id="trigger"/>

<script type="text/javascript">
    $('#trigger').on('click', function () {
        $('#target').text('変更されました！');
    });
</script>
```

`<input>` 要素に `trigger` という id をつけておき、`'#trigger'` でセレクトした jQuery オブジェクトに対し、`on` メソッドを使ってイベントと処理をバインドしています。

> イベント発生時に呼び出される関数のことを「コールバック関数」と呼んだりします。

## JavaScript のソースを別ファイル化する

せっかく HTML と JavaScript のコードを分離するなら、ファイルごと分けてしまったほうが管理がしやすいので、やってみましょう。

と言ってもやることはとても簡単です。既に、jQuery のソースファイルを URL から読み込んで使っていますが、これと全く同じ方法でやればよいのです。

```
~/workspace/php-abc-quests/practices/04/hello-event.js
~/workspace/php-abc-quests/practices/04/hello-event.html
```

という 2 ファイルを作成して、`hello-event.js` は以下のような内容に、

```javascript
$('#trigger').on('click', function () {
    $('#target').text('変更されました！');
});
```

そして `hello-event.html` は以下のような内容にしてください。

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
<input type="button" value="変更する" id="trigger"/>

<script type="text/javascript" src="hello-event.js"></script>
</body>
</html>
```

これで完全に HTML と JavaScript のコードを別々に管理できるようになりました。いい感じですね。

## DOM 構築待ちを考慮する

さて、賢明な方なら先ほどのコードを見ていささかの違和感を覚えたかもしれません。

```html
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
```

この読み込みは `<head></head>` 内で行っているのに、

```html
<script type="text/javascript" src="hello-event.js"></script>
```

なんでこっちは `<body>` の一番下に書いたのでしょうか？まとめて書いておいたほうが読みやすいですよね。

実はこれには理由があります。試しに `hello-event.js` の読み込みを `<head>` に移動させてみてください。

```html
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>test</title>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="hello-event.js"></script>
</head>
<body>
<p id="target">ここが変更されます</p>
<input type="button" value="変更する" id="trigger"/>
</body>
</html>
```

ボタンをクリックしても変化が起きませんね。

### DOM ツリーの構築と JavaScript の実行タイミング

先ほどの例でボタンをクリックしても何も起こらなかったのは、ブラウザが DOM ツリーを構築する順序と、JavaScript コードが実行されるタイミングが関係しています。

こう聞くと難しそうですが、何のことはありません。

ブラウザは、**HTML コードの上から順番に** DOM ツリーを構築していきます。そして、途中で `<script>` タグを見つけた場合、その時点で `<script>` タグに記述されている（もしくは `src=""` で読み込まれている）JavaScript のコードを実行します。

`hello-event.js` に書かれている処理の内容は以下のとおりでしたね。

```javascript
$('#trigger').on('click', function () {
    $('#target').text('変更されました！');
});
```

今一度確認しますが、この処理の意味は、「DOM ツリーの中から id が `trigger` である要素を見つけて、その要素に `click` イベントとコールバック関数をバインドする」ということですね。

しかし、この JavaScript コードが実行されたタイミングでは、**id が `trigger` である要素はまだ DOM ツリー上に存在していません。**

つまり、ボタンをクリックしても変化が起きなかったのは、単純に **ボタンにイベントがバインドされていなかった** からというわけです。

### 対策

このような不具合が起こらないようにするには、DOM ツリーの構築が全て完了してから JavaScript のコードが実行されるようにしておく必要がありそうです。

「`<script>` タグは常に `<body>` タグの一番最後に書く！」という方法でも対策は可能ですが、それだと、何かのはずみに `<script>` タグの位置が変更されてしまうととたんに動かなくなってしまうのであまり良手とは言えません。

> 実は、`<script>` タグを `<body>` タグの最後に書くというのはページ読み込みの体感速度を早くするための定石として実際によくやられていることなのですが、それは一旦置いておきます :sweat_smile: 

`<script>` タグがページ内のどこにあっても正常に動作できるように、**全ての処理を、DOM ツリーの構築完了イベントの発生時に行うようにしておく** という方法をとります。

`hello-event.js` を以下のように修正してみてください。

```javascript
$(document).ready(function () {

    // 全ての処理をこの中に書く.
    
    $('#trigger').on('click', function () {
        $('#target').text('変更されました！');
    });

});
```

これで、`<script>` タグが `<head>` 内にあってもちゃんと動くようになったかと思います。

`$(document)` というのは HTML ドキュメント全体を jQuery オブジェクト化したもので、これの `ready()` メソッドで、`DOM ツリー構築完了` イベントに処理をバインドしています。

これによって、`$(document).ready( ここ );` に書いた無名関数の処理は、DOM ツリー構築完了直後に呼ばれるようになったわけです。

なお、これにはいくつかの書き方があり、一番短い書き方だと `$( ここ );` と書くこともできます。今回の例だと以下のような形になります。

```javascript
$(function () {
    $('#trigger').on('click', function () {
        $('#target').text('変更されました！');
    });
});
```

一般的にはこの書き方をする人が多いように思います。このあたり、詳しくは [こちら](http://js.studio-kingdom.com/jquery/events/ready) などを参考に調べてみてください。

> ##### ポイント :bulb:
>
> ここで登場した `$(document)` のように、`$()` の中にはセレクタ文字列の他に、JavaScript 標準の DOM オブジェクトを入れて jQuery オブジェクトでラップすることができます。
> 
> と言っても、通常使うのは DOM ツリーのルート要素である `document` か、「自分自身」を意味する `this` ぐらいです。今後出てくることがあるかも知れませんので、頭の片隅で覚えておいてください。

## まとめ

急に PHP と関係ない話がたくさん出てきたので多少混乱してしまったかもしれませんが、ひとまず今の時点では JavaScript について隅々まで正確に理解しておく必要はありません。

* JavaScript を使えばブラウザ側で DOM ツリーを操作できる
* 素の JavaScript でもできるけど、jQuery というライブラリを使うと色々便利になる
* DOM 要素にイベントハンドラを仕込んで、イベント駆動で DOM が変化するようにプログラムしていくのが一般的な流れ
* DOM 操作のプログラムを書くときは、DOM ツリー構築完了を待ってから実行されるようにおまじないが必要

ぐらいが分かっていれば十分ではないかと思います。
