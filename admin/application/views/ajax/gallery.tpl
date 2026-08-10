<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header"> 
				<a href="javascript:void();" class="closeEv close close_pop"><span>×</span></a> 
				<h3 class="modal-title"><strong>{$titlePage}</strong></h3>
			</div>
			<form action="" method="post" id="frmIssue" encrupt="miltipart/form-data">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-4">
							<div class="aspect-ratio aspect-ratio--square aspect-ratio--interactive">
								<img class="aspect-ratio__content" src="{$oneItem.image}" />
							</div>
						</div>
						<div class="col-md-8">
							<div class="form-group mt-half">
								<label class="col-form-label">{$core->get_Lang('Title')}</label>
								<input type="text" class="form-control required" name="title" value="{$oneItem.title}">
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" name="type" value="{$type}" />
				<input type="hidden" name="table_id" value="{$table_id}" />
				<div class="modal-footer">
					<button class="btn btn-primary submitClick btn_savephoto_gallery" table_id="{$table_id}" _type="{$type}" image_id="{$image_id}">
						{$core->makeIcon('check', $core->get_Lang('Save'))}
					</button>
				</div>
			</form>
		</div>
	</div>