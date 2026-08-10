<div class="main-content container">
	<div class=" pd5  ">
        <ol class="breadcrumb breadcrumb-arrows">
            <li><a href="/" target="_self">Trang chủ</a></li>
            <li><a href="/profile.html">Trang cá nhân</a></li>
            <li class="active"><span>Đơn hàng của tôi</span></li>
        </ol>
    </div>
    <div class="member-content">
        <div class="row">
        	<div class="col-lg-3 col-md-3 col-xs-12">
            	{$core->getBlock('member_left_profile')}
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12">
            	<div class="box-title-collection m-b-15 clearfix">
                	<div class="box-title-collection__title">
                    	<h1><img style="vertical-align:0px" align="absmiddle" src="../../../application/modules/member/{$URL_IMAGES}/member/account.png" /> Quản lý Đơn Hàng</h1>
                    </div>
                </div>
                <div class="clearfix"></div>
                <table class="tbl-grid" width="100%" cellpadding="0">
                    <tr>
                        <td class="gridheader"><strong>Mã ĐH</strong></td>
                        <td class="gridheader" style="text-align:center"><strong>Chi tiết đơn hàng</strong></td>
                        <td class="gridheader" style="text-align:center"><strong>Ngày đặt</strong></td>
                        <td class="gridheader" style="text-align:center"><strong>Trạng thái</strong></td>
                        <td class="gridheader"><strong>Tiền thanh toán</strong></td>
                    </tr>
                    {if $lstOrder[0].order_id ne ''}
                        {section name=i loop= $lstOrder}
                        <tr>
                            <td>{$clsOrder->getOneField('order_code',$lstOrder[i].order_id)}</td>
                            <td style="text-align:right"><a href="/order/detail/{$lstOrder[i].order_id}"><img align="absmiddle" src="../../../application/modules/member/{$URL_IMAGES}/member/zoom_last.png" /> Xem chi tiết</a></td>
                            <td class="color_r" style="text-align:right">{$clsOrder->getOneField('reg_date',$lstOrder[i].order_id)|date_format:"%d-%m-%Y %H:%M"}</td>
                            <td>{$clsOrder->getStatus($lstOrder[i].order_id)}</td>
                            <td class="format_price">{$clsOrder->getTotalMoney($lstOrder[i].order_id)}</td>
                        </tr>
                        {/section}
                    {else}
                    <tr>
                        <td colspan="5" style="text-align:center"> Chưa có đơn hàng nào</td>
                    </tr>
                    {/if}
                </table>
            </div>
        </div>
    </div>
</div>