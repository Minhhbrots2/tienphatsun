<div class="modal-dialog modal-sm">
	<form class="modal-content" method="POST" action="" enctype="multipart/form-data">
		<div class="modal-header"> 
			<a href="javascript:void();" class="closeEv close_pop close"><span>×</span></a> 
			<h3 class="modal-title">
				<i class="fa fa-files-o" aria-hidden="true"></i>
				<i class="fa fa-long-arrow-right" aria-hidden="true"></i>
				<i class="fa fa-files-o" aria-hidden="true"></i>
			</h3>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-5">
					<input type="text" class="form-control required" placeholder="Từ tầng" name="from_floor" />
				</div>
				<label class="col-md-2 col-form-label text-center">
					<i class="fa fa-long-arrow-right mt-2" aria-hidden="true"></i>
				</label>
				<div class="col-md-5">
					<input type="text" class="form-control required" placeholder="Tới tầng" name="to_floor" />
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" name="project_id" value="{$project_id}" />
			<input type="hidden" name="block_id" value="{$block_id}" />
			<input type="hidden" name="building_id" value="{$building_id}" />
			<button type="button" class="btn btn-success pull-right" onClick="$Core.stock.do_copy(this, event)">Copy</button>
			<button type="button" class="btn btn-default mr-half pull-right" data-dismiss="modal">Đóng</button>
		</div>
	</form>
</div>