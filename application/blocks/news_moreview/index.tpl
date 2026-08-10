<div class="news_more_view corner4px">
    <h2 class="headbox">Tin xem nhiều</h2>
    <div class="contentbox">
        <ul class="news_more_view_list">
            {section name=i loop=$list_more_news}
                <li><a href="{$clsNews->getLink($list_more_news[i].news_id)}" title="{$clsNews->getTitle($list_more_news[i].news_id)}">{$clsNews->getTitle($list_more_news[i].news_id)}</a></li>
            {/section}
        </ul>
    </div>
</div>