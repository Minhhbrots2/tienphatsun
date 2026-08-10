<ul class="list-result-search">
	{if $lstProduct[0].product_id ne ''}
    {section name=i loop=$lstProduct}
    {assign var = _pName value = $clsProduct->getTitle($lstProduct[i].product_id)}
    {assign var = _pLink value = $clsProduct->getLink($lstProduct[i].product_id)}
    <li class="list-result-search__item">
        <div class="list-result-search__item__left">
            <a class="photo" href="{$_pLink}" title="{$_pName}">
                <img src="{$clsProduct->getImage($lstProduct[i].product_id,60,60)}" width="60px" height="60px" alt="{$_pName}" />
            </a>
        </div>
        <div class="list-result-search__item__right">
            <h3 class="list-result-search__item__title"><a href="{$_pLink}" title="{$_pName}">{$_pName}</a></h3>
            <span class="list-result-search__item__price">Giá: {$clsProduct->getPrice($lstProduct[i].product_id,true)} {$clsISO->getRate()}</span>
        </div>
    </li>
    {/section}
    {else}
    <li>
    	Không có sản phẩm nào...
    </li>
    {/if}
</ul>