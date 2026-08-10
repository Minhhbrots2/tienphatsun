<div class="container-xxl flex-grow-1 pt-2 container-p-y">
    <div class="col-12 col-md-8 offset-lg-2">
		<div class="alert alert-warning text-center mb-2">
			<a href="javascript:;" class="text-main d-flex font-bold text-upper align-items-center justify-content-center fs-20" campaign_id="{$oneCampaign.campaign_id}"> {$oneCampaign.title}</a>
			<ul class="countdown mb-0" data-end="{$clsISO->convertTimeToTextFormat($end_date)} 23:59:59">
				<li><span class="days">00</span>
					<p class="mb-0 days_text">ngày</p>
				</li>
				<li class="seperator">:</li>
				<li><span class="hours">00</span>
					<p class="mb-0 hours_text">giờ</p>
				</li>
				<li class="seperator">:</li>
				<li><span class="minutes">00</span>
					<p class="mb-0 minutes_text">phút</p>
				</li>
				<li class="seperator">:</li>
				<li><span class="seconds">00</span>
					<p class="mb-0 seconds_text">giây</p>
				</li>
			</ul>
		</div>
		<div class="iKuJnjIFyr template_2">
			<div class="hJsiGEcCOJ template_2">
				<table width="100%" class="table table-campaign text-wrap template_2">
					<thead><tr class="nohover">
						<th class="p_header text-center text-upper" colspan="10">
							<div class="mb-2">
								<img src="{$clsConfiguration->getValue('LogoWhite')}" width="{$clsConfiguration->getImageWidth('LogoWhite')}" height="{$clsConfiguration->getImageHeight('LogoWhite')}" alt="{$header_configs.CompanyName}" />
							</div>
							<strong>Bảng xếp hạng Thi đua cá nhân {$current_year}</strong><br />
							({$clsISO->convertTimeToText($start_date)}-
							{$clsISO->convertTimeToText($end_date)})
						</th>
					</tr>
					<tr>
						<th class="p_head text-center">STT</th>
						<th class="p_head text-left text-wrap">Họ và tên</th>
						{if $deviceType ne 'phone'}
						<th class="p_head text-center text-wrap">Tổng điểm</th>
						<th class="p_head text-center">Xếp loại</th>
						{else}
						<th class="p_head text-center text-wrap">Điểm</th>
						{/if}
					</tr></thead>
					{$html_table}
				</table>
			</div>
		</div>
    </div>
</div>