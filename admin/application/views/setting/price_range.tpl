<div class="ui-title-bar-container">
	<div class="ui-title-bar">
		<div class="ui-title-bar__navigation">
			<div class="ui-breadcrumbs">
				<a href="{$PCMS_URL}/index.php?mod={$mod}" class="btn btn-default ui-breadcrumb">
					{$core->makeIcon('angle-left mr-5')}
					<span class="ui-breadcrumb__item">{$core->get_Lang('Setting')}</span>
				</a>
			</div>
		</div>
	</div>
	<div class="ui-title-bar">
		<div class="ui-title-bar__main-group">
			<div class="ui-title-bar__heading-group">
				<h1 class="ui-title-bar__title w-100">{$core->get_Lang('Khoảng giá')}</h1>
				<p class="type--subdued">{$core->get_Lang('Hệ thống quản lý Khoảng giá hệ Thống')}</p>
			</div>
		</div>
	</div>
</div>
<div class="ui-layout">
	<div class="ui-layout__sections">
		<div class="ui-layout__section">
			<div class="ui-layout__item">
				<div class="ui-card">
					<div class="next-tab__container">
						<ul class="next-tab__list filter-tab-list">
							<li class="filter-tab-item" data-tab-index="1">
								<a href="javascript:void();" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active">Danh sách khoảng giá</a>
							</li>
						</ul>
					</div>
					<div class="ui-card__section has-bulk-actions pages">
						<div class="form-search form-inline">
							<div class="form-group">
								<div class="input-group">
									<input type="text" class="form-control" name="keyword" id="keyword" placeholder="Tìm kiếm">
									<div class="input-group-btn">
										<button class="btn btn-success btnCreatePriceRange" price_range_id="0" style="padding:6px 10px;">
											{$core->makeIcon('plus-circle', $core->get_Lang('Addnew'))}
										</button>
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix" style="margin-bottom: 15px"></div>
						<div id="holder_price_range" class="hastable table-wrapper"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var insert_error = 'Lỗi thêm mới';
	var update_error = 'Lỗi cập nhật mới';
	var insert_error_exist = 'Thêm mới đã tồn tại';
</script>
{literal}
<script type="text/javascript">
	$().ready(function(){
		load_list_price_range({});
		$_document.on('keyup','#keyword', $Core.util.delay(function(){
			var keyword = $(this).val();
			load_list_price_range({'keyword': keyword});
		},500));
		$_document.on('click','.btnCreatePriceRange,.btn_editPriceRange', function(){
			var $_this = $(this),
				price_range_id = $_this.attr('price_range_id');

			vietiso_loading(1);
			$.ajax({
				type:'POST',
				url:path_ajax_script+'/index.php?mod='+mod+'&act=open_price_range',
				data : {'price_range_id' : price_range_id},
				dataType:'html',
				success:function(html){
					vietiso_loading(0);
					makepopup('auto','auto',html,'open_price_range_'+price_range_id);
				}
			});
			return false;
		});
		$_document.on('click','.savePriceRange', function(){
			var $_this = $(this),
				price_range_id = $_this.attr('price_range_id');

			var _validated = 0,
				$_form = $_this.closest('form');
			if($('input.required', $_form).length){
				$('input.required', $_form).each(function(){
					if($Core.util.isEmpty($(this).val())){
						_validated++;
						$(this).focus();
						return false;
					}
				})
			}
			if(_validated==0){
				vietiso_loading(1);
				$_form.ajaxSubmit({
					type:'POST',
					url : path_ajax_script+'/index.php?mod='+mod+'&act=save_price_range',
					data:{'price_range_id':price_range_id},
					dataType:'html',
					success:function(html){
						vietiso_loading(0);
						if(html.indexOf('_success') >= 0){
							load_list_price_range({});
							$Core.popup.close($_form.closest('.modal'));
						} else {
							$Core.alert.error(update_error);
						}
					}
				});
			}
		});
		$_document.on('click','.btn_deletePriceRange', function(){
			var $_this = $(this),
				$_adata = {'price_range_id' : $_this.attr('price_range_id')};
			$Core.alert.confirm('Xác nhận xóa?', 'Bạn có chắc chắn muốn xóa?', function(){
				$.post(path_ajax_script+'/index.php?mod='+mod+'&act=delete_price_range', $_adata, function(){
					vietiso_loading(0);
					load_list_price_range({});
				});
			});
			return false;
		});
		$('.btn_movePriceRange').live('click',function(){
			var _this = $(this),
				direct = _this.attr('direct'),
				price_range_id = _this.attr('price_range_id');
			var $_adata = {'direct':direct,'price_range_id':price_range_id};
			$.post(path_ajax_script+"/?mod="+mod+"&act=move_price_range", $_adata, function(){
				load_list_price_range({});
			});
			return false;
		});
	});
	function load_list_price_range(options){
		var $_adata = options || {};
		vietiso_loading(1);
		$.post(path_ajax_script+"/?mod="+mod+"&act=load_list_price_range", $_adata, function(respJson){
			vietiso_loading(0);
			$('#holder_price_range').html(respJson.html);
			if(parseInt(respJson.total_record) > 0){
			$('#pager_price_range').pagination({
				total: respJson.total_record,
				pageSize : respJson.number_per_page,
				onSelectPage: function(pageNumber,pageSize){
					load_list_price_range($.extend(options, {'page':pageNumber,'number_per_page':pageSize}));
				},
				onRefresh: function(pageNumber,pageSize){
					load_list_price_range($.extend(options, {'page':pageNumber,'number_per_page':pageSize}));
				},
				onChangePageSize: function(pageSize){
					load_list_price_range($.extend(options, {'number_per_page':pageSize}));
				}
			});
		}
		}, 'json');
	}
</script>
{/literal}