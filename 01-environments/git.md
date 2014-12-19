# Git をインストールする

PHP の開発環境とは直接関係ないのですが、これから先の課題で必要なので、何も言わずにここでインストールだけ済ませておいてください :bow:

Git については次の章で説明します。

## Mac の場合

以下の手順でインストールします。

1. [Homebrew](http://ja.wikipedia.org/wiki/Homebrew_%28%E3%83%91%E3%83%83%E3%82%B1%E3%83%BC%E3%82%B8%E7%AE%A1%E7%90%86%E3%82%B7%E3%82%B9%E3%83%86%E3%83%A0%29) をインストール
2. Homebrew を使って Git をインストール

### Homebrew をインストール

Homebrew は Mac の CLI にコマンドを追加インストールするための管理ツールです。ほぼデファクトスタンダード的なツールなので、安心してインストールしてください。

Homebrew のインストールは [http://brew.sh/index_ja.html](http://brew.sh/index_ja.html) の最下部にある

```
ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
```

というような一行スクリプトをコピーして、ターミナルに貼り付けて実行するだけです。

不安な方は [こちらのページ](http://www.tettori.net/post/1442/) などを参考にしてみてください。**（ただし、ターミナルにコピペするコマンドは古いものが書かれているので要注意です）**

### Homebrew を使って Git をインストール

Homebrew をインストールしたことで、CLI 上で `brew` というコマンドが使えるようになっています。
以下のように Homebrew から Git をインストールしてください。

```bash
# 念のため Homebrew 自体を最新にアップデート
$ brew update

# git コマンドをインストール
$ brew install git
```

## Windows の場合

[Git for Windows](https://msysgit.github.io/) をインストールしてください。
