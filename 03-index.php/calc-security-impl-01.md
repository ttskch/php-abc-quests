# 簡易電卓プログラム XSS 対策版の解答例

```php
<?php
$settings = require __DIR__ . '/../../secret-settings.php';

$left = isset($_GET['left']) ? $_GET['left'] : null;
$operator = isset($_GET['operator']) ? $_GET['operator'] : '+';
$right = isset($_GET['right']) ? $_GET['right'] : null;
$result = '計算結果なし';

switch (strtolower($_SERVER['REQUEST_METHOD'])) {
    case 'post':
        if (isset($_POST['result'])) {
            $body =
                "簡易電卓プログラムの記念報告メールです。\n" .
                "\n" .
                "計算内容：{$_POST['result']}\n" .
                "IPアドレス：{$_SERVER['REMOTE_ADDR']}\n"
            ;
            mb_language('Japanese');
            mb_internal_encoding('UTF-8');
            mb_send_mail($settings['email'], '簡易電卓プログラム記念報告', $body, 'From: ' . mb_encode_mimeheader('簡易電卓プログラム') . ' <no-reply@example.com>');
        }
        break;

    case 'get':
    default:
        if (!is_null($left) && !is_null($right)) {
            switch ($operator) {
                case '-':
                    $answer = $left - $right;
                    break;
                case '*':
                    $answer = $left * $right;
                    break;
                case '/':
                    $answer = $left / $right;
                    break;
                case '+':
                default:
                    $answer = $left + $right;
                    break;
            }
            $result = "{$left} {$operator} {$right} = {$answer}";
        }
        break;
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
        <input type="text" name="left" value="<?php echo h($left); ?>" required autofocus/>
        <select name="operator">
            <option value="+" <?php if ($operator === '+') { echo 'selected'; } ?>>+</option>
            <option value="-" <?php if ($operator === '-') { echo 'selected'; } ?>>-</option>
            <option value="*" <?php if ($operator === '*') { echo 'selected'; } ?>>*</option>
            <option value="/" <?php if ($operator === '/') { echo 'selected'; } ?>>/</option>
        </select>
        <input type="text" name="right" value="<?php echo h($right); ?>" required/>
        <input type="submit" value="計算する">
    </form>
    <p><?php echo h($result); ?></p>

    <hr>

    <?php if (isset($answer) && $answer % 100 === 0) { ?>
        <p>計算結果が100の倍数になったら記念報告！</p>
        <form action="index.php" method="POST">
            <input type="hidden" name="result" value="<?php echo h($result); ?>"/>
            <input type="submit" value="メールで報告する"/>
        </form>
    <?php } ?>
</body>
</html>
<?php
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}
?>
```

## 参考

* [ユーザー定義関数](http://php.net/manual/ja/functions.user-defined.php)
