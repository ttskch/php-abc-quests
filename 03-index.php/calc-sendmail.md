# 簡易電卓プログラムの計算結果をメール送信してみる

ここで、実用的な Web アプリケーションの練習として、PHP のプログラムからメールを送信する方法を学んでおきたいと思います。

あまり意味のある実装ではないですが、簡易電卓プログラムに計算結果をメールで送信する処理を追加してみましょう。

## 下準備

PHP のプログラムからメールを送信するには、いくつかの下準備が必要になります。
下準備の内容は Mac と Windows で異なりますので、それぞれの環境に合わせて以下のとおり実施してください。

* [Mac の場合](#mac)
* [Windows の場合](#win)

<a name="mac"></a>
### Mac の場合

Mac には Apache や PHP が最初からインストールされていたのと同様に、Postfix というメールサーバが最初からインストールされています。
なので、Mac の場合の事前準備はこの Postfix を起動する作業だけです。（PC を再起動すると Apache と同様 Postfix も終了してしまうので、注意してください）

```bash
$ sudo postfix start
```

[次へ進む](#basic)

<a name="win"></a>
### Windows の場合

Windows の場合、メールを送信できるようにするのは少し大変です。
ここでやっていることの意味は今はまだ理解できなくてもよいので、以下を着実に実施してください。

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

ここには、sendmail モジュールがメール送信に利用する **外部の SMTP サーバ** の情報を設定します。
一般的な例としてここでは Gmail の SMTP サーバを使うことにします。
Gmail アカウントを持っていない方は [ここから](https://accounts.google.com/SignUp?service=mail&hl=ja) アカウントを取得しておいてください。

Gmail アカウントの取得が済んだら、`C:\xampp\sendmail\sendmail.ini` をテキストエディタで開いて、以下の設定項目を見つけてください。

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

ここまでの変更で下準備は完了です。Apache を忘れずに再起動しておいてください。

[次へ進む](#basic)

<a name="basic"></a>
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

    // 計算結果をメールで送信.
    mb_language('Japanese');
    mb_internal_encoding('UTF-8');
    mb_send_mail('hogehoge@gmail.com', '計算結果', $result, 'From: ' . mb_encode_mimeheader('簡易電卓プログラム') . ' <no-reply@example.com>');

} else {
    $result = '計算結果なし';
}
?>
```

**`hogehoge@gmail.com` の部分はあなたのメールアドレスに置き換えてください。ここが送信先のメールアドレスになります。**

宛先を書き換えたら、早速、実際にメールを送信してみましょう。

冒頭の `if` 文の内側にメール送信処理を書いたので、パラメータ付きでアクセスされた（計算が行われた）ときにだけメールが送信されます。ブラウザで何か値を入力して `計算する` ボタンをクリックしてみてください。

`簡易電卓プログラム <no-reply@example.com>` という差出人から、件名が `計算結果`、本文が入力した計算の結果となっているメールが届いたでしょうか。

> ##### Windows の方へ
>
> Windows の場合、Gmail の SMTP サーバを使ってメールを送信しているので、Gmail の仕様により **差出人のメールアドレスは PHP 上で何を指定しても常にあなたの Gmail アドレスになってしまいます。**
> もう少し先へ進めばこの問題は解決しますので、今は気にしないでください :bow:

### 解説

付け足したコードは 3 行だけです。

```php
mb_language('Japanese');
mb_internal_encoding('UTF-8');
```

まずこの 2 行は、次の `mb_send_mail()` 関数を使うためのおまじないみたいなものです。今の時点では気にしなくて OK です。

```php
mb_send_mail('hogehoge@gmail.com', '計算結果', $result, 'From: ' . mb_encode_mimeheader('簡易電卓プログラム') . ' <no-reply@example.com>');
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

また、メール送信フォームから送られてくるリクエストは、計算を実行したリクエストは全くの別物なので、メール送信フォームの `<input>` タグで改めて計算結果の文字列を渡す必要があります。

画面に表示せずに値だけを送信したい場合は、`<input type="hidden" name="" value=""/>` が使えます。

### 解答例

解答例は [こちら](calc-sendmail-advanced.md) にあります。ぜひ、自力でチャレンジしてから見るようにしてください。
