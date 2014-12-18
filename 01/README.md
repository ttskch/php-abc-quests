# 01. 自分の PC に開発環境を整えましょう

## 必要な開発環境

PHP を用いた基本的な Web 開発では、以下のような環境が必要になります。

* Apache (Web サーバ)
* PHP
* MySQL (データベースサーバ)

また、これらの環境の構築時や日々の開発の中で、コンソール（Mac なら「ターミナル」、Windows なら「コマンドプロンプト」）での操作を頻繁に使いますので、コンソールの基本的な使い方も別途勉強しておいてください。

### Apache

[Apache (アパッチ)](http://ja.wikipedia.org/wiki/Apache_HTTP_Server) は Web サーバソフトです。
これをインストールして起動すると、自分の PC が Web サーバになり、所定の場所に設置した HTML ファイルの内容をブラウザで見られるようになります。

### PHP

Apache に PHP のモジュールを追加すると、静的な HTML だけでなく PHP で作成したプログラムの出力結果をブラウザで見られるようになります。

### MySQL

[MySQL (マイエスキューエル)](http://ja.wikipedia.org/wiki/MySQL) はデータベースサーバです。
これをインストールして起動すると、自分の PC 内でデータベースを作成・管理することができるようになります。
PHP のプログラムで複雑なデータを扱いたい場合などに利用します。

## 開発環境と本番環境について

PHP でアプリケーションを作る場合、最終的には、常時起動している Web サーバ上に設置してサービスとして稼働させたい場合が多いでしょう。
開発中は自分の PC に Web サーバ環境を作って開発を進めますが、完成した際には別のサーバマシンにソースコード一式をアップロード（これを「デプロイする」と言います）して、本番環境上で動作させることになります。

その際は、サーバマシン上にも開発環境と全く同じ手順で各種環境を準備してください。

## インストール

Mac の場合と Windows の場合で環境のインストール方法が全く異なるので、それぞれについて説明します。

> ちなみに、Web 開発の現場では Mac が好んで使われます。
> 理由としては、Mac と Linux なら普通に動くのに Windows でだけ動かない、という現象が頻繁に起こることや、Mac の方が便利で快適な開発環境を作りやすいことなどが挙げられます。

* [Mac の場合](#mac)
* [Windows の場合](#windows)

<a name="mac"></a>
## Mac の場合

### Apache

Mac には初めから Apache がインストールされています。ひとまずデフォルトの設定のまま動作させてみましょう。

#### デフォルト設定のまま動かしてみる

まずは Mac 標準のコンソールである「ターミナル」を開いてください。（Launchpad の「その他」の中にあります）
開いたら、以下のように打ち込んでみてください。

```bash
$ sudo apachectl start
```

> `sudo` は一時的に管理者権限でコマンドを実行するためのコマンドです。パスワードを要求された場合は、Mac のログインパスワードを打ち込んでください。

これで、Apache が起動しました。つまり、自分の PC が Web サーバとして稼働している状態になったわけです（この状態の自分の PC のことを「ローカルサーバ」と呼んだりします）。
ということは、ブラウザに URL を打ち込めば設置されているコンテンツが表示されるはずですね。試してみましょう。

ブラウザを開いて、URL のところに `http://localhost/index.html.en` または `http://127.0.0.1/index.html.en` と入力してアクセスしてみてください。

```
It works!
```

と表示されれば成功です。

#### 意味を理解する

まず、コンソールに打ち込んだ

```bash
$ sudo apachectl start
```

ですが、これは Apache を起動するためのコマンドです。最後の `start` の部分を `stop` にすれば終了させることができ、 `restart` にすれば再起動させることができます。
今後も使うことがあるコマンドなので、覚えておいてください。

次に、Apache が起動した時にどういう原理でブラウザに `It works!` が表示されたのかを理解しておきましょう。

まず、`localhost` というホスト名や `127.0.0.1` という IP アドレスは「自分自身」を意味する特殊なもので、これを指定すればブラウザからローカルサーバにアクセスすることが出来ます。
つまり、`http://localhost/index.html.en` という URL は「ローカルサーバにある `index.html.en` というファイル」にアクセスする URL だったわけです。

では、`index.html.en` というファイルは一体どこにあるのでしょうか？

これは、Apache の設定を見れば分かります。
Apache の設定ファイルである `/etc/apache2/httpd.conf` というファイルを [Vim](http://ja.wikipedia.org/wiki/Vim) や [Emacs](http://ja.wikipedia.org/wiki/Emacs) などのテキストエディタで開いてください。

```bash
$ view /etc/apache2/httpd.conf
```

開いたら、ファイル内を検索して以下のような行を見つけてください。

```
DocumentRoot "/Library/WebServer/Documents"
```

これは、「この PC 内の `/Library/WebServer/Documents` というパスが『ドキュメントルート』だよ」ということを設定している行です。

ドキュメントルートとは、その名のとおり、コンテンツを格納しておくルートディレクトリのことです。つまり、

* `http://localhost/index.html.en` にアクセスされる → `/Library/WebServer/Documents/index.html.en` というファイルを返す
* `http://localhost/aaa/bbb/index.html.en` にアクセスされる → `/Library/WebServer/Documents/aaa/bbb/index.html.en` というファイルを返す

という具合に、URL のディレクトリ構造がそのままドキュメントルート配下のディレクトリ構造に対応するわけです。

それでは、今度はドキュメントルート直下にあるはずの `index.html.en` の中身を見てみましょう。

```bash
$ view /Library/WebServer/Documents/index.html.en
```

以下のような内容が表示されるはずです。

```html
<html><body><h1>It works!</h1></body></html>
```

先ほどブラウザに表示されたページのソースコードになっていますね。

これが、基本的な Apache の動作原理です。

#### 設定を変更する

Apache の動作をざっくり理解したところで、開発環境として使い勝手が良いように少しだけ設定を変更しておきましょう。

先ほど見た `httpd.conf` を（書き込み権限で）開いてください。

```bash
$ sudo vi /etc/apache2/httpd.conf
```

開いたら、先ほど見たドキュメントルートを設定している箇所を以下のように書き換えてください。

```
#DocumentRoot "/Library/WebServer/Documents"
DocumentRoot "~/workspace"
```

既存の行を `#` でコメントアウトして、`~/workspace` がドキュメントルートになるように設定し直しました。（この変更は必須ではありませんが、`/Library/WebServer/Documents` だと何かと不便なので変更しておくとよいです。）

ドキュメントルートを変更した場合は、以下の箇所も変更が必要なので要注意です。

```
<Directory "/Library/WebServer/Documents">
    〜略〜
</Directory>
```

ここも

```
<Directory "~/workspace">
    〜略〜
</Directory>
```

と変更してください。

ついでに、

```
<Directory "~/workspace">
    〜略〜
    AllowOverride None
    〜略〜
</Directory>
```

の部分を

```
<Directory "~/workspace">
    〜略〜
    AllowOverride All
    〜略〜
</Directory>
```

に変更しておいてください。これについては現時点では「おまじない」と思っておいて大丈夫です。（気になる人はググッてみてください）

また、さらに以下のような箇所を見つけてください。

```
<IfModule dir_module>
    DirectoryIndex index.html
</IfModule>
```

この部分は、「ファイル名を省略してアクセスされた場合に自動で表示するファイル」を設定しています。

デフォルトでは `index.html` だけになっていますが、複数設定することが可能です。
PHP で Web サイトを作る場合は `index.php` というファイルも自動で表示してほしいので、以下のように追加しておきましょう。

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

ひとまずここまでの変更を上書き保存してください。

ドキュメントルートを `~/workspace` に変更したので、実際に `~/workspace` というディレクトリを作っておく必要があります。

Finder でユーザディレクトリを開いて `workspace` というディレクトリを作ってもよいですし、コマンドで以下のようにして作成しても大丈夫です。

```bash
$ mkdir ~/workspace
```

ついでに、`~/workspace/index.html` も用意しておきましょう。中身は空っぽで構いません。コマンドなら以下のように行えます。

```bash
$ touch ~/workspace/index.html
```

最後に、`httpd.conf` を変更した場合は Apache を再起動する必要があるので忘れずに再起動しておきましょう。

```bash
$ sudo apachectl restart
```

ここまでで Apache の基本的な説明はおしまいです。

### PHP

// 準備中

### MySQL

// 準備中

<a name="windows"></a>
## Windows の場合

// 準備中

## お疲れさまでした！

以上でセクション 01 は完了です。[お疲れさまでした！](http://www.lgtm.in/g)
