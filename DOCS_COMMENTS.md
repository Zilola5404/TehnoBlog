Обзор и комментарии к файлам, где нельзя вставлять прямые комментарии (JSON)

- `composer.json` — содержит зависимости PHP и автозагрузку PSR-4. Не поддерживает комментарии в формате JSON, поэтому кратко:
  - `require`: PHP ^8.1, `smarty/smarty` для шаблонов.
  - `autoload.psr-4`: пространство имён `App\` в каталоге `app/`.
  - `scripts`: `clear-cache` запускает `php bin/clear-cache`, `build-assets` запускает `npm run build:css`.

- `package.json` — конфиг для npm/sass:
  - `scripts.build:css`: собирает `assets/scss/app.scss` в `public/assets/css/app.css` сжатым стилем.
  - Используется devDependency `sass`.

Почему сюда: JSON-файлы не позволяют комментарии — добавляю пояснение в отдельном markdown-файле.
