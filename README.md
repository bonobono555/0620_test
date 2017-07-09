■ほっこりすと

    ■概要
        ほっこりしたい人、されたい人のためのSNS
        ほっこりした出来事を共有してほっこりしあう
        
        非ログインユーザ：ユーザ一覧画面、コメント一覧画面を見ることが可能。ユーザ新規登録機能
        ログインユーザ：自分の情報を編集・削除、掲示板にコメントを投稿可能。

    ■使用方法

        1.ドキュメントルート上にこのリポジトリをクローンする
        git clone https://github.com/bonobono555/0620_test.git;

        2.gitをクローンしたディレクトリでcomposerをインストールする
        php composer.phar install

        3.[ドキュメントルート]0620_test/config/autoload/local.phpを新規作成
            --start---------------
            <?php
            return array(
                'db' => array(
                    'dsn'            => 'mysql:dbname=ec;host=localhost',        
                    'username' => 'root', //ユーザー
                    'password' => 'root', //パスワード
                ),
                'base_url' =>  'http://localhost:8888/0620_test/public',
            );
            --end---------------
        4.データベースを作成
            下記の「使用DB」を参考にインサートする。

    ■機能
        Index

            [ホスト名]/ec/index/login
            ログイン機能
            emailとpasswordを入力して [ホスト名]/ec/user/index にログインできる

            [ホスト名]/ec/index/logout
            ログアウト機能
            [ホスト名]/ec/user/indexからログアウトできる

        User

            [ホスト名]/ec/user/add
            ユーザ新規登録画面
            登録時バリデーションを実施
            emailとpasswordは必須項目

            [ホスト名]/ec/user/edit
            ユーザ情報変更画面
            ログインユーザのみ自分の情報を編集きる

            [ホスト名]/ec/user/delete
            ユーザ削除画面
            ログインユーザのみ自分の情報を削除できる

            [ホスト名]/ec/user/detail
            ユーザ詳細画面

            [ホスト名]/ec/user/index
            ユーザ一覧画面

            説明
            ec/user/index画面からユーザの登録・編集・詳細表示・削除が行える。

        Comment

            [ホスト名]/ec/comment/index
            コメント一覧画面
            ログインユーザ関係なくコメントの一覧を見ることができる

            [ホスト名]/ec/comment/add
            コメント新規投稿画面
            ログインユーザのみ投稿可能

            [ホスト名]/ec/comment/detail
            コメント詳細画面
            コメントのレスなどを見ることができる

            説明
            ログインしたユーザーでコメントの投稿可能で 
            投稿コメントにレスをつけることができる


    ■練習
        Auth

            [ホスト名]/ec/auth/login
            ログイン機能

            [ホスト名]/ec/auth/signup
            ユーザ登録画面


            説明
            [ホスト名]/ec/auth/signup にて登録したユーザで
            [ホスト名]/ec/auth/login にログインできる

        Form

            [ホスト名]/ec/form/index
            フォーム登録画面

            説明
            [ホスト名]/ec/form/index でフォーム送信すると
            次の画面でフォームの内容が表示される。

        
■使用DB

    ■データベース

        CREATE DATABASE ec;

    ■テーブル

        ・User

            CREATE TABLE `user` (
          `id` int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY COMMENT 'ID',
          `name` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT '名前',
          `email` varchar(64) CHARACTER SET utf8 DEFAULT NULL COMMENT 'メールアドレス',
          `password` varchar(16) CHARACTER SET utf8 DEFAULT NULL COMMENT 'ログインパスワード',
          `comment` text CHARACTER SET utf8 COMMENT '自己紹介文',
          `url` varchar(64) CHARACTER SET utf8 DEFAULT NULL COMMENT 'サイトアドレス'
        ) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

        ・Comment
        
            CREATE TABLE `comment` (
              `id` int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
              `title` varchar(255) CHARACTER SET utf8 NOT NULL,
              `comment` text CHARACTER SET utf8 NOT NULL,
              `parent_id` int(11) NOT NULL,
              `user_id` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

        ・People

            CREATE TABLE `people` (
            `id` int(100) unsigned  AUTO_INCREMENT NOT NULL PRIMARY KEY,
            `email` varchar(300) CHARACTER SET utf8 NOT NULL,
            `password` varchar(300) CHARACTER SET utf8 NOT NULL
          ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

          ※phpmyadminからテーブルをエクスポートする際、
           auto_increment,primary_keyが付与されないSQL文が出力されるので注意


ZendSkeletonApplication
=======================

Introduction
------------
This is a simple, skeleton application using the ZF2 MVC layer and module
systems. This application is meant to be used as a starting place for those
looking to get their feet wet with ZF2.

Installation
------------

Using Composer (recommended)
----------------------------
The recommended way to get a working copy of this project is to clone the repository
and use `composer` to install dependencies using the `create-project` command:

    curl -s https://getcomposer.org/installer | php --
    php composer.phar create-project -sdev --repository-url="https://packages.zendframework.com" zendframework/skeleton-application path/to/install

Alternately, clone the repository and manually invoke `composer` using the shipped
`composer.phar`:

    cd my/project/dir
    git clone git://github.com/zendframework/ZendSkeletonApplication.git
    cd ZendSkeletonApplication
    php composer.phar self-update
    php composer.phar install

(The `self-update` directive is to ensure you have an up-to-date `composer.phar`
available.)

Another alternative for downloading the project is to grab it via `curl`, and
then pass it to `tar`:

    cd my/project/dir
    curl -#L https://github.com/zendframework/ZendSkeletonApplication/tarball/master | tar xz --strip-components=1

You would then invoke `composer` to install dependencies per the previous
example.

Using Git submodules
--------------------
Alternatively, you can install using native git submodules:

    git clone git://github.com/zendframework/ZendSkeletonApplication.git --recursive

Web Server Setup
----------------

### PHP CLI Server

The simplest way to get started if you are using PHP 5.4 or above is to start the internal PHP cli-server in the root directory:

    php -S 0.0.0.0:8080 -t public/ public/index.php

This will start the cli-server on port 8080, and bind it to all network
interfaces.

**Note: ** The built-in CLI server is *for development only*.

### Apache Setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName zf2-tutorial.localhost
        DocumentRoot /path/to/zf2-tutorial/public
        SetEnv APPLICATION_ENV "development"
        <Directory /path/to/zf2-tutorial/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>
