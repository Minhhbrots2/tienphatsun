{if $template eq 'add'}
<li class="link-list-group-item ui-sortable-handle" uid="{$uid}">
	<div class="ui-stack--spacing-none sortable-menu-item">
		<input type="hidden" id="{$uid}_id" name="links[{$order_no}][id]" value="0" />
		<input type="hidden" id="{$uid}_order_no" name="links[{$order_no}][order_no]" value="{$order_no}" />
		<div class="row w-100">
			<div class="col-md-1">
				<span class="ui-sortable__handle mt">
					<svg class="next-icon next-icon--color-slate-lighter next-icon--size-12 drag-handle"> 
						<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#next-drag-handle">
							<svg id="next-drag-handle"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Drag-Handle</title><path d="M7 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm6-8c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 2c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2z"></path></svg></svg>
						</use> 
					</svg>
				</span>
			</div>
			<div class="col-md-9">
				<div class="menu-item-name form-horizontal menu-item__form-wrapper">
					<div class="form-group">
						<div class="col-md-12">
							<input type="text" id="{$uid}_title" name="links[{$order_no}][title]" required="true" class="form-control require" placeholder="Nhập tên liên kết" />
						</div>
					</div>
					<div class="form-group mb-0">
						<div class="col-md-5">
							<select id="{$uid}_type" class="form-control" onChange="handler_linktype_change(this);" order_no="{$order_no}" uid="{$uid}" name="links[{$order_no}][type]">
								{foreach from=$lstType key = _type item = text}
								<option{if $_type eq 'frontpage'} selected="selected"{/if} value="{$_type}">{$text}</option>
								{/foreach}
							</select>
						</div>
						<div class="col-md-7" id="{$uid}"></div>
					</div>
				</div>
			</div>
			<div class="col-md-2 text-center">
				<button class="btn btn-default" onClick="remove_link(this)" style="padding:9px 10px;" type="button">
					{$core->makeIcon('trash')}
				</button>
			</div>
		</div>
	</div>
</li>
{elseif $template eq 'handler'}
	{if $holderG eq 'http'}
	<input type="text" class="form-control require" required="true" id="{$uid}_url" name="links[{$order_no}][url]" />
	{else}
	<div class="ui-select__wrapper">
		<div id="{$uid}_dropdown" class="dropdown mega-dropdown">
			<button class="ui-select dropdown-toggle fixed-width btn-filter btn-choose-product" data-toggle="dropdown">
				<span  class="choosed-single">Chọn</span>
				<svg class="next-icon next-icon--size-16">
					<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#select-chevron">
						<svg id="select-chevron"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
							<path d="M10 16l-4-4h8l-4 4zm0-12L6 8h8l-4-4z"></path></svg>
						</svg>
					</use>
				</svg>
			</button>
			<div class="dropdown-menu mega-dropdown-menu">
				<div class="dropdown-panel-body">
					<div class="form-group">
						<div class="col-md-12">
							<div class="input-group">
								<span class="input-group-addon">{$core->makeIcon('search')}</span>
								<input type="text" data-uid="{$uid}" data-url="{$_url}" placeholder="Tìm kiếm" class="form-control input-search" />
							</div>
						</div>
					</div>
					<div class="build {$uid}">Loading...</div>
				</div>
				<div class="dropdown-panel-footer">
					<div class="button-group pull-right">
						<button type="button" data-url="{$_url}" disabled data-uid="{$uid}" class="btn btn-default">{$core->makeIcon('arrow-left')}</button>
						<button type="button" data-url="{$_url}" data-uid="{$uid}" class="btn btn-default">{$core->makeIcon('arrow-right')}</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	{/if}
{/if}