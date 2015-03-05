# JavaScript を動かしてみる

## はじめに

これから先の章では、ブラウザとして [Google Chrome](https://www.google.co.jp/chrome/browser/) を使っていることを前提にして説明することがあります。（説明の簡略化のため）

Google Chrome は Web 開発者の中でも比較的人気の高いブラウザなので、特にこだわりがなければ Google Chrome を使って進めていってください。

## クライアントサイドとサーバサイド

[Web アプリの仕組みを理解する](01-environment/webapp.md) の章で

> Web サーバ上で動作するアプリを Web アプリと呼び、（略）

と説明しましたが、実は最近の Web アプリはサーバ上だけでなく **クライアント側でも動的に変化させる** ことが一般的になっています。

* サーバ側での動的な変化
* クライアント側での動的な変化

この 2 つを用途や処理内容に応じて適切に組み合わせながらアプリケーションとしての機能を作っていくのが、最近の Web アプリ開発の常識です。

そもそもですが、「Web サイトを閲覧する」という行為の裏では、以下のようなことが行われています。

1. ブラウザから Web サーバに対してページを要求する（リクエスト）
2. Web サーバから HTML や画像、CSS ファイルなどの、ページを構成する「リソース」が送られてくる（レスポンス）
3. ブラウザはダウンロードしたリソースを元にページを表示する（レンダリング）

この中で、`1` の内容に応じて `2` の内容を動的に生成するのが「サーバ側での動的な変化」に相当します。これは前章で PHP を使って実装してきたものですね。

それに対して、`3` で Web サーバからリソースをダウンロードした後で、**ブラウザ上で HTML を動的に変化させる** のが「クライアント側での動的な変化」です。

サーバ側での動的な変化を実装することを **サーバサイドプログラミング（バックエンドプログラミング）**、クライアント側での動的な変化を実装することを **クライアントサイドプログラミング（フロントエンドプログラミング）** と呼びます。

## JavaScript

ブラウザ上で HTML を動的に変化させるためには、PHP ではなく **JavaScript（ジャバスクリプト）** というプログラミング言語を使います。（長いので「JS（ジェーエス）」と略して呼んだりします）

> ※ 現在ブラウザ上で動作するスクリプト言語はほぼ JavaScript 一択という状況であり、現行のほとんど全てのブラウザが JavaScript の実行環境を搭載しています。

JavaScript をブラウザ上で動作させるためには、HTML の中に [&lt;script&gt;](http://www.htmq.com/html/script.shtml) というタグを使って JavaScript のソースコードを埋め込みます。

実は [簡易電卓プログラムの脆弱性対策](03-index.php/calc-security.md) の章で XSS の説明をする際に少しだけ JavaScript について触れていましたが、この章で具体的な使い方を学んでいきます。

百聞は一見に如かず、とにかく実際に動かしてみましょう。

## 動かしてみる

```
~/workspace/php-abc-quests/practices/04/hello-js.html
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
<script type="text/javascript">
    alert('Hello, JavaScript!');
</script>
</body>
</html>
```

保存したら、ブラウザで [http://localhost/php-abc-quests/practices/04/hello-js.html](http://localhost/php-abc-quests/practices/04/hello-js.html) にアクセスしてみてください。

```
Hello, JavaScript!
```

というアラートが表示されましたね。これが、ブラウザが JavaScript コードを実行した結果です。

> ※ ちなみに、JavaScript コード（が埋め込まれた HTML）は、Web サーバから送られてきたものでなくても動作可能なので、`hello-js.html` をブラウザにドラッグアンドドロップする等してローカルのファイルを直接ブラウザで開いても全く同じ結果が得られます。

もう一つ試してみましょう。

今度は `hello-js.html` を以下のように書き換えてみてください。

```html
 <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>test</title>
</head>
<body>
<script type="text/javascript">
    var hello = 'Hello, JavaScript!';
    console.log(hello);
</script>
</body>
</html>
```

これをブラウザで開くとどうなるでしょうか。

何も表示されていませんか？

では、Chrome の右端のメニューボタンから `その他のツール` → `デベロッパーツール` を開いて、`Console` というタブを選択してみてください。

```
Hello, JavaScript!
```

と出力されていますね。

コードの中に書いた

```javascript
console.log(hello);
```

は、「変数 `hello` の中身をコンソールに出力してください」という意味だったのです。

このように、クライアントサイドの開発では、原始的なデバッグプリント手段として `console.log()` をしょっちゅう使います。PHP で `var_dump()` を使うのと同じ感覚ですね。

デベロッパーツールには他にも色々なタブがあることに気がついたと思いますが、**Elements**、**Network** あたりは **Console** と合わせてクライアントサイドの開発をする際に頻繁に使うことになりますので、覚えておいてください。

次節では JavaScript を使って実際に HTML を動的に変化させる方法について学びます。
