<?php
/**
 * このファイルの使い方
 *
 * 1. secret-settings.php.placeholder をコピーして secret-settings.php を作成
 * 2. secret-settings.php に各種設定値を記入
 * 3. 他のプログラムファイルから secret-settings.php を require して設定値を利用
 *
 * 個人的なメールアドレスや、何かのパスワードなど、GitHub 上で公開されると困る情報は
 * すべてこの設定ファイルに記入してください。
 * このファイル (secret-settings.php) は Git で管理されないようにしてあるので、
 * ローカルリポジトリを GitHub に push しても、秘密の情報が公開されずに済みます。
 */

return array(
    'email' => 'hogehoge@gmail.com',

    // 必要に応じて自由に設定を追記してください
);
