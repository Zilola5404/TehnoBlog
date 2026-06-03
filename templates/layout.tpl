{* Основной шаблон сайта — подключает CSS/JS, шапку, общий лейаут и блок `content`. *}
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{$metaDescription|default:'Футуристичный блог о разработке, дизайне, технологиях и цифровых продуктах.'}">
    <title>{$title|default:'ТехноБлог'}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css?v={}">
    <script src="https://unpkg.com/three@0.160.0/build/three.min.js" defer></script>
    <script src="/assets/js/app.js?v={}" defer></script>
</head>
<body>
<canvas id="three-bg" aria-hidden="true"></canvas>
<div class="scene3d" aria-hidden="true">
    <span></span>
    <span></span>
    <span></span>
</div>

<header class="site-header">
    <a class="brand" href="/">ТехноБлог</a>
    <a class="start-link" href="/#posts">Читать статьи</a>
</header>

<main class="page">
    {block name=content}{/block}
</main>
</body>
</html>
