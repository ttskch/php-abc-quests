# Bootstrap を使ってみる

さて、ではいよいよ実際に CSS フレームワークを使ってみましょう。

CSS フレームワークには [いろいろあります](http://qiita.com/komeda/items/a74bda1408141e9109c9) が、最もユーザ数が多いものと言えば（多分）Twitter 社が作っている [Bootstrap](http://getbootstrap.com/) です。

筆者も Bootstrap 以外のフレームワークは使ったことがないので、ここでは Bootstrap の使い方について説明します :sweat_smile: 

## とにかく動かしてみる

使い方について色々と説明が必要なところはありますが、まずはとにかく動かしてみましょう。

```
~/workspace/php-abc-quests/practices/05/hello-bootstrap.html
```

というファイルを作成して、[Bootstrap のサイトに書いてある HTML のテンプレート](http://getbootstrap.com/getting-started/#template) を参考に、以下のようなコードを書いてください。

```html
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap example</title>

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<h1>Hello, Bootstrap!</h1>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
</body>
</html>
```

保存したら、[http://localhost/php-abc-quests/practices/05/hello-bootstrap.html](http://localhost/php-abc-quests/practices/05/hello-bootstrap.html) にアクセスしてみましょう。当然ながら `Hello, Bootstrap!` と表示されていますね :+1: 

`<head>` タグの中に色々難しいことが書いてありますが、今は全ておまじないと思っていて大丈夫です。`<body>` タグの中（のうち、`<script>` より前）だけに注目しておいてください。

## Bootstrap らしいことをしてみる

`Hello, Bootstrap!` だけだと今のところ何も嬉しくないので、少し Bootstrap らしいことをしてみましょう。

`<body>` タグ内に以下のようなコードを書いてみてください。

```html
<a class="btn btn-default" href="">編集</a>
<a class="btn btn-danger" href="">削除</a>
<a class="btn btn-primary" href="">保存</a>
```

ただ `btn` `btn-hogehoge` という `class` をつけただけの `<a>` タグですが、表示してみてください。

**おお、なんかそれっぽいボタンが表示されてますね！**

さらにこんなのも書いてみましょう。（ちょっと長いですが、ただの簡単なテーブルです）

```html
<table class="table table-hover">
    <thead>
    <tr>
        <th>名前</th>
        <th>誕生日</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>中居正広</td>
        <td>1972/08/18</td>
        <td>
            <a class="btn btn-default btn-sm" href="">編集</a>
            <a class="btn btn-danger btn-sm" href="">削除</a>
        </td>
    </tr>
    <tr>
        <td>木村拓哉</td>
        <td>1972/11/13</td>
        <td>
            <a class="btn btn-default btn-sm" href="">編集</a>
            <a class="btn btn-danger btn-sm" href="">削除</a>
        </td>
    </tr>
    <tr>
        <td>稲垣吾郎</td>
        <td>1973/12/08</td>
        <td>
            <a class="btn btn-default btn-sm" href="">編集</a>
            <a class="btn btn-danger btn-sm" href="">削除</a>
        </td>
    </tr>
    <tr>
        <td>草なぎ剛</td>
        <td>1974/07/09</td>
        <td>
            <a class="btn btn-default btn-sm" href="">編集</a>
            <a class="btn btn-danger btn-sm" href="">削除</a>
        </td>
    </tr>
    <tr>
        <td>香取慎吾</td>
        <td>1977/01/31</td>
        <td>
            <a class="btn btn-default btn-sm" href="">編集</a>
            <a class="btn btn-danger btn-sm" href="">削除</a>
        </td>
    </tr>
    </tbody>
</table>
```

表示してみてください。

**どうですか、このそれっぽさ！**

## ナビゲーションバーとかも付けてみる

さらに、ページトップによくあるナビゲーションバーなんかも付けてみましょう。

```html
<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="">Bootstrap</a>
        </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="">一覧</a></li>
                <li><a href="">リンク</a></li>
            </ul>
    </div>
</nav>

<table class="table table-hover">
    :
    :
</table>
```

**ますますそれっぽくなってきました！**

今のところ自分では一行も CSS を書いていないのに、ちょろっと `class` 名を付けたり、決められたとおりのタグを書くだけで、こんなに **それっぽい** デザインになりました。これが CSS フレームワークの力です。

## なんか横幅いっぱいなんですけど？

ところで、さっき書いたテーブル、画面の幅いっぱいに表示されていてちょっとみっともないですよね。これには理由があって、正しくマークアップすればちゃんといい感じになります。

ひとまず説明は抜きにして、いい感じに表示されるように書き直してみましょう。

```html
<div class="container-fluid">
    <table class="table table-hover">
        :
        :
    </table>
</div>
```

このように `table` を `div.container-fluid` で囲うだけです。表示してみてください。

微妙な変化ですが、テーブルの左右に適切なマージンが挿入されてきれいな感じになりましたね。

さらにこんなこともしてみましょう。

```html
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>名前</th>
                    <th>誕生日</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>中居正広</td>
                    <td>1972/08/18</td>
                    <td>
                        <a class="btn btn-default btn-sm" href="">編集</a>
                        <a class="btn btn-danger btn-sm" href="">削除</a>
                    </td>
                </tr>
                :
                :
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>名前</th>
                    <th>誕生日</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>大野智</td>
                    <td>1980/11/26</td>
                    <td>
                        <a class="btn btn-default btn-sm" href="">編集</a>
                        <a class="btn btn-danger btn-sm" href="">削除</a>
                    </td>
                </tr>
                <tr>
                    <td>櫻井翔</td>
                    <td>1982/01/25</td>
                    <td>
                        <a class="btn btn-default btn-sm" href="">編集</a>
                        <a class="btn btn-danger btn-sm" href="">削除</a>
                    </td>
                </tr>
                <tr>
                    <td>相葉雅紀</td>
                    <td>1982/12/24</td>
                    <td>
                        <a class="btn btn-default btn-sm" href="">編集</a>
                        <a class="btn btn-danger btn-sm" href="">削除</a>
                    </td>
                </tr>
                <tr>
                    <td>二宮和也</td>
                    <td>1983/06/17</td>
                    <td>
                        <a class="btn btn-default btn-sm" href="">編集</a>
                        <a class="btn btn-danger btn-sm" href="">削除</a>
                    </td>
                </tr>
                <tr>
                    <td>松本潤</td>
                    <td>1983/08/30</td>
                    <td>
                        <a class="btn btn-default btn-sm" href="">編集</a>
                        <a class="btn btn-danger btn-sm" href="">削除</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
```

ちょっと複雑ですが、

```
div.container-fluid
   |
   +-- div.row
           |
           +-- div.col-md-6
           |       |
           |       +-- table
           |
           +-- div.col-md-6
                   |
                   +-- table
```

という構造になっています。

いきなりワケの分からない `class` 名が登場しましたが、とりあえず何も考えずに動作確認してみてください。
2 つのテーブルが画面の左右半分ずつの幅で表示されていれば OK です。

## グリッドレイアウト

先ほど使った左右 2 分割で配置するテクニックは、Bootstrap を使ってデザインを構築していく上で避けては通れないものです。ここで基本的な概念を理解しておきましょう。

Bootstrap のデザインは、[グリッドレイアウト](https://www.google.co.jp/search?q=%E3%82%B0%E3%83%AA%E3%83%83%E3%83%89%E3%83%AC%E3%82%A4%E3%82%A2%E3%82%A6%E3%83%88) と呼ばれる手法を採用しています。

グリッドレイアウトとは、**画面の幅全体をいくつかの列に等分** して、そのうちの **何列分の幅を使うか** を指定して要素を配置していく手法のことです。

Bootstrap では画面全体が **12 列** に分割されています。

先ほどの例で `col-md-6` という `class` 名をつけましたが、これは **6 列分（つまり画面全体の半分）** を使うための指定だったわけです。

> ちなみに `col` は column（列）の頭文字ですね。また、`md` の部分にも意味がありますが、ここは後ほど説明します。

Bootstrap でグリッドを使う場合、**一行分の要素全体を** `div.row` で囲う必要があります（row = 行）。
また、1 つの `row` の中の `col` の合計は 12 にする必要があります。

さらに、[ここに書いてあるとおり](http://getbootstrap.com/css/#overview-container)、グリッドを使う要素全体を `div.container` または `div.container-fluid` で囲う必要があります。（これがないと、最初に見たように全体が画面幅いっぱいになってしまいます）

Bootstrap を使う場合は基本的に `div.container` > `div.row` > `div.col-xx-xx` という構成にする必要があるということだけ、覚えておいてください。

## レスポンシブデザイン

さて、前節でレスポンシブデザインについて学びましたが、Bootstrap もレスポンシブデザインに対応しています。

先ほど、`col-md-6` という `class` を使いましたが、この `md` の部分は

* `xs`
* `sm`
* `md`
* `lg`

の 4 種類あり、それぞれ

* extra small（画面幅 767px 以下）
* small（画面幅 768px 〜 991px）
* medium（画面幅 992px 〜 1199px）
* large（画面幅 1200px 以上）

を意味しています。

詳しくは [こちら](http://getbootstrap.com/css/#grid-options) に説明がありますが、これらの `class` は、**閲覧者の画面サイズに応じて「何列分でレイアウトするか」を変化させる** ために使い分けます。

多分この説明だとピンと来ないと思いますので、実例を挙げてみましょう。

まずは、現状の `hello-bootstrap.html` をブラウザで表示したまま、ウィンドウサイズを小さくしてみてください。

**ある一定より小さくなったところで、2 つのテーブルそれぞれが画面幅いっぱいを使って 2 行で表示されたと思います。**

では、`col-md-6` と書いてあるところを `col-xs-6` に変更してみてください（2 箇所あるのでご注意を）。
保存したら、表示して先ほどと同じように画面を小さくしてみてください。

今度は画面幅をいくら小さくしても 2 行に分かれなくなりましたね。

これは、`col-md-6` が **画面サイズが md 以上のときに 6 列で表示する** という意味だからです。
特に指定されていない画面サイズのときは自動で 12 列で表示されます。

`col-xs-6` に変更したことで、**xs 以上のとき（つまり常に）に 6 列 で表示** となったわけですね。

ではさらに、`class="col-lg-3 col-md-4 col-sm-6"` と、3 つの `class` を付けてみてください。

* 画面サイズが lg のときは 3 列（つまり画面幅の 1/4）
* 画面サイズが md のときは 4 列（つまり画面幅の 1/3）
* 画面サイズが sm のときは 6 列（つまり画面幅の半分）
* （特に指定がされていないので）画面サイズが xs のときは 12 列（つまり画面幅いっぱい）

となったかと思います。

このように、`col-xx-xx` クラスを適切に設定しておくことで、画面サイズの異なるデバイスに対し常に適切なレイアウトを構成できるようになるのです。便利ですね〜。

他にも、`hidden-xs` や `visible-lg` などのクラスを使って、**xs のときは表示しない** とか **lg のときにしか表示しない** とかいったこともできます。（詳しくは [こちら](http://getbootstrap.com/css/#responsive-utilities-classes)）

## まとめ

Bootstrap の基本的な使い方について説明しました。

紹介したもの以外にも、様々なデザインや機能を簡単に使うことができますので、ググったりオフィシャルサイトを読んだりして、どんなことができるのか調べてみてください。

ここでは説明しませんでしたが、単純な CSS 以外にも、JavaScript を使った [こんな表現](http://getbootstrap.com/javascript/#carousel) とかも簡単に使えますし、他にも

```html
<i class="glyphicon glyphicon-thumbs-up"></i>
```

とか書くだけで [それらしいアイコンを表示してくれる](http://getbootstrap.com/components/#glyphicons) 機能などもあります。

英語が苦手な人には難しいかもしれませんが、オフィシャルサイトのドキュメントにはぜひ一通り目を通してみてほしいと思います。
