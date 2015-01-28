# Apache と PHP をインストールする

## 目次

* [インストール](#install)
* [Apache を起動する](#run-apache)
* [ローカルサーバにブラウザでアクセスしてみる](#localhost)
* [Apache の設定を変更する](#setup-apache)
* [PHP のページを表示してみる](#php)

> 途中、Mac と Windows で実施する内容が分岐するところが出てきますが、自分に関係ない方は読み飛ばして問題ありません。

<a name="install"></a>
## インストール

### Mac の場合

実は、Mac には Apache も PHP も初めからインストールされているので、インストール作業は不要です。

[次へ進む](#run-apache)

### Windows の場合

Windows の場合、[XAMPP](http://ja.wikipedia.org/wiki/XAMPP) というソフトをインストールすることで Apache と PHP をまとめてインストールすることができます。

[XAMPP のオフィシャルサイト](https://www.apachefriends.org/jp/index.html) から Windows 用インストーラをダウンロードして、インストールしてください。インストール先もデフォルトの `C:¥xampp` で OK です。

> Windows7 ではインストーラを起動すると警告が出るかもしれませんが、気にせず OK して大丈夫です。

[次へ進む](#run-apache)

<a name="run-apache"></a>
## Apache を起動する

まずはデフォルトの設定のままで Apache を起動してみましょう。

### Mac の場合

Mac では、CLI で以下のコマンドを使って Apache の起動・終了・再起動を行います。

```bash
# 起動
$ sudo apachectl start

# 終了
$ sudo apachectl stop

# 再起動
$ sudo apachectl restart
```

[次へ進む](#localhost)

### Windows の場合

Windows では、XAMPP を起動して Apache の `Start` ボタンをクリックすれば Apache を起動できます。
Apache が起動している状態ではボタンが `Stop` になっていますので、これをクリックすれば Apache を終了できます。

> #### 起動が失敗する場合
>
> Apache の起動が失敗する場合、ほとんどは使おうとしているポートが他のアプリに既に使われていることが原因です。さらには、その競合しているアプリというのもほとんどの場合は Skype です。（[ググってみるとすぐ分かります](https://www.google.co.jp/search?q=xampp+apache+%E8%B5%B7%E5%8B%95%E3%81%A7%E3%81%8D%E3%81%AA%E3%81%84&oq=xampp+apache+%E8%B5%B7%E5%8B%95%E3%81%A7%E3%81%8D%E3%81%AA%E3%81%84&aqs=chrome..69i57.8608j0j4&sourceid=chrome&es_sm=119&ie=UTF-8)）
>
> この場合は、Skype が 80, 443 のポートを使わないように設定変更してから Apache を起動してください。

[次へ進む](#localhost)

<a name="localhost"></a>
## ローカルサーバにブラウザでアクセスしてみる

Apache が起動している状態で、ブラウザを開いて以下の URL にアクセスしてみてください。

* Mac の場合：[http://localhost/index.html.en](http://localhost/index.html.en)
* Windows の場合：[http://localhost/index.html](http://localhost/index.html)

```
It works!
```

と表示されたでしょうか。

これは、デフォルトの [ドキュメントルート](http://e-words.jp/w/E38389E382ADE383A5E383A1E383B3E38388E383ABE383BCE38388.html) に置かれている `index.html(.en)` というファイルにブラウザからアクセスした結果です。

ドキュメントルートの設定はこのあと確認しますが、ひとまず以下の場所に当該のファイルがあるので、内容を確認してみてください。ブラウザに表示されたページのソースコードが確認できるはずです。

* Mac の場合：`$ view /Library/WebServer/Documents/index.html.en`
* Windows の場合：`C:¥xampp¥htdocs¥index.html` をテキストエディタで開く

[次へ進む](#setup-apache)

<a name="setup-apache"></a>
## Apache の設定を変更する

初めに Apache の設定をいくつか変更しておく必要があります。
Apache の設定ファイルは、Mac, Windows それぞれ以下の箇所にあります。

* Mac の場合：`/etc/apache2/httpd.conf`
* Windows の場合：`C:¥xampp¥apache¥conf¥httpd.conf`

変更したい箇所は Mac と Windows で異なりますので、以下はそれぞれの環境に合わせて実施してください。

* [Mac の場合](#setup-apache-mac)
* [Windows の場合](#setup-apache-win)

<a name="setup-apache-mac"></a>
### Mac の場合

Mac の場合、変更したい箇所は以下のとおりです。

* ドキュメントルートを扱いやすい場所に変更する
* [.htaccess](http://e-words.jp/w/2Ehtaccess.html) を使えるようにする
* PHP を有効にする

以下のように設定ファイルをテキストエディタで開いて編集してください。

```bash
$ sudo vi /etc/apache2/httpd.conf
```

#### ドキュメントルートを変更する

ドキュメントルートを設定している箇所を以下のように変更してください。

```
#DocumentRoot "/Library/WebServer/Documents"
DocumentRoot "/Users/あなたのユーザ名/workspace"
```

> 既存の行を `#` でコメントアウトして、ホームディレクトリ直下の `workspace` というディレクトリがドキュメントルートになるように設定し直しています。
>
> なお、ユーザ名が分からない場合はターミナルで
>
> ```bash
> $ cd ~
> $ pwd
> /Users/あなたのユーザ名
> ```
>
> とすれば確認できます。


ドキュメントルートを変更した場合は以下の箇所も変更が必要なので要注意です。

```
#<Directory "/Library/WebServer/Documents">
<Directory "/Users/あなたのユーザ名/workspace">
    〜略〜
</Directory>
```

#### .htaccess を使えるようにする

上記で変更した

```
<Directory "/Users/あなたのユーザ名/workspace">
    〜略〜
</Directory>
```

この部分の `〜略〜` の中にある以下の箇所を変更してください。

```
#    AllowOverride None
    AllowOverride All
```

#### PHP を有効にする

```
#LoadModule php5_module libexec/apache2/libphp5.so
```

この行の先頭の `#` を削除してコメントインしてください。

```
LoadModule php5_module libexec/apache2/libphp5.so
```

さらに、

```
<IfModule dir_module>
    DirectoryIndex index.html
</IfModule>
```

という箇所を見つけて、以下のように変更してください。
こうしておくと、ブラウザからディレクトリにアクセスしたときに、自動で `index.php` が表示されるようになります。

```
<IfModule dir_module>
    DirectoryIndex index.php index.html
</IfModule>
```

#### 設定の変更を反映させる

ここまでの変更を保存したら、最後に、ドキュメントルートとして設定したディレクトリ（`~/workspace`）を実際に作成します。

```bash
$ cd ~
$ mkdir workspace
```

作成したら、Apache を再起動して設定の変更内容を反映させてください。

[次へ進む](#php)

<a name="setup-apache-win"></a>
### Windows の場合

Windows の場合、変更したい箇所は以下のみです。

* ドキュメントルートを扱いやすい場所に変更する

設定ファイル `C:¥xampp¥apache¥conf¥httpd.conf` をテキストエディタで開いて編集してください。（XAMPP の `Config` ボタンからも開けます）

#### ドキュメントルートを変更する

ドキュメントルートを設定している箇所を以下のように変更してください。

```
#DocumentRoot "C:/xampp/htdocs"
#<Directory "C:/xampp/htdocs">
DocumentRoot "C:/Users/あなたのユーザ名/workspace"
<Directory "C:/Users/あなたのユーザ名/workspace">
```

> 既存の行を `#` でコメントアウトして、ホームディレクトリ直下の `workspace` というディレクトリがドキュメントルートになるように設定し直しています。

#### 設定の変更を反映させる

ここまでの変更を保存したら、最後に、ドキュメントルートとして設定したディレクトリ（`~/workspace`）を実際に作成します。

```bash
$ cd ~
$ mkdir workspace
```

作成したら、Apache を再起動して設定の変更内容を反映させてください。

[次へ進む](#php)

<a name="php"></a>
## PHP のページを表示させてみる

ここまでで、PHP が実行可能な Web サーバの構築が完了しました。次は実際に動かしてみましょう。

ドキュメントルート（`~/workspace`）直下に `index.php` というファイルを作成して、以下の内容を記述してください。

```php
<?php
phpinfo();
?>
```

これを保存したら、ブラウザで [http://localhost](http://localhost) にアクセスしてみてください。

[こんな感じの画面](https://www.google.co.jp/search?q=phpinfo&tbm=isch) が表示されれば OK です。

[phpinfo](http://php.net/manual/ja/function.phpinfo.php) という PHP の組み込み関数を実行した結果を表示する、最も基礎的な Web アプリの実行に成功しました！おめでとうございます！
