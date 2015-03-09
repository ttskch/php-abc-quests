<?php
$settings = require __DIR__ . '/../secret-settings.php';

session_start();

$type = isset($_POST['type']) ? $_POST['type'] : '';
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
------------------------------------------------------------
お問い合わせ種別：{$type}
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
                'class' => 'alert alert-success',
                'message' => 'メールの送信が完了しました。',
            );

            // 入力値をクリア.
            $name = $email = $tel = $message = '';
        } else {
            $result = array(
                'class' => 'alert alert-danger',
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
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>お問い合わせフォーム</title>

    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href=".">Sample Web Site</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">お問い合わせフォーム</div>
        <div class="panel-body">
            <div class="<?php echo $result['class']; ?>"><?php echo $result['message']; ?></div>

            <form id="contact-form" class="form-horizontal" action="index.php" method="POST">
                <div class="form-group">
                    <label class="col-sm-2 control-label">お問い合わせ種別</label>
                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <input type="radio" name="type" value="ご意見" checked/>ご意見
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="type" value="ご質問"/>ご質問
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger">*</span> お名前</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" name="name" value="<?php echo h($name); ?>" placeholder="例）山田 太郎" required autofocus/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label"><span class="text-danger">*</span> メールアドレス</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="email" name="email" value="<?php echo h($email); ?>" placeholder="例）email@example.com" required/>
                    </div>
                </div>

                <div class="form-group for-question">
                    <label class="col-sm-2 control-label">お電話番号</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="tel" name="tel" value="<?php echo h($tel); ?>" placeholder="例）090-1234-5678"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">
                        <span class="text-danger">*</span>
                        <span class="for-question">ご質問内容</span>
                        <span class="for-comment">ご意見内容</span>
                    </label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="message" rows="10" placeholder="ご自由にお書きください" required><?php echo h($message); ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="pull-right">
                            <button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-send"></i> 送信</button>
                            <button class="btn btn-default" type="reset"><i class="glyphicon glyphicon-trash"></i> リセット</button>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="csrf_key" value="<?php echo generateCsrfKey(); ?>"/>
            </form>
        </div>
    </div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="script.js"></script>
</body>
</html>
