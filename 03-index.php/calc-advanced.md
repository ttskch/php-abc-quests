# 簡易電卓プログラム応用編の解答例

```php
<?php
$left = isset($_GET['left']) ? $_GET['left'] : '';
$operator = isset($_GET['operator']) ? $_GET['operator'] : '+';
$right = isset($_GET['right']) ? $_GET['right'] : '';

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
} else {
    $result = '計算結果なし';
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
    <input type="text" name="left" value="<?php echo $left; ?>" required autofocus/>
    <select name="operator">
        <option value="+" <?php if ($operator === '+') { echo 'selected'; } ?>>+</option>
        <option value="-" <?php if ($operator === '-') { echo 'selected'; } ?>>-</option>
        <option value="*" <?php if ($operator === '*') { echo 'selected'; } ?>>*</option>
        <option value="/" <?php if ($operator === '/') { echo 'selected'; } ?>>/</option>
    </select>
    <input type="text" name="right" value="<?php echo $right; ?>" required/>
    <input type="submit" value="計算する">
</form>
<p><?php echo $result; ?></p>
</body>
</html>
```

## 参考

* [is_null()](http://php.net/manual/ja/function.is-null.php)
* [三項演算子](http://php.net/manual/ja/language.operators.comparison.php)
