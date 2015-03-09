# 問い合わせフォームを改造する

[問い合わせフォームを作ってみる](../03-index.php/contact-form.md) で作成した問い合わせフォームを、jQuery を使って少し改造してみましょう！

```
~/workspace/php-abc-quests/practices/04/contact-form/index.php
~/workspace/php-abc-quests/practices/04/contact-form/script.js（JavaScript は別ファイル化）
~/workspace/php-abc-quests/practices/04/contact-form/style.css（ついでに CSS も別ファイル化しましょう）
```

現状の問い合わせフォームに、以下の仕様を追加してください。

1. フォームを送信しようとしたときに「送信しますか？」という確認ダイアログを表示し、続けて「はい」をクリックした場合のみフォームを送信する
2. 「お問い合わせ種別」という項目を追加し、「ご意見」「ご質問」から選択できるようにする
3. 「ご意見」が選択されたら、「お電話番号」の項目を非表示にし、「ご質問内容」という項目名を「ご意見内容」に書き換える
4. 「ご質問」が選択されたら「お電話番号」の項目を再度表示し、「ご意見内容」という項目名を「ご質問内容」に書き換える
5. 最初にページを開いた時点では「ご意見」が選択された状態にする

完成イメージは以下のとおりです。

![image](https://cloud.githubusercontent.com/assets/4360663/6550866/78aec2d8-c672-11e4-8536-74d0b432ea6d.png)

![image](https://cloud.githubusercontent.com/assets/4360663/6550876/97f77626-c672-11e4-8660-ad6f26685347.png)

![image](https://cloud.githubusercontent.com/assets/4360663/6550884/c2a1a3f6-c672-11e4-9b77-eb2e7ebc9bc7.png)

## ヒント

* CSS を別ファイル化するには [`<link>`](http://www.htmq.com/html5/link.shtml) タグを使います
* フォームの送信は `submit` イベントで検知することができます
* 確認ダイアログは `confirm()` という JavaScript の関数で出力することができます
* DOM 要素の表示・非表示には `show()` `hide()` という jQuery メソッドを使います
* CSS の `display: none;` を使うと要素をはじめから非表示にしておくことができます

## 解答例

解答例は [こちら](contact-form-jquery-impl.md) です。ぜひ、自力でチャレンジしてから見てください。

## 作成完了した人へ

お疲れさまでした！ここまでで、jQuery を使ったフロントエンド開発の基礎は完了です！

PHP と JavaScript でサーバサイドとクライアントサイドそれぞれで動的な変化を実装するスキルが身につきましたね。
基本的には、ここまで学んできた内容の応用で、かなり色々なことができるはずです。ぜひ色々作って遊んでみてください。

次章では Bootstrap という CSS フレームワークを使って、デザインが苦手なプログラマでも、手を抜きつつもかっこいい見た目のサイトを作る方法を学びます。お楽しみに！
