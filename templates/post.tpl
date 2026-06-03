{extends file='layout.tpl'}

{block name=content}
    {* Страница поста: заголовок, картинка, тело и блок похожих статей *}
    <article class="post">
        <header class="post__header">
            <div class="post-card__meta">
                <time datetime="{$post.published_at}">{$post.published_at|date_format:'%d.%m.%Y'}</time>
                <span>{$post.views} просмотров</span>
            </div>
            <h1>{$post.title}</h1>
            <p>{$post.description}</p>

            <div class="chips">
                {foreach $categories as $category}
                    <a href="/category/{$category.slug}">{$category.name}</a>
                {/foreach}
            </div>
        </header>

        <img class="post__image" src="{$post.image}" alt="{$post.title}">

        <div class="post__body">
            {$post.body}
        </div>
    </article>

    {if $relatedPosts}
        <section class="related">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Читайте дальше</p>
                    <h2>Похожие статьи</h2>
                </div>
            </div>

            <div class="post-grid">
                {foreach $relatedPosts as $post}
                    {include file='partials/post_card.tpl' post=$post}
                {/foreach}
            </div>
        </section>
    {/if}
{/block}
