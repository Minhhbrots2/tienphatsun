<div class="container-xxl flex-grow-1 container-p-y">

    <div class="col-12 col-md-8 offset-lg-2">

		<div class="alert alert-warning text-center mb-2">

			<a href="javascript:;" class="text-main d-flex font-bold text-upper align-items-center justify-content-center fs-18"><img src="{$URL_IMAGES}/gift-icon-hot.gif" class="w-px-30" />  Thi đua cá nhân khối BO {$curent_year}</a>

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

				<table width="100%" class="table table-campaign template_2">

					<thead><tr class="nohover">

						<th class="p_header text-center text-upper" colspan="10">

							<div class="mb-2">

								<img src="{$clsConfiguration->getValue('LogoWhite')}" width="{$clsConfiguration->getImageWidth('LogoWhite')}" height="{$clsConfiguration->getImageHeight('LogoWhite')}" alt="{$header_configs.CompanyName}" />

							</div>

							<strong>Bảng xếp hạng thi đua khối BO</strong><br />

							({$clsISO->convertTimeToText($start_date)}-

							{$clsISO->convertTimeToText($end_date)})

						</th>

					</tr>

					<tr>

						<th width="{if $deviceType eq 'phone'}3{else}10{/if}%" class="p_head text-center">No.</th>

						<th class="p_head">Họ và tên</th>

						<th width="{if $deviceType eq 'phone'}22{else}25{/if}%" class="p_head">Điểm</th>

					</tr>

					</thead>

					{foreach name=i from=$list_staffs item = _oStaff}

					<tr class="p_row text-white">

						<td class="p_cell text-center">{$smarty.foreach.i.iteration}</td>

						<td class="p_cell font-bold">{$_oStaff.code}-{$_oStaff.full_name}</td>

						<td class="p_cell">{$_oStaff.score}/

							<strong class="text-orange">60</strong>

						</td>

					</tr>

					{/foreach}

				</table>

			</div>

		</div>

    </div>

</div>