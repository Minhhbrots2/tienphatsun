<div class="ui-title-bar-container ui-title-bar-container--full-width">
    <div class="ui-title-bar">
        <div class="ui-title-bar__main-group">
            <div class="ui-title-bar__heading-group">
                <h1 class="ui-title-bar__title w-100">{$core->get_Lang('Report')}</h1>
                <p class="type--subdued">{$core->get_Lang('This system allows you to manage & edit static pages in Systems')}</p>
            </div>
        </div>
    </div>
</div>
<div class="ui-layout ui-layout--full-width">
    <div class="ui-layout__sections">
        <div class="ui-layout__section">
            <div class="ui-layout__item">
               <div class="row">
               		<div class="col-md-6">
               			<div class="ui-card">
							<div class="next-tab__container">
								<ul class="next-tab__list filter-tab-list">
									<li class="filter-tab-item">
										<a href="javascript:void(0);" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active"> {$core->get_Lang('Biểu đồ tỉ lệ đơn hàng các tháng trong năm')} {$year}</a>
									</li>
								</ul>
							</div>
							<div class="ui-card__section has-bulk-actions pages">
							   <h4 style="margin-bottom: 15px"></h4>
								<div class="chartContainer" id="chartContainer" style="width: 100%; height: 405px;">
									{$jsCodeContactChart}
								</div>
							</div>
                		</div>
               		</div>
               		<div class="col-md-6">
               			<div class="ui-card" style="margin-top: 20px">
							<div class="next-tab__container">
								<ul class="next-tab__list filter-tab-list">
									<li class="filter-tab-item">
										<a href="javascript:void(0);" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active"> {$core->get_Lang('Biểu đồ doanh thu các tháng trong năm')} {$year}</a>
									</li>
								</ul>
							</div>
							<div class="ui-card__section has-bulk-actions pages">
								<div class="chartContainer" id="chartContainer_money" style="width: 100%; height: 400px;">
								{$jsCodeContactChart_money}</div>
							</div>
               	 		</div>
               		</div>
               	</div>
               	<div class="clearfix"></div>
                <div class="ui-card" style="margin-top:20px">
                    <div class="next-tab__container">
                        <ul class="next-tab__list filter-tab-list">
                            <li class="filter-tab-item">
                                <a href="javascript:void(0);" class="filter-tab filter-tab-active show-all-items next-tab next-tab--is-active"> {$core->get_Lang('Thống kê báo cáo các đơn hàng')}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="ui-card__section has-bulk-actions pages">
                        <form method="post">
                            <div class="form-search form-inline">
                                {$select_year}
                                <input type="hidden" name="filter" value="filter" />
                                <button type="submit" class="btn btn-success hidden submit_btn">{$core->makeIcon('search', 'Search')}</button>
                            </div>
                            <div class="hastable table-wrapper">
                                {$htmlList}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{literal}
    <style>
        tr.even {
            background: #eee;
        }
        thead>tr>th, tbody>tr>th, tfoot>tr>th, thead>tr>td, tbody>tr>td, tfoot>tr>td {
            padding: 10px 8px 6px;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 1px solid #ddd;
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
        }
        .size20{font-size: 20px;}
        .color_1c1c1c{color: #1c1c1c !important;}
    </style>
    <script type="text/javascript">
        $(document).on('change', '#year', function(){
            $('.submit_btn').trigger('click');
        });
    </script>
{/literal}