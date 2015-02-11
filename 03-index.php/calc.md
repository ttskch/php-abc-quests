# 簡易電卓プログラム

おみくじプログラムでは、毎回実行するたびに自動で結果が決まるようになっていました。

今度はもう一歩進んで、ブラウザからの入力を受け取って、入力に応じた結果を返すプログラムを作ってみましょう。
ここでは例として簡易的な電卓プログラムを作ってみることにします。

## 基本編

```
~/workspace/php-abc-quests/practices/03/calc/index.php
```
```php
<?php
$answer = 100;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>test</title>
</head>
<body>
<input type="text" name="left" required autofocus/>
<select name="operator">
    <option value="+" selected>+</option>
    <option value="-">-</option>
    <option value="*">*</option>
    <option value="/">/</option>
</select>
<input type="text" name="right" required/>
<p><?php echo $answer; ?></p>
</body>
</html>
```

`<input>` タグと `<select>` タグで、単純な二項の四則演算を行う電卓っぽい画面を書いてみました。
表示して確認してみてください。

このままだと、計算結果の箇所には常に `100` が表示されます。

```php
$answer = 100;
```

としているので当然ですね。

この `$answer` に値を代入する処理の部分で、実際にブラウザで入力された値を使うことができれば、本当の計算結果を表示することができそうです。

では、ブラウザの入力を PHP のプログラムに渡すにはどうすればいいのでしょうか？

> ##### ポイント :bulb:
>
> ブラウザの入力を PHP のプログラムに渡すには、HTML の `<form>` タグを利用します。

### フォームを使ってブラウザからの入力を渡してみる

まずは仮の処理になりますが、プログラムを以下のように書き換えてみてください。

```php
<?php
if (isset($_GET['left'])) {
    $answer = $_GET['left'];
} else {
    $answer = '計算結果なし';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>test</title>
</head>
<body>
<form action="index.php" method="GET">
    <input type="text" name="left" required autofocus/>
    <select name="operator">
        <option value="+" selected>+</option>
        <option value="-">-</option>
        <option value="*">*</option>
        <option value="/">/</option>
    </select>
    <input type="text" name="right" required/>
    <input type="submit" value="計算する">
</form>
<p><?php echo $answer; ?></p>
</body>
</html>
```

ここで行ったことは以下のとおりです。

* 入力要素を `<form>` タグで囲い、フォームの送信先を `index.php`（つまり自分自身）、送信時に利用するリクエストメソッドを `GET` に設定
* `計算する` ボタンでフォームが送信されるように設定
* `$_GET` という変数（連想配列）に `'left'` というキーが [セットされている場合](http://php.net/manual/ja/function.isset.php) は `$_GET['left']` を、そうでない場合は `'計算結果なし'` を `$answer` に代入するように処理を変更

> ##### ポイント :bulb:
>
> PHP では、[$_GET](http://php.net/manual/ja/reserved.variables.get.php) という変数で GET リクエストのパラメータを取得することができます。
> （同様に、POST リクエストのパラメータは [$_POST](http://php.net/manual/ja/reserved.variables.post.php) で取得できます。）

このプログラムを動作させてみると、最初にページを開いたときは `計算結果なし` と表示されていて、左辺に何か入力して `計算する` ボタンをクリックすると、入力した値が表示されます。（今は仮に `$_GET['left']` の値を `$answer` に代入しているだけなので、実際の計算結果にはなりません。）

`計算する` ボタンをクリックした後、URL の末尾に `?left=xxx&operator=xxx&right=xxx` というような文字列が付加されたことに気付いたでしょうか。
この部分は「クエリパラメータ」と言い、これを使ってリクエスト先のページに自由にパラメータを渡すことができます。

パラメータがセットされているかどうか（ここでは `left=xxx` のみチェック）によって、

```php
if (isset($_GET['left'])) {
    $answer = $_GET['left'];
} else {
    $answer = '計算結果なし';
}
```

ここの処理の実行パスが変わり、画面に表示される内容が変わっているわけです。

> ##### ポイント :bulb:
>
> プログラムの解説からは少しずれますが、PHP に限らず Web アプリケーションを開発していく上で、[HTTP](http://ja.wikipedia.org/wiki/Hypertext_Transfer_Protocol) という通信プロトコルについて理解することは **必須スキル** です。
>
> 現状では php-abc-quests では HTTP についての詳しい解説は割愛していますが、各自 [ググって](https://www.google.co.jp/search?q=http+%E4%BB%95%E7%B5%84%E3%81%BF&oq=http+%E4%BB%95%E7%B5%84%E3%81%BF&aqs=chrome.0.69i59j69i60.1609j0j4&sourceid=chrome&es_sm=119&ie=UTF-8) 勉強しておいてください :bow:
>
> 例えば、[こちらの記事](http://www.atmarkit.co.jp/ait/articles/0103/02/news003.html) などは少し分量が多いですが詳細に説明されていて分かりやすいと思います。

### 実際の計算結果を表示できるようにしてみる

では、プログラム部分を作り込んで実際の計算結果を表示できるようにしてみましょう。

```php
<?php
if (isset($_GET['operator'])) {
    switch ($_GET['operator']) {
        case '-':
            $answer = $_GET['left'] - $_GET['right'];
            break;
        case '*':
            $answer = $_GET['left'] * $_GET['right'];
            break;
        case '/':
            $answer = $_GET['left'] / $_GET['right'];
            break;
        case '+':
        default:
            $answer = $_GET['left'] + $_GET['right'];
            break;
    }
} else {
    $answer = '計算結果なし';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>test</title>
</head>
<body>
<form action="index.php" method="GET">
    <input type="text" name="left" required autofocus/>
    <select name="operator">
        <option value="+" selected>+</option>
        <option value="-">-</option>
        <option value="*">*</option>
        <option value="/">/</option>
    </select>
    <input type="text" name="right" required/>
    <input type="submit" value="計算する">
</form>
<p><?php echo $answer; ?></p>
</body>
</html>
```

実行してみてください。入力した内容に応じてちゃんと計算結果が表示されたと思います。

## 応用編

今のままだと、計算結果が表示されると同時にフォームに入力していた値が画面から消えてしまうため、どんな計算をした結果が表示されているのか分からなくて不便ですね。

そこで、

* 計算結果だけでなく計算式も表示するようにする
* ついでに、フォームに入力していた値も画面から消えないようにする

という改良を行ってみましょう。

例によって、自力でチャレンジしてみてから [解答例](calc-advanced.md) を見るようにしてください。

> ところで、これぐらいの規模のプログラムになってくると、「この時点でこの変数の中身はどうなっているんだろう？」というのを随時確認（いわゆるデバッグプリントですね）しながら作る、ということがやりたくなってくるかもしれません。
>
> PHP ではデバッグプリント用に [var_dump()](http://php.net/manual/ja/function.var-dump.php) という組み込み関数が用意されています。`var_dump()` した直後に `exit()` でプログラムを終了させて、その時点の変数の中身を確認する、というのはよく使う方法の一つです。
