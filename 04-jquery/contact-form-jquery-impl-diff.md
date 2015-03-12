# 問い合わせフォーム改造前後の差分

```diff
<?php
$settings = require __DIR__ . '/../../secret-settings.php';

session_start();

+ $type = isset($_POST['type']) ? $_POST['type'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$tel = isset($_POST['tel']) ? $_POST['tel'] : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';
$result = array(
    'class' => '',
    'message' => '',
);

if (strtolower($_SERVER['REQUEST_METHOD']) === 'post') {

    // CSRF チェック.
    if (!isset($_POST['csrf_key']) || !checkCsrfKey($_POST['csrf_key'])) {
        echo '不正なアクセスです。';
        exit;
    }

    // 必須項目のチェック.
    if (!$name || !$email || !$message) {
        $result = array(
            'class' => 'error',
            'message' => '必須項目はすべて入力してください。',
        );
    }

    // メールアドレスの簡易チェック.
    elseif ($email && !preg_match('/^[a-zA-Z0-9\.-_]+@[a-zA-Z0-9\.-_]+$/', $email)) {
        $result = array(
            'class' => 'error',
            'message' => '不正なメールアドレスです。',
        );
    }

    else {
        $content = <<<EOT
+ ------------------------------------------------------------
+ お問い合わせ種別：{$type}
  ------------------------------------------------------------
  お名前：{$name}
  ------------------------------------------------------------
  メールアドレス：{$email}
  ------------------------------------------------------------
  お電話番号：{$tel}
  ------------------------------------------------------------
  ご質問内容：
  {$message}
  ------------------------------------------------------------
EOT;

        mb_language('Japanese');
        mb_internal_encoding('UTF-8');

        // サイト管理者宛ての通知メールを送信.
        $body = "以下のお客様からお問い合わせがありました。\n\n" . $content;
        if (mb_send_mail($settings['email'], 'お問い合わせがありました', $body, 'From: ' . mb_encode_mimeheader('問い合わせフォーム') . ' <no-reply@example.com>')) {

            // 通知メールの送信が成功した場合のみ自動返信メールを送信.
            $body = "以下の内容でお問い合わせを受け付けました。\n担当者より折り返しご連絡を差し上げますので、今しばらくお待ちください。\n\n" . $content;
            mb_send_mail($email, 'お問い合わせがありがとうございました', $body, 'From: ' . mb_encode_mimeheader('問い合わせフォーム') . ' <no-reply@example.com>');

            $result = array(
                'class' => 'success',
                'message' => 'メールの送信が完了しました。',
            );

            // 入力値をクリア.
            $name = $email = $tel = $message = '';
        } else {
            $result = array(
                'class' => 'error',
                'message' => 'メールの送信に失敗しました。',
            );
        }
    }
}

function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

function generateCsrfKey()
{
    return $_SESSION['csrf_key'] = sha1(uniqid(mt_rand(), true));
}

function checkCsrfKey($key)
{
    if (!isset($key) || !isset($_SESSION['csrf_key']) || $_SESSION['csrf_key'] !== $key) {
        return false;
    }
    return true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>お問い合わせフォーム</title>
+     <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
+     <script type="text/javascript" src="script.js"></script>
+     <link rel="stylesheet" href="style.css"/>
</head>
<body>
<h1>お問い合わせフォーム</h1>

- <form action="index.php" method="POST">
+ <form id="contact-form" action="index.php" method="POST">
    <table>
        <thead>
        <tr>
            <td colspan="2" class="<?php echo $result['class']; ?>"><?php echo $result['message']; ?></td>
        </tr>
        </thead>
        <tbody>
+         <tr>
+             <th>お問い合わせ種別</th>
+             <td>
+                 <label><input type="radio" name="type" value="ご意見" checked/>ご意見</label>
+                 <label><input type="radio" name="type" value="ご質問"/>ご質問</label>
+             </td>
+         </tr>
        <tr>
            <th><span>*</span> お名前</th>
            <td><input type="text" name="name" value="<?php echo h($name); ?>" placeholder="例）山田 太郎" required autofocus/></td>
        </tr>
        <tr>
            <th><span>*</span> メールアドレス</th>
            <td><input type="email" name="email" value="<?php echo h($email); ?>" placeholder="例）email@example.com" required/></td>
        </tr>
-         <tr>
+         <tr class="for-question">
            <th>お電話番号</th>
            <td><input type="tel" name="tel" value="<?php echo h($tel); ?>" placeholder="例）090-1234-5678"/></td>
        </tr>
        <tr>
-             <th><span>*</span> ご質問内容</th>
+             <th>
+                 <div class="for-question"><span>*</span> ご質問内容</div>
+                 <div class="for-comment"><span>*</span> ご意見内容</div>
+             </th>
            <td><textarea name="message" rows="10" placeholder="ご自由にお書きください" required><?php echo h($message); ?></textarea></td>
        </tr>
        </tbody>
        <tfoot>
        <td colspan="2">
            <input type="hidden" name="csrf_key" value="<?php echo generateCsrfKey(); ?>"/>
            <input type="submit" value="送信"/>
            <input type="reset" value="リセット"/>
        </td>
        </tfoot>
    </table>
</form>
</body>
</html>
```
