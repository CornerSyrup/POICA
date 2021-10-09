# POICA

スイカを使った学校のポータルサイト。HEW2020に向けたグループ制作作品。

## グループメンバ

- 趙澤 (GitHub@ths95049, リーダ)
- 飯島聖也 (GitHub@ths90234)
- 折田直彦 (GitHub@on0302)
- 小林宏明 (GitHub@hiro90850)
- 山下由里子 (GitHub@yy9889)

## プロジェクト構造

```txt
/                       
├───/docs                       ドキュメント
|   ├───/API                    REST API関連
|   └───Database Schema.drawio  データベースのスキーマ
├───/src                        ソースファイル
│   ├───/client                 クライアントサイド関連
│   |   ├───/page               PUGページ
│   |   ├───/script             ReactJSスクリプト
│   |   └───/style              lessスタイル
|   ├───/database               SQLスクリプト
|   └───/server                 サーバサイド関連
|       ├───/model              モデル
|       └───/...                API別
└───/www                        PHPサーバのCGIコード
    ├───/controller             コントローラ別
    └───/image                  サイトの画像
```
