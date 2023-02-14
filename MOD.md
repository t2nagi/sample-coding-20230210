変更点
=====

# レスポンスを保存するDB
テーブルの変更箇所
```sql
CREATE TABLE `ai_analysis_logs` ( -- 末尾に"s"を追加
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `image_path` varchar(255) DEFAULT NULL,
    `is_success` boolean DEFAULT NULL, -- APIのレスポンス仕様に合わせて"varchar"から"boolean"に変更、型に合わせてカラム名を変更
    `message` varchar(255) DEFAULT NULL,
    `class` int(11) DEFAULT NULL,
    `confidence` decimal(5, 4) DEFAULT NULL,
    `request_timestamp` int(10) unsigned DEFAULT NULL,
    `response_timestamp` int(10) unsigned DEFAULT NULL,
    `created_at` timestamp default current_timestamp, -- テーブルの作成日時を追加
    `updated_at` timestamp default current_timestamp on update current_timestamp, -- テーブルの作成日時を追加
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
```

# API仕様 リクエスト
* URLベース:http://example.com/
* リクエスト:POST
* パラメーター
    * image_path 
    * String
    * 画像ファイルPath  
例) /image/d03f1d36ca69348c51aa/c413eac329e1c0d03/test.jpg  
↑ ファイルPathがローカルパスになっていたため、AWS S3に一度アップしS3のパスを利用するよう仕様を変更 