{extends file='layout.tpl'}

{block name=content}
    {* Простая 404-страница *}
    <section class="hero">
        <div>
            <p class="eyebrow">404</p>
            <h1>Страница не найдена</h1>
            <p>Такого материала нет или ссылка устарела.</p>
            <a class="button pulse" href="/">На главную</a>
        </div>
    </section>
{/block}
