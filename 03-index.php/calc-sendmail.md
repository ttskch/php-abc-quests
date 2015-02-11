# 簡易電卓プログラムの計算結果をメール送信してみる

ここで、実用的な Web アプリケーションの練習として、PHP のプログラムからメールを送信する方法を学んでおきたいと思います。

あまり意味のある実装ではないですが、簡易電卓プログラムに計算結果をメールで送信する処理を追加してみましょう。

## 下準備

PHP のプログラムからメールを送信するには、いくつかの下準備が必要になります。
下準備の内容は Mac と Windows で異なりますので、それぞれの環境に合わせて実施してください。

なお、Mac, Windows どちらの場合も、Gmail の SMTP サーバを利用するため、Gmail アカウントが必要になります。
Gmail アカウントを持っていない方は [こちらで](https://accounts.google.com/SignUp?service=mail&hl=ja) アカウントを取得しておいてください。

> なお、Google アカウントで 2 段階認証を設定している場合は、通常のパスワードの代わりに `アプリ パスワード` が必要になります。
> 2 段階認証を設定している Google アカウントを使用する場合は、[こちらで](https://security.google.com/settings/security/apppasswords) `アプリ パスワード` を作成してメモしておいてください。（[参考](https://support.google.com/accounts/answer/185833)）
> また、以降の設定方法の説明に示す `Gmail のパスワード` は `アプリ パスワード` に読み替えて設定を実施してください。

* [Mac の場合](#mac)
* [Windows の場合](#win)

<a name="mac"></a>
### Mac の場合

Mac には Apache や PHP が最初からインストールされていたのと同様に、Postfix というメールサーバが最初からインストールされています。
なので、この Postfix を起動するだけでとりあえずは PHP からメール送信ができるようになります。

```bash
$ sudo postfix start
```

しかし、デフォルトの設定のままで使用すると、スパムフィルタに引っかかって Gmail などのメジャーなメールアドレス宛てに送信できなかったりと環境としては不完全なので、
少しだけ設定を変更して正常にメール送信できる環境を作っておきましょう。

具体的には、Postfix でメールを送信する際に、**外部の SMTP サーバ** を使用するように設定します。ここでは Gmail の SMTP サーバを使うことにします。

以下 CLI での操作になりますが、行っている内容についてはここでは特に理解しなくて OK です。間違いがないよう正確に実施してください。

#### 1. 設定ファイルに追記

```bash
$ sudo vi /etc/postfix/main.cf
```

ファイルの末尾に以下を追記してください。（コピペ推奨）

```
# added
relayhost = [smtp.gmail.com]:587
smtp_sasl_auth_enable = yes
smtp_sasl_password_maps = hash:/etc/postfix/sasl_passwd
smtp_sasl_security_options = noanonymous
smtp_sasl_tls_security_options = noanonymous
smtp_sasl_mechanism_filter = plain
smtp_use_tls = yes
```

#### 2. Gmail 認証ファイルを作成

```bash
$ sudo vi /etc/postfix/sasl_passwd
```

新しくファイルを作成し、以下のように Gmail の認証情報を記入してください。

```
[smtp.gmail.com]:587 hogehoge@gmail.com:password
```

> `hogehoge@gmail.com` の部分をあなたの Gmail アドレス、`password` の部分をあなたの Gmail パスワードに置き換えてください。

このファイルを元に、実際に送信時に使用する認証ファイルを生成します。生成後は元の平文ファイルは不要になるので、安全のため削除してください。

```bash
$ sudo postmap /etc/postfix/sasl_passwd
$ ls /etc/postfix/sasl_passwd.db     # .db ファイルが作成されていることを確認
$ sudo rm /etc/postfix/sasl_passwd   # もう用済みなので削除
```

#### 3. Postfix を再起動

```bash
$ sudo postfix stop
$ sudo postfix start
```

> PC を再起動すると Postfix も終了してしまうので、注意してください。

以上で下準備は完了です。

[次へ進む](#setting)

<a name="win"></a>
### Windows の場合

`php.ini` および `sendmail.ini` という 2 つの設定ファイルを編集しますが、行っている内容についてはここでは特に理解しなくて OK です。
間違いがないよう正確に実施してください。

#### php.ini の設定変更

`C:\xampp\php\php.ini` に、PHP の設定ファイルがあります。これをテキストエディタで開いてください。（XAMPP のウィンドウの Apache の `Config` ボタンからも開けます）

開いたら、以下の箇所を

```
; 実際には前後にコメント行があります
;sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"

sendmail_path="C:\xampp\mailtodisk\mailtodisk.exe"
```

以下のように変更してください。（コメントアウトしている行を入れ替え）

```
sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"

;sendmail_path="C:\xampp\mailtodisk\mailtodisk.exe"
```

#### sendmail.ini の設定変更

次に、メール送信モジュール sendmail の設定を行います。
sendmail の設定ファイルは `C:\xampp\sendmail\sendmail.ini` にあります。

このファイルには、sendmail モジュールがメール送信に利用する **外部の SMTP サーバ** の情報を設定します。ここでは Gmail の SMTP サーバを使うことにします。

`C:\xampp\sendmail\sendmail.ini` をテキストエディタで開いて、以下の設定項目を見つけてください。

```
; 実際には 4 つバラバラの位置にあります
smtp_server=mail.mydomain.com
smtp_port=25
auth_username=
auth_password=
```

これらの項目を、それぞれ以下のように設定してください。

```
smtp_server=smtp.gmail.com

smtp_port=587

auth_username=[Gmail のメールアドレス]
; 例: auth_username=hogehoge@gmail.com

auth_password=[Gmail のパスワード]
; 例: auth_password=password
```

以上で下準備は完了です。Apache を忘れずに再起動しておいてください。

[次へ進む](#setting)

<a name="setting"></a>
## 設定ファイルを準備

さて、これからメール送信のプログラムを書いていくわけですが、当然、実際に送信したメールを自分で受け取るためには、プログラムのソースコードの中に送信先となる自分のメールアドレスを記入する必要があります。

ソースコードは Git で管理しているので、push すれば **GitHub 上で公開されてしまいます**。これは避けたいですよね。

そこで、ワークツリー上に **Git で管理しないファイル** を用意して、公開したくない情報はそのファイルに記載しておき、プログラムからそのファイルの情報を読み込んで使う、という方法をとることにします。

```
~/workspace/php-abc-quests/practices/secret-settings.php.placeholder
```

というファイルがあるので、これを `secret-settings.php` というファイル名でコピーしてください。

```sh
$ cd ~/workspace/php-abc-quests/practices
$ cp secret-settings.php.placeholder secret-settings.php
$ cd -
```

コピーした `secret-settings.php` は、実は既に **Git の管理対象外とするよう設定されています**。
以下のファイルを見てください。

```
~/workspace/php-abc-quests/practices/.gitignore
```

`.gitignore` というファイルは、Git の管理対象外としたいファイルを指定するための特殊なファイルです。
中には以下のように書いてあります。

```
secret-settings.php
```

つまり、この `.gitignore` と同じ階層に置かれている `secret-settings.php` というファイルを Git 管理対象外とする、という設定がされているわけです。
`git status` して確認してみてください。ワークツリー上には `secret-settings.php` が追加されているのに、変更としては検出されないはずです。

現状、`secret-settings.php` の中身は

```php
return array(
    'email' => 'hogehoge@gmail.com',

    // 必要に応じて自由に設定を追記してください
);
```

のようになっています。この `'hogehoge@gmail.com'` の部分をあなたの実際のメールアドレスに変更しておいてください。

これから作るプログラムでは、このファイルを読み込んでメールアドレスを使用します。
ここに設定したメールアドレスがメールの送信先になりますので、間違いには十分に注意してください。

> ##### ポイント :bulb:
>
> このように「プログラムの実行には必要だけど Git では管理しない」ようなファイルがある場合、リモートリポジトリを初めて `git clone` する際にそのファイルを忘れずに作成しなければなりません。
> 複数のマシンで開発を行う場合などは意外と忘れがちなので、十分に気をつけましょう。

## 基本編

それではいよいよメール送信のプログラムを書いてみましょう。

```
~/workspace/php-abc-quests/practices/03/calc/index.php
```

既存の関数電卓プログラムの PHP 部分に以下のような処理を追記してみてください。

```php
<?php

    // ...

    $result = "{$left} {$operator} {$right} = {$answer}";

    // 設定ファイルを読み込み.
    $settings = require __DIR__ . '/../../secret-settings.php';

    // 計算結果をメールで送信.
    mb_language('Japanese');
    mb_internal_encoding('UTF-8');
    mb_send_mail($settings['email'], '計算結果', $result, 'From: ' . mb_encode_mimeheader('簡易電卓プログラム') . ' <no-reply@example.com>');

} else {
    $result = '計算結果なし';
}
?>
```

では、早速、実際にメールを送信してみましょう。

冒頭の `if` 文の内側にメール送信処理を書いたので、パラメータ付きでアクセスされた（計算が行われた）ときにだけメールが送信されます。ブラウザで何か値を入力して `計算する` ボタンをクリックしてみてください。

`簡易電卓プログラム` という差出人から、件名が `計算結果`、本文が入力した計算の結果となっているメールが届いたでしょうか。

> ##### メールが送信されない場合
>
> Windows ユーザの方で、[Avast](https://www.avast.co.jp/index) という無料のアンチウィルスソフトを入れている場合に、Avast がメールの送信をブロックしてしまうという現象が報告されています。
>
> この場合は、`sendmail.ini` の設定で
>
> ```
> smtp_port=587
> ```
>
> と書いたところを、
>
> ```
> smtp_port=465
> ```
>
> と変更してから Apache を再起動して、再度実行してみてください。
>
> --
>
> ##### 差出人メールアドレスについて
>
> 届いたメールの差出人メールアドレスは、PHP で指定した `no-reply@example.com` ではなく、あなたの Gmail アドレスになっていると思います。
>
> 今回の環境では Gmail の SMTP サーバを使ってメールを送信しているので、Gmail の仕様により **差出人のメールアドレスは PHP 上で何を指定しても常にあなたの Gmail アドレスになってしまいます。**
> もう少し先へ進めばこの問題は解決しますので、今は気にしないでください :bow:

### 解説

付け足したコードは 4 行だけです。

```php
$settings = require __DIR__ . '/../../secret-settings.php';
```

これは先ほど説明した設定ファイルの読み込み箇所ですね。`secret-settings.php` で `return` された配列が `$settings` に格納されます。

```php
mb_language('Japanese');
mb_internal_encoding('UTF-8');
```

この 2 行は、次の `mb_send_mail()` 関数を使うためのおまじないみたいなものです。今の時点では気にしなくて OK です。

```php
mb_send_mail($settings['email'], '計算結果', $result, 'From: ' . mb_encode_mimeheader('簡易電卓プログラム') . ' <no-reply@example.com>');
```

この一行で、PHP の [mb\_send\_mail()](http://php.net/manual/ja/function.mb-send-mail.php) 関数を使ってメールを実際に送信しています。

`mb_send_mail()` の引数は、前から順に

* 送信先メールアドレス
* 件名
* 本文
* メールヘッダに追加したい文字列（オプション）

です。

E メールの仕様として、メールヘッダに ascii 以外の文字（日本語など）を記載したい場合は MIME エンコードする必要があるため、

```php
'From: 簡易電卓プログラム <no-reply@example.com>'
```

ではなく

```php
'From: ' . mb_encode_mimeheader('簡易電卓プログラム') . ' <no-reply@example.com>'
```

となっています。

## 応用編

さて、今のままだと、計算を実行するたびに毎回メールが送信されてしまいますね。

これでは面白くない（というか迷惑 :sweat_smile: ）ので、以下のような仕様を追加してみましょう。

* 計算を実行するためのフォームとは別に、メールを送信するためのフォームがある（メソッドは `POST`）
* 通常はメール送信フォームは画面に出力されない
* 計算結果が 100 の倍数になったときのみ、メール送信フォームが出現する
* メール送信ボタンが押されたら、計算結果に加えて `ユーザの IP アドレス` を本文に記載して、Web ページ管理者（あなた）宛てにメールを送信する

ちょっとやることが多くて難しそうに見えますが、基本的にはこれまでやってきたことの延長でできるはずです。

### ヒント

PHP では、以下のように [$_SERVER](http://php.net/manual/ja/reserved.variables.server.php) というグローバル変数からクライアントの IP アドレスが取得できます。

```php
$ip = $_SERVER['REMOTE_ADDR'];
```

> ローカルマシンからローカルサーバへのアクセスの場合、取得される IP アドレスは `127.0.0.1` または `::1` となります。これらは [ループバックアドレス](http://ja.wikipedia.org/wiki/Localhost) という、自分自身を表す特殊な IP アドレスです。

また、メール送信フォームから送られてくるリクエストは、計算を実行したリクエストとは全くの別物なので、メール送信フォームの `<input>` タグで改めて計算結果の文字列を渡す必要があります。

画面に表示せずに値だけを送信したい場合は、`<input type="hidden" name="" value=""/>` が使えます。

### 解答例

解答例は [こちら](calc-sendmail-advanced.md) にあります。ぜひ、自力でチャレンジしてから見るようにしてください。
