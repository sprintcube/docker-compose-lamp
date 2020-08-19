# Outdated
# Docker Compose を用いた LAMP 環境の構築方法

![Landing Page](https://preview.ibb.co/gOTa0y/LAMP_STACK.png)

Docker Composeを使用して構築された基本的なLAMPスタック環境です。構成要素は次のとおりです。

* PHP
* Apache
* MySQL
* phpMyAdmin
* Redis

現時点では、PHP のバージョンごとに異なるブランチがあります。必要な PHP のバージョンに応じて適切なブランチを使用します。

* [5.4.x](https://github.com/sprintcube/docker-compose-lamp/tree/5.4.x)
* [5.6.x](https://github.com/sprintcube/docker-compose-lamp/tree/5.6.x)
* [7.1.x](https://github.com/sprintcube/docker-compose-lamp/tree/7.1.x)
* [7.2.x](https://github.com/sprintcube/docker-compose-lamp/tree/7.2.x)
* [7.3.x](https://github.com/sprintcube/docker-compose-lamp/tree/7.3.x)
* [7.4.x](https://github.com/sprintcube/docker-compose-lamp/tree/7.4.x)

## インストール

このリポジトリをローカルコンピュータにコピーして、7.4.x などの適切なブランチをチェックアウトします。
`docker-compose up -d` を実行します。


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

該当するバージョンのブランチの README.md を読んでください。
