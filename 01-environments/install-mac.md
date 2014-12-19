# Apache と PHP をインストールする（Mac）

実は、Mac には Apache も PHP も初めからインストールされています。なので、ここでは設定の変更方法や起動方法を覚えましょう。

## Apache を起動する

以下のコマンドで Apache の起動・終了・再起動ができます。

```bash
# 起動
$ sudo apachectl start

# 終了
$ sudo apachectl stop

# 再起動
$ sudo apachectl restart
```

Apache が起動している状態で、ブラウザを開いて http://localhost/index.html.en にアクセスしてみてください。

```
It works!
```

と表示されたでしょうか。

これは、デフォルトの [ドキュメントルート](http://e-words.jp/w/E38389E382ADE383A5E383A1E383B3E38388E383ABE383BCE38388.html) である `/Library/WebServer/Documents` というパスにある `index.html.en` というファイルにブラウザからアクセスした結果です。

以下のコマンドで、そのファイルを確認してみましょう。

```bash
$ view /Library/WebServer/Documents/index.html.en
```

```html
<html><body><h1>It works!</h1></body></html>
```

ブラウザに表示されたページのソースコードになっていますね。

## Apache の設定を変更する

Apache の設定ファイルは `/etc/apache2/httpd.conf` にあります。デフォルトの設定のままだと不便なところがあるので、最初に少しだけ編集しておきましょう。書き込み権限が必要なので sudo で開きます。

```bash
$ sudo vi /etc/apache2/httpd.conf
```

とりあえず変更しておきたいのは以下の 2 点です。

* ドキュメントルートをもう少し扱いやすい場所に変更する
* [.htaccess](http://e-words.jp/w/2Ehtaccess.html) を使えるようにする

### ドキュメントルートを変更する

以下のようにドキュメントルートを設定している箇所を見つけてください。

```
DocumentRoot "/Library/WebServer/Documents"
```

これを

```
#DocumentRoot "/Library/WebServer/Documents"
DocumentRoot "~/workspace"
```

このように変更しましょう。

既存の行を `#` でコメントアウトして、`~/workspace` がドキュメントルートになるように設定し直しました。

ドキュメントルートを変更した場合は、以下の箇所も変更が必要なので要注意です。

```
<Directory "/Library/WebServer/Documents">
    〜略〜
</Directory>
```

ここを

```
<Directory "~/workspace">
    〜略〜
</Directory>
```

と変更してください。

### .htaccess を使えるようにする

上記で変更した

```
<Directory "~/workspace">
    〜略〜
</Directory>
```

この部分の `〜略〜` の中に、

```
    AllowOverride None
```

という箇所があると思います。ここを

```
#    AllowOverride None
    AllowOverride All
```

と変更してください。これで、ドキュメントルート配下で `.htaccess` が使えるようになります。

### Apache を再起動してワークディレクトリを作成する

`httpd.conf` を変更しても、Apache を再起動しないと変更は反映されません。忘れずに再起動しておきましょう。

```bash
$ sudo apachectl restart
```

また、ドキュメントルートを `~/workspace` に変更したので、実際に `~/workspace` というディレクトリを作っておく必要があります。

Finder でユーザディレクトリを開いて `workspace` というディレクトリを作ってもよいですし、コマンドで以下のようにして作成しても OK です。

```bash
$ mkdir ~/workspace
```

## PHP を有効にする

次に、Apache の設定で PHP のモジュールを有効にして、Apache から PHP のプログラムを実行できる状態にしましょう。

先ほどと同じように `httpd.conf` を編集します。

```bash
$ sudo vi /etc/apache2/httpd.conf
```

以下のような行を見つけてください。

```
#LoadModule php5_module libexec/apache2/libphp5.so
```

この行を

```
LoadModule php5_module libexec/apache2/libphp5.so
```

と、先頭の `#` を削除してコメントインすれば、PHP のモジュールが有効になります。

さらに、

```
<IfModule dir_module>
    DirectoryIndex index.html
</IfModule>
```

という箇所を見つけてください。

この部分は、「ファイル名を省略してアクセスされた場合に自動で表示するファイル」を設定しています。

デフォルトでは `index.html` だけになっていますが、PHP で Web アプリを作る場合は `index.php` というファイルも自動で表示してほしいので、以下のように追加しておきましょう。

```
<IfModule dir_module>
    DirectoryIndex index.html index.php
</IfModule>
```

こうしておけば、アクセスされたディレクトリに

1. `index.html` があればそれが表示され
2. `index.html` がなくて `index.php` があればそれが表示され
3. `index.html` も `index.php` もなければ 404 Not Found になる

という動作になります。

変更を保存したら、忘れずに Apache を再起動しておきましょう。

## PHP を動かしてみる

ここまでで、PHP が実行可能な Web サーバの構築が完了しました。次は実際に動かしてみましょう。

ドキュメントルート（`~/workspace`）直下に `index.php` というファイルを作成して、以下の内容を記述してください。

```php
<?php
phpinfo();
?>
```

これを保存したら、ブラウザで http://localhost にアクセスしてみてください。

[こんな感じの画面](https://www.google.co.jp/search?q=phpinfo&tbm=isch#tbm=isch&q=phpinfo&imgdii=_) が表示されれば OK です。

[phpinfo](http://php.net/manual/ja/function.phpinfo.php) という PHP の組み込み関数を実行した結果を表示する、最も基礎的な Web アプリの実行に成功しました！おめでとうございます！
