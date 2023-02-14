技術課題
=====

# 前提

* [エンジニア課題](./%E3%82%A8%E3%83%B3%E3%82%B8%E3%83%8B%E3%82%A2%E8%AA%B2%E9%A1%8C.pdf)
* 前提条件の変更点 - [MOD.md](MOD.md)
  

# 動作確認手順

## 環境構築
以下を実行する環境にインストールする。
 * Git
 * Docker ※ 最新バージョン
 * Docker-Compose ※ 最新バージョン

## 確認手順
1. プロジェクトをgithubからCloneします。
```bash
$ git clone 
```
2. プロジェクトディレクトリへ移動します。
```bash
$ cd ./sample-coding-20230210
```
3. docker-composeでコンテナを起動します。
```bash
$ docker-compose up -d
$ docker-compose logs -f
# logでコンテナの起動を確認します。
# ---------------------------
# api-mock      | INFO:     Uvicorn running on http://0.0.0.0:80 (Press CTRL+C to quit)
# api-mock      | INFO:     Started reloader process [40] using WatchFiles
# api-mock      | INFO:     Started server process [42]
# api-mock      | INFO:     Waiting for application startup.
# api-mock      | INFO:     Application startup complete.
# ※ ここまで完了
```

4. (初回起動のみ) PHPパッケージのインストールします。
```bash
$ docker exec php \
  composer install 
```

5. artisanで使用してコンソール処理を起動します。(AI分析のリクエスト成功パターン)
```bash
$ docker exec php \
  php artisan command:image:analyse /volume/success_img.png
```
6. artisanで使用してコンソール処理を起動します。(AI分析のリクエスト失敗パターン)
```bash
$ docker exec php \
  php artisan command:image:analyse /volume/failure_img.png
```
7. テーブルの内容を確認します。
```bash
$ docker exec mysql \
  mysql -Dproblem -e "select * from ai_analysis_logs;"
```

# 実装言語/FW
* 課題の実装言語
   * 言語：PHP 8
   * FW：Laravel 8  
* AI分析用APIモック
   * 言語：Python
   * FW：FastAPI

# 各Containerの役割
| Container名 | 役割                        |
| ----------- | --------------------------- |
| php         | 本課題の実行サーバ          |
| api-mock    | AI分析用APIのモックサーバ   |
| mysql       | APIのレスポンスを格納するDB |
| localstack  | 画像ファイル共有用のS3      |

# 編集ファイル
```
sample-coding-20230210
 ├── README.md
 ├── api-mock                                               ← モックAPIディレクトリ
 │   ├── Dockerfile
 │   ├── main.py                                            ← モックAPIコード
 │   └── startup.sh                                         ← 起動スクリプト
 ├── docker-compose.yml
 ├── localstack                                             ← localstackディレクトリ(S3を利用)
 │   ├── Dockerfile
 │   └── initaws.d
 │       └── 00_init.sh                                     ← bucket作成スクリプト
 ├── mysql
 │   └── initdb.d                                           ← Mysqlディレクトリ
 │       └── 00_init.sql                                    ← テーブル作成スクリプト
 ├── php
 │   ├── Dockerfile
 │   ├── app
 │   │   ├── Console
 │   │   │   ├── Commands
 │   │   │       └── ImageAnalyseCommand.php                ← Batchコード
 │   │   ├── Domains
 │   │   │   ├── Entities
 │   │   │   │   └── AiAnalysisLogEntity.php                ← Entityコード
 │   │   │   ├── Repositories
 │   │   │   │   └── AiAnalysisLogRepository.php            ← Repositoryコード
 │   │   │   └── UseCases
 │   │   │       └── ImageAnalyseUserCase.php               ← UserCaseコード
 │   │   ├── Infrastructure
 │   │   │   ├── Repositories
 │   │   │   │   └── IAiAnalysisLogRepository.php           ← Repository Interface
 │   │   │   └── UseCases
 │   │   │       └── IImageAnalyseUserCase.php              ← UserCase Interface
 │   │   └── Models
 │   │       └── AiAnalysisLog.php                          ← Modelコード(ORM)
 │   └── tests
 │       └── Unit
 │           └── App
 │               ├── Console
 │               │   └── Commands
 │               │       └── AiAnalysisCommandTest.php      ← Batchテストコード
 │               └── Domains
 │                   └── UseCases
 │                       └── ImageAnalyseUserCaseTest.php   ← UserCaseテストコード
 ├── volume
 │   ├── failure_img.png                                    ← テスト用ファイル
 │   └── success_img.png                                    ← テスト用ファイル
 └── エンジニア課題.pdf
```

# テスト
 phpコードのテスト実行
```bash
$ docker exec php \
  php artisan test 
```
