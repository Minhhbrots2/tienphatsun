<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
		</div>
		<form method="post" action="" enctype="multipart/form-data">
			<div class="modal-body">
				<div class="form-row">
					<div class="col-xs-12 col-md-6">
						<div class="form-group">
							<label class="form-label">Tiêu đề</label>
							<input class="form-control required" placeholder="Nhập tiêu đề" name="title" value="{$oneItem.title}" />
						</div>
					</div>
					<div class="col-xs-12 col-md-6">
						<div class="form-group">
							<label class="form-label">Đường dẫn</label>
							<input class="form-control required" placeholder="Nhập đường dẫn" name="link" value="{$oneItem.link}" />
						</div>						
					</div>
				</div>
				<div class="form-row">
					<div class="col-xs-12 col-md-6">
						<div class="form-group">
							<label class="form-label">Domain</label>
							<input class="form-control required" placeholder="Nhập tên miền" name="domain" value="{$oneItem.domain}" />
						</div>						
					</div>
					<div class="col-xs-12 col-md-6">
						<div class="form-group">
							<label class="form-label">Danh mục Tin</label>
							<select name="cat_id" id="" class="form-select form-control iso-select2">
								{$clsProperty->getSelectByProperty("_NEWS_CATEGORY",$oneItem.cat_id)}
							</select>
						</div>					
					</div>
				</div>
				<div class="form-row">
					<div class="col-xs-12 col-md-6">
						<div class="form-group">
							<label class="form-label">Giới thiệu</label>
							<select name="about_us_id" class="form-select form-control iso-select2">
								<option value="0">Chọn bài giới thiệu</option>
								{foreach from=$lstPage item=_oItem key=key name=i}
									<option value="{$_oItem.page_id}" {if $oneItem.about_us_id eq $_oItem.page_id}selected{/if} >{$_oItem.title}</option>
								{/foreach}
							</select>
						</div>	
					</div>
					<div class="col-xs-12 col-md-6">
						<div class="form-group">
							<label class="form-label">Danh mục FAQs</label>
							<select name="cat_faqs_id" class="form-select form-control iso-select2">
								{$clsProperty->getSelectByProperty("_CATEGORYFAQS",$oneItem.cat_faqs_id)}
							</select>
						</div>					
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onClick="$Core.setting.save_domain(this, event)" class="btn btn-success" 
					data-action="save" domain_id="{$domain_id}">
					<span>Lưu lại</span>
				</button>
				<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">
					<span>Đóng</span>
				</button>
			</div>
		</form>
	</div>
</div>
