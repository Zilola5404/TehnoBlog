{extends file='layout.tpl'}

{block name=content}
    {* Главная страница: блок hero и секции по категориям с последними постами *}
    <section class="hero">
        <div>
            <p class="eyebrow">ИТ, дизайн и технологии</p>
            <h1>Футуристичный блог о цифровых продуктах и интерфейсах</h1>
            <p>Практические статьи о разработке, UX, данных, команде и росте сервисов. Всё на русском и без лишней воды.</p>
            <a class="button pulse" href="#posts">Начать читать</a>
        </div>
    </section>

    <div id="posts" class="category-sections">
        {foreach $sections as $section}
            <section class="category-section">
                <div class="section-heading">
                    <div>
                        <h2>{$section.name}</h2>
                        <p>{$section.description}</p>
                    </div>
                    <a class="button" href="/category/{$section.slug}">Все статьи</a>
                </div>

                <div class="post-grid">
                    {foreach $section.posts as $post}
                        {include file='partials/post_card.tpl' post=$post}
                    {/foreach}
                </div>
            </section>
        {/foreach}
    </div>
{/block}
