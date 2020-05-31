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

- install
```
$ composer require laravel/ui
```

- 簡易UIの生成機能でログインフォームなどサクッと作成(ログインフォーム込みvue対応)
```
$ php artisan ui vue
$ php artisan ui vue --auth
$ npm install
$ npm run dev
```

# setup for laravel passport

- install
```
$ composer require laravel/passport
```

- チュートリアルどうりに色々編集
  - AuthServiceProvider.php
  - User.php
  - auth.php

- secretの生成
```
$ php artisan passport:install
```

- passport周りのvue component生成
```
$ php artisan vendor:publish --tag=passport-components
```

- 作成されたvue componentは、app.jsでセットアップし、home.blade.phpに反映し、webpack
```
$ npm run dev
```

