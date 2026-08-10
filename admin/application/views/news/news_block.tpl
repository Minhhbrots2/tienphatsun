{literal}
<script type="text/javascript">
	$().ready(function(){
		$_document.on('change', '.selectBlock', function(ev){
			var $_this = $(this),
				$_type = $_this.val(),
				$_news_id = $_this.attr('news_id');
			
			var $_adata = {"type" : $_type,"news_id" : $_news_id};
			if($_type != '0'){
				vietiso_loading(1);
				$.ajax({
					type: "POST",
					url: path_ajax_script+"/index.php?mod="+mod+"&act=saveNewsBlock",
					data: $_adata,
					dataType: "html",
					success: function(html){
						vietiso_loading(0);
						loadNewsBlock($_type);					
					}
				});
			}
		});
		$_document.on('click', '.removeFromList', function(ev){
			var $_this = $(this),
				$_type = $_this.attr('type'),
				$_news_block_id = $_this.attr('news_block_id'),
				$_adata = {"type" : $_type , "news_block_id"  : $_news_block_id};
			$Core.alert.confirm("Xác nhận xóa", "Bạn có chắc chắn muốn xóa ?", function(){
				vietiso_loading(1);
				$.ajax({
					type: "POST",
					url: path_ajax_script+"/index.php?mod="+mod+"&act=removeNewsBlock",
					data: $_adata,
					dataType: "html",
					success: function(html){
						vietiso_loading(0);
						loadNewsBlock($_type);
					}
				});
			});
			return false;
		});
		$_document.on('click', '.moveDownList,.moveUpList', function(ev){
			var $_this = $(this),
				$_type = $_this.attr('type'),
				$_news_block_id = $_this.attr('news_block_id'),
				$_adata = {"type" : $_type, "news_block_id" : $_news_block_id};
			
			var ajAction = $_this.hasClass('moveDownList') 
				? 'moveDownNewsBlock' : 'moveUpNewsBlock';
			
			vietiso_loading(1);
			$.post(path_ajax_script+"/index.php?mod="+mod+"&act="+ajAction, $_adata, function(html){
				vietiso_loading(0);
				loadNewsBlock($_type);
			});
			return false;
		});
	});
	function loadNewsBlock($type){
		$.ajax({
			type: "POST",
			url: path_ajax_script+"/index.php?mod="+mod+"&act=loadNewsBlock",
			data: {"type" : $type},
			dataType: "html",
			success: function(html){
				vietiso_loading(0);
				$('#list-hot-'+$type).html(html);
			}
		});
	}
</script>
{/literal}
<header class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a class="btn btn-default ui-breadcrumb" href="{$PCMS_URL}/index.php?mod=home" title="{$core->get_Lang('Setting')}">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('News')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h2 class="ui-title-bar__title">{$core->get_Lang('NewsBlock')}</h2>
			</div>
			<p class="type--subdued">Với công cụ này, bạn có thể quản lí việc hiển thị tin bài trên chuyên mục hot ở trang chủ. Bạn có thể xóa, tạo mới hay chỉnh sửa những bản tin hiện có.</p>
		</div>
	</div>
</header>
<div class="ui-layout">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">
			<div class="ui-layout__item">
				{section name=i loop=$lst_NewsBlock}
				<div class="ui-card">
					<div class="next-tab__container">
						<ul class="next-tab__list filter-tab-list">
							<li class="filter-tab-item" data-tab-index="1">
								<a class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">Tin hot nhất [{$lst_NewsBlock[i].key}]</a>
							</li>
						</ul>
						<div class="ui-card__section has-bulk-actions pages">
							<div id="list-hot-{$lst_NewsBlock[i].key}">Chưa có bài nào được chọn</div>
							{literal}
							<script type="text/javascript">
								loadNewsBlock({/literal}'{$lst_NewsBlock[i].key}'{literal});
							</script>
							{/literal}
						</div>
					</div>
				</div>
				{/section}
				<div class="ui-card">
					<div class="next-tab__container mt20">
						<ul class="next-tab__list filter-tab-list">
							<li class="filter-tab-item" data-tab-index="1">
								<a class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">Danh sách 30 tin mới nhất</a>
							</li>
						</ul>
					</div>
					<div class="ui-card__section has-bulk-actions pages">
						<form method="post">
							<div class="form-search form-inline">
								<div class="form-group">
									<div class="input-group w-400px double-input">
										<select name="cat_id" class="iso-selectize required">
											{$clsCategory->makeSelectboxOption(0,'_NEWS',$cat_id)}
										</select>
										<input type="text" class="form-control" name="keyword" value="{$keyword}" placeholder="{$core->get_Lang('search')}" />
									</div>
								</div>
								<input type="hidden" name="filter" value="filter" />
								<button type="submit" class="btn btn-success">{$core->makeIcon('search', 'Search')}</button>
							</div>
						</form>
						<table class="table table-vertical table-striped" cellpadding="2" cellspacing="2" width="100%">
							<thead><tr>
								<th class="text-center" width="5%">No.</th>
								<th class="text-left">{$core->get_Lang('Title')}</th>
								<th class="text-left">{$core->get_Lang('Category')}</th>
								<th class="text-left" width="15%">{$core->get_Lang('CreateBy')}</th>
								<th class="text-left" width="10%">{$core->get_Lang('Number Views')}</th>
								<th class="text-center">{$core->get_Lang('Tool')}</th>
							</tr></thead>
							{section name=i loop=$listItem}
							<tr>
								<td class="text-center" width="5%">{$smarty.section.i.index+1}</td>
								<td class="text-left"><a href="{$DOMAIN_NAME}{$clsNews->getLink($listItem[i].news_id)}" target="_blank"><b>{$clsNews->getTitle($listItem[i].news_id)}</b></a></td>
								<td class="text-left">{$clsCategory->getTitle($listItem[i].cat_id)}</td>
								<td class="text-left">{$listItem[i].reg_date|date_format:'%d/%m/%Y %H:%M:%I'}</td>
								<td class="text-center">{$listItem[i].view_num} lượt</td>
								<td class="text-center" width="160px" style="white-space: nowrap;">        
									 <select class="form-control selectBlock" news_id="{$listItem[i].news_id}">
										<option value="0">-Chọn block-</option>
										<option value="ONETOPHOT">ONETOPHOT</option>
										<option value="4TOPHOT">4TOPHOT</option>
										<option value="6TOPHOT">6TOPHOT</option>
									 </select>
								</td>
							</tr>
							{/section}
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>