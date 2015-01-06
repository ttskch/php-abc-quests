# PHP プログラムの仕組みを理解する

## 基礎的な PHP プログラム

PHP はもともと **HTML の中に部分的にプログラムを埋め込む** ためのものでした。

百聞は一見に如かずです。試しに `~/workspace/php-abc-quests/practices/03/introduction/index.php` を作成して以下のコードを書いてみてください。

> ##### ポイント :bulb:
>
> このとき、ファイルの文字コードは `UTF-8` で保存するようにしてください。
>
> `<meta charset="UTF-8"/>` で指定した文字コードとファイルの文字コードが異なっていると、ブラウザで表示したときに日本語が文字化けする原因になります。
>
> `UTF-8` を選ぶ理由については今の時点では深く考えなくてもよいですが、ファイルによって文字コードがバラバラになると文字化けの原因になるので、
> テキストエディタのデフォルトの文字コードを `UTF-8` に設定しておくことを強くお勧めします。

```php
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>test</title>
</head>
<body>
<p>Hello, World!</p>
</body>
</html>
```

ファイルの拡張子は `.php` ですが中身は純粋な HTML ですね。これをブラウザで表示してみてください。

[http://localhost/php-abc-quests/practices/03/introduction](http://localhost/php-abc-quests/practices/03/introduction)

```
Hello, World!
```

と表示されました。

そう、**PHP ファイルの中には普通に HTML が書ける**んです。

では今度はコードを少しいじって以下のようにしてみてください。

```php
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>test</title>
</head>
<body>
<p>Hello, <?php echo 'PHP'; ?>!</p>
</body>
</html>
```

これを再度ブラウザで表示してみてください。

```
Hello, PHP!
```

と表示されました。

今度のコードは純粋な HTML ではなく、一部分だけが PHP のプログラムになっています。`<?php echo 'PHP'; ?>` の部分ですね。

> ##### ポイント :bulb:
>
> `.php` ファイルの中で `<?php` と `?>` で囲った部分は PHP のプログラムコードとして処理されるのです。

[echo 文](http://php.net/manual/ja/function.echo.php) で `'PHP'` という文字列を出力するだけの小さなプログラムコードを HTML のコードの途中に埋め込んだわけですね。

ではさらにプログラムっぽく変数と配列を使ってみましょう。

```php
<?php
$words = array('World', 'PHP', 'Web Application');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>test</title>
</head>
<body>
<p>Hello, <?php echo $words[0]; ?>!</p>
<p>Hello, <?php echo $words[1]; ?>!</p>
<p>Hello, <?php echo $words[2]; ?>!</p>
</body>
</html>
```

今度は `<?php ?>` が 2 箇所出てきましたね。

```php
<?php
$words = array('World', 'PHP', 'Web Application');
?>
```

ここでは、`$words` という変数を作って、`'World'`, `'PHP'`, `'Web Application'` という 3 つの文字列を要素に持つ [配列](http://php.net/manual/ja/language.types.array.php) を代入しています。

> ##### ポイント :bulb:
>
> PHP では変数名は常に `$` で始まる必要があります。

```php
<p>Hello, <?php echo $words[0]; ?>!</p>
<p>Hello, <?php echo $words[1]; ?>!</p>
<p>Hello, <?php echo $words[2]; ?>!</p>
```

その 3 つの各要素をここでそれぞれ出力しているわけですね。

出力結果は以下のようになっているでしょう。

```
Hello, World!
Hello, PHP!
Hello, Web Application!
```

さて、ここで賢明なプログラマなら 3 つの要素をベタ書きで出力せずにループを回したくなるはずです。
PHP にはループ文として [for](http://php.net/manual/ja/control-structures.for.php), [foreach](http://php.net/manual/ja/control-structures.foreach.php), [while](http://php.net/manual/ja/control-structures.while.php), [do-while](http://php.net/manual/ja/control-structures.do.while.php) が用意されていますが、このように配列の中身を順次処理したい場合には `foreach` が便利です。

```php
<?php
$words = array('World', 'PHP', 'Web Application');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>test</title>
</head>
<body>
<?php foreach ($words as $word) { ?>
    <p>Hello, <?php echo $word; ?>!</p>
<?php } ?>
</body>
</html>
```

このコードは以下のように書いても全く同じ動作になります。

```php
<?php
$words = array('World', 'PHP', 'Web Application');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>test</title>
</head>
<body>
<?php
foreach ($words as $word) {
    echo '<p>Hello, ' . $word . '!</p>' . "\n";
}
?>
</body>
</html>
```

`.` は文字列を連結する演算子です。また、文字列の表現に `'` と `"` が両方登場していますが、これは改行文字を表す `\n` は `""` の中でしか使えないためにそうしています。

> ##### ポイント :bulb:
> PHP では文字列リテラルを [シングルクオートとダブルクオートの両方で表現でき](http://php.net/manual/ja/language.types.string.php)、それぞれ性質が異なります。

## まとめ

ここでは、基礎的な PHP プログラムの基本的な書き方を学びました。

HTML のコードの中に `<?php ?>` で部分的に PHP のプログラムコードを埋め込んで動的なページを生成する、これが PHP プログラミングの基礎になります。

ひとまずここで書いたコードをコミットして GitHub に push しておきましょう。

```bash
$ cd ~/workspace/php-abc-quests
$ git add .
$ git commit -m "03 PHPプログラムの仕組みを理解する"
$ git push
```

> 本来ならコードを変更していく過程をそれぞれコミットしておくべきでしたが、解説が中断されるのを避けるためにあえてコミットは最後に一つだけとしました :bow:

