{* Карточка поста — используется в списках статей и похожих материалах *}
<article class="post-card">
    <a class="post-card__image" href="/post/{$post.slug}">
        <img src="{$post.image}" alt="{$post.title}">
    </a>
    <div class="post-card__body">
        <div class="post-card__meta">
            <time datetime="{$post.published_at}">{$post.published_at|date_format:'%d.%m.%Y'}</time>
            <span>{$post.views} просмотров</span>
        </div>
        <h3><a href="/post/{$post.slug}">{$post.title}</a></h3>
        <p>{$post.description}</p>
    </div>
</article>
