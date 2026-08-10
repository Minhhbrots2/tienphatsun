{if $list_more_news[0].news_id ne ''}
<div class="box-pr-relate mt">
    <h2 class="box-pr-title">
        <span>Bài viết nổi bật</span>
    </h2>
    <div class="box-pr-content">
    	{section name=i loop=$list_more_news max=5}
        {assign var = _title value = $clsNews->getTitle($list_more_news[i].news_id)}
        {assign var = _link value = $clsNews->getLink($list_more_news[i].news_id)}
        <div class="media-box clearfix">
            <a class="article-new-img" href="{$_link}" title="{$_title}">
            	<img  class="img-fluid" src="{$clsNews->getImage($list_more_news[i].news_id,600,400)}" alt="{$_title}" />
            </a>
            <div class="article-new-ct">
                <h2 class="media-heading"><a href="{$_link}" title="{$_title}">{$_title}</a> </h2>
				<p class="time text-white">{$list_more_news[i].reg_date|date_format:"%d/%m/%Y"}</p>
            </div>
        </div>
        {/section}
    </div>
</div>
{/if}
{literal}
<style type="text/css">
	.box-pr-title{font-size:24px;position:relative;padding:0 0 10px;margin:0 0 15px}
	.box-pr-title:after{content:"";width:25px;height:3px;background:linear-gradient(to left,#2260bf,#03bcca);position:absolute;left:0;bottom:0}
	.box-pr-content{margin-top:15px}
	.box-pr-content img,.box-pr-content{border-radius:3px}
	.box-pr-content .article-new-img{display:block;width:100%;height:auto}
	.box-pr-content .article-new-ct{width:100%;position:absolute;left:0;bottom:0;background-image:linear-gradient(rgba(0,0,0,0),#000);padding:15px 15px 5px;z-index:9}
	.box-pr-content .media-box .media-heading a{color:#fff;font-size:16px}
	.box-pr-content .media-box{position:relative;border-top:unset;margin:20px 0;padding:0}
	.box-pr-content .media-box:last-child{padding-bottom:0}
	@media (min-width: 768px) and (max-width: 991px) {
		.box-pr-content{margin:0 -10px}
		.box-pr-content .media-box{margin:20px 0;width:50%;padding:0 10px;float:left;margin:0 0 20px 0}
		.box-pr-content .media-box .shadow{width:calc(100% - 20px);left:10px}
	}
</style>
{/literal}