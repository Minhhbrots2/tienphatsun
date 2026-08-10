<div class="container-xxl flex-grow-1 container-p-y">
	
	<div class="d-flex flex-wrap justify-content-between mb-4 w-100">
		<div>
			<h4 class="fw-bold mb-1">Danh sách nhà môi giới</h4>
			<span class="srp-total-count text-muted">Hiện có <strong class="total-stock text-main">{$totalRecord}</strong> nhà môi giới.</span>
		</div>
		<div class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0 {if $deviceType eq 'phone'}mt-3 w-100{/if}">
			<form class="{if $deviceType eq 'phone'}w-100{/if}" action="" method="post" onsubmit="return false">				
				<div id="DataTables_Table_0_filter" class="dataTables_filter">
					<div class="input-group input-group-merge">
					  <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
					  <input type="text" class="form-control"  name="keyword" placeholder="Search" value="{$keyword}" aria-label="Search" aria-describedby="basic-addon-search31" onKeyUp="$Core.broker.searchKey(this,event)">
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="">
		<div>
			<div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
				<div class="row">
					
				</div>
				<div class="holder_broker awe__sop-list row">
					
				</div>
				<div class="d-flex justify-content-between py-3 text-center" id="showmorethisresult">
					<button type="button" class="showmorethisresult d-none" onClick="$Core.broker.load_more(this, event)" page="2"> 
						<span>Xem thêm</span> 
						<img src="{$URL_IMAGES}/loading_48.gif" width="24px"> 
					</button> 
				</div>
			</div>
		</div>
	</div>
</div>