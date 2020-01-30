# Docker Compose を用いた LAMP 環境の構築方法

![Landing Page](https://preview.ibb.co/gOTa0y/LAMP_STACK.png)

Docker Composeを使用して構築された基本的なLAMPスタック環境です。構成要素は次のとおりです。

* PHP 7.4
* Apache 2.4
* MySQL 5.7 または MariaDB 10.3
* phpMyAdmin

## インストール

このリポジトリをローカルコンピュータにコピーして、`7.3.x` ブランチをチェックアウトした後、`docker-compose up -d` を実行します。

```shell
$ git clone https://github.com/sprintcube/docker-compose-lamp.git
$ cd docker-compose-lamp/
$ git fetch --all
$ git checkout 7.4.x
$ cp sample.env .env
$ docker-compose up -d
```

LAMPスタックの準備が整いました。`http://localhost` 経由でアクセスできます。

## 設定と利用法

このパッケージには、デフォルトの設定オプションがあります。これらは プロジェクトのルートディレクトリーに `.env` ファイルを作成することで修正できます。

簡単にするには、`sample.env` から内容をコピーするだけです。必要に応じて環境変数の値を更新します。

### 設定変数

以下の設定変数が使用可能です。独自の `.env` ファイルを上書きすることでカスタマイズできます。

_**DOCUMENT_ROOT**_

Apache サーバーのドキュメントルートです。このデフォルト値は `./www` です。すべてのサイトがここに表示され、自動的に同期されます。

_**MYSQL_DATA_DIR**_

これは MySQL のデータディレクトリです。このデフォルト値は `./data/mysql` です。すべての MySQL のデータファイルはここに格納されます。

_**VHOSTS_DIR**_

これは仮想ホスト用です。このデフォルト値は`./config/vhosts` です。ここに仮想ホストの conf ファイルを置くことができます。

> システムの `hosts` ファイルに、各仮想ホストのエントリーを必ず追加してください。

_**APACHE_LOG_DIR**_

これは Apache のログの保存に使用されます。このデフォルト値は `./logs/apache2` です。

_**MYSQL_LOG_DIR**_

これは MYSQL のログの保存に使用されます。このデフォルト値は `./logs/mysql` です。

## Web Server

Apacheは 80 番ポートで動作するように設定されていますので、`http://localhost` でアクセスできます。

#### Apache Modules

デフォルトでは、次のモジュールが有効になっています。

* rewrite
* headers

> より多くのモジュールを有効にしたい場合は `./bin/webserver/Dockerfile` を更新してください。PRを生成することもできますし、一般的な目的に適しているようであればマージします。

> `docker-compose build` を実行して docker イメージを再ビルドして、docker のコンテナを再起動する必要があります。

#### SSH 接続

`docker exec` コマンドで Web サーバーに接続し、さまざまな操作を実行できます。sshを使用してコンテナーにログインするには、以下のコマンドを使用します。

```shell
$ docker-compose exec webserver bash
```

## PHP

インストールされている PHP のバージョンは 7.4 です。

#### 拡張機能

デフォルトでは、次の拡張機能がインストールされます。

* mysqli
* pdo_sqlite
* pdo_mysql
* mbstring
* zip
* intl
* mcrypt
* curl
* json
* iconv
* xml
* xmlrpc
* gd

> 他の拡張機能をインストールしたいなら `./bin/webserver/Dockerfile` を更新してください。PR を生成することもできますし、一般的な目的に適しているようであればマージします。

> `docker-compose build` を実行してdocker イメージを再ビルドして、docker コンテナを再起動する必要があります。

## phpMyAdmin

phpMyAdmin がポート 8080 番で実行するように設定されています。次のデフォルトの認証情報を使用してください。

http://localhost:8080/  
username: root  
password: tiger

## Redis
