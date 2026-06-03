{extends file='layout.tpl'}

{block name=content}
    {* Страница категории: описание категории, сортировка и список постов с пагинацией *}
    <section class="category-header">
        <div>
            <p class="eyebrow">Категория</p>
            <h1>{$category.name}</h1>
            <p>{$category.description}</p>
        </div>

        <div class="sort">
            <a class="{if $sort === 'date'}is-active{/if}" href="{$dateUrl}">По дате</a>
            <a class="{if $sort === 'views'}is-active{/if}" href="{$viewsUrl}">По просмотрам</a>
        </div>
    </section>

    <div class="post-grid">
        {foreach $posts as $post}
            {include file='partials/post_card.tpl' post=$post}
        {foreachelse}
            <p class="empty">Статей пока нет.</p>
        {/foreach}
    </div>

    {if $pages > 1}
        <nav class="pagination" aria-label="Пагинация">
            {if $prevUrl}
                <a class="pagination__arrow" href="{$prevUrl}">Назад</a>
            {/if}

            {for $i=1 to $pages}
                <a class="{if $i === $page}is-active{/if}" href="{$pageUrls[$i]}">{$i}</a>
            {/for}

            {if $nextUrl}
                <a class="pagination__arrow" href="{$nextUrl}">Вперёд</a>
            {/if}
        </nav>
        <p class="pagination-summary">Страница {$page} из {$pages}. Всего статей: {$total}.</p>
    {/if}
{/block}
