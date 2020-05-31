# study_laravel
Study of Lararvel

# setup

- .envを作成し、database接続周りを修正しておく
- `php artisan config:cache`でキャッシュクリア
- `php artisan migrate:status`でmigrationの確認
- `php artisan migrate`でmigrationの実施

# make:authがなくなっていてui:authに

- laravel mixについて、npmに依存していてバージョン整合で色々ハマるので、結局nodeとnpm最新にしてmoduleインストールし直し(dockerfileに反映)
- かつ、npmのcacheが生きていることもあるので`npm cache clear --force`も念のため実行しておく

- 簡易UIの生成機能でログインフォームなどサクッと作成
```
$ composer require laravel/ui
# vue対応(ログインフォーム込み）

$ php artisan ui vue
$ php artisan ui vue --auth
$ npm install
$ npm run dev
```
