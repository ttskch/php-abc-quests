# 簡易電卓プログラム応用編の解答例

```php
<?php
$left = isset($_POST['left']) ? $_POST['left'] : '';
$operator = isset($_POST['operator']) ? $_POST['operator'] : '';
$right = isset($_POST['right']) ? $_POST['right'] : '';

if ($left && $operator && $right) {
    switch ($operator) {
        case '−':
            $answer = $left - $right;
            break;
        case '×':
            $answer = $left * $right;
            break;
        case '÷':
            $answer = $left / $right;
            break;
        case '＋':
        default:
            $answer = $left + $right;
            break;
    }

    $result = "{$left} {$operator} {$right} ＝ {$answer}";
} else {
    $result = '計算結果なし';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>test</title>
</head>
<body>
    <form action="index.php" method="post">
        <input type="text" name="left" value="<?php echo $left; ?>" required autofocus/>
        <select name="operator">
            <option value="＋" <?php if ($operator === '＋') { echo 'selected'; } ?>>＋</option>
            <option value="−" <?php if ($operator === '−') { echo 'selected'; } ?>>−</option>
            <option value="×" <?php if ($operator === '×') { echo 'selected'; } ?>>×</option>
            <option value="÷" <?php if ($operator === '÷') { echo 'selected'; } ?>>÷</option>
        </select>
        <input type="text" name="right" value="<?php echo $right; ?>" required/>
        <input type="submit" value="計算する">
    </form>
    <p><?php echo $result; ?></p>
</body>
</html>
```

## 参考

* [三項演算子](http://php.net/manual/ja/language.operators.comparison.php)
