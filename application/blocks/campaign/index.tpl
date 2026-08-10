{if !empty($oneCampaign)}

	<!--onClick="$Core.helper.view_campaign(this, event)" --> 

	<div class="alert alert-warning text-center mb-2">

		<h3 class="mb-2 font-bold text-main">Mục tiêu: {$total_transactions}/{$clsConfiguration->getValue('total_transactions')} giao dịch</h3>

		<hr />

		<a href="javascript:;" class="text-main d-flex font-bold text-upper align-items-center justify-content-center fs-20" campaign_id="{$oneCampaign.campaign_id}"><img src="{$URL_IMAGES}/gift-icon-hot.gif" class="w-px-30" /> {$oneCampaign.title}</a>

		<ul class="countdown mb-0" data-end="{$clsISO->convertTimeToTextFormat($oneCampaign.end_date)} 23:59:59">

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

    {if $oneCampaign.selector eq 'staff'}

    <div class="iKuJnjIFyr bg-building">

        <div class="hJsiGEcCOJ">

            <table width="100%" class="table dnqdzzfKjV mb-0">

                <thead><tr class="nohover">

                    <th class="text-white border-0 text-center" colspan="5">

                        <div class="mb-2">

                            <img src="{$clsConfiguration->getValue('LogoWhite')}" width="{$clsConfiguration->getImageWidth('LogoWhite')}" height="{$clsConfiguration->getImageHeight('LogoWhite')}" alt="{$header_configs.CompanyName}" />

                        </div>  

                        <strong>Bảng điểm {$oneCampaign.title}</strong><br />

                        ({$clsISO->convertTimeToText($oneCampaign.start_date)}-

                        {$clsISO->convertTimeToText($oneCampaign.end_date)})

                    </th>

                </tr>

                <tr class="nohover">

                    <th class="p_head border-0 text-center">STT</th>

                    <th class="p_head border-0 text-left">Họ và tên</th>

                    <th class="p_head border-0 text-center">Điểm</th>

                </tr></thead>

                {if !empty($list_staffs)}

                    {foreach name=i from=$list_staffs item=_oStaff}

                    <tr class="nohover p_row {if !empty($_oStaff.total_scores)}lighter{/if}">

                        <td class="p_cell text-center text-white font-bold">{$smarty.foreach.i.iteration}</td>

                        <td class="p_cell text-white font-bold">{$_oStaff.full_name}</td>

                        <td class="p_cell text-center text-white font-bold">

                            {$_oStaff.total_scores}

                            <span class="text-orange">/60</span>

                        </td>

                    </tr>

                    {/foreach}

                {/if}

            </table>

        </div>

    </div>

    {else}

    <table width="100%" class="table table-campaign">

        <thead><tr class="nohover">

            <th class="p_head text-center" colspan="5">

            {if $deviceType eq 'phone'}

                <strong>Bảng xếp hạng {$oneCampaign.title}</strong><br />

                ({$clsISO->convertTimeToText($oneCampaign.start_date)}-

                {$clsISO->convertTimeToText($oneCampaign.end_date)})

            {else}

                <strong class="text-upper">Bảng xếp hạng {$oneCampaign.title}</strong>

                ({$clsISO->convertTimeToText($oneCampaign.start_date)}-

                {$clsISO->convertTimeToText($oneCampaign.end_date)})

            {/if}

            </th>

        </tr>

        <tr>

            {if $deviceType ne 'phone'}

            <th width="6%" class="p_head text-center">STT</th>

            <th width="14%" class="p_head text-center">Team</th>

            <th class="p_head text-center">Họ & tên đội</th>

            <th class="p_head text-center">Tổng điểm</th>

            {else}

            <th width="20%" class="p_head text-center">STT</th>

            <th class="p_head text-center">Team</th>

            <th width="30%" class="p_head text-center">Tổng điểm</th>

            {/if}

        </tr></thead>

        <tbody>

            {if !empty($list_groups)}

                {foreach name=i from=$list_groups item = _oGroup}

                <tr class="{if !empty($_oGroup.total_scores)}lighter{/if}">

                    <td class="p_cell text-center font-bold">{$smarty.foreach.i.iteration}</td>

                    <td class="p_cell text-center font-bold">{$_oGroup.name}</td>

                    {if $deviceType ne 'phone'}

                    <td class="p_cell text-left text-main">

                        {if !empty($_oGroup.total_scores)}

                            <strong>{$_oGroup.group_members}</strong>

                        {else}

                            {$_oGroup.group_members}

                        {/if}

                    </td>

                    {/if}

                    <td class="p_cell text-center font-bold">

                        {if !empty($_oGroup.total_scores)}

                            {$clsISO->formatNumber($_oGroup.total_scores)}

                        {else}

                            Updating...

                        {/if}

                    </td>

                </tr>

                {/foreach}

            {/if}

        </tbody>

        {if $oneCampaign.is_terms eq '1' && !empty($list_terms)}

        <tfoot><tr class="nohover">

            <td style="background:#ffff0e" class="p_cell text-center" colspan="5">

                <div class="w-px-300 mx-auto">

                    <table class="w-100 text-main">

                        <thead><tr class="nohover">

                            <td style="background:#ffff0e" class="border-0 text-pink text-left" colspan="4">

                                <strong>Cách tính điểm thi đua như sau:</strong>

                            </td>

                        </tr></thead>

                        <tbody>

                            <tr class="nohover">

                            {foreach name=i from=$list_terms key = prop_id item=score}

                                <td width="35%" class="text-left text-pink border-0">

                                    {$clsProperty->getTitle($prop_id)}

                                </td>

                                <td class="text-left text-pink border-0">

                                    <strong>{$score}</strong>

                                </td>

                                {if $smarty.foreach.i.iteration%2 eq '0'}

                                    </tr><tr class="nohover">

                                {/if}

                            {/foreach}

                            </tr>

                        </tbody>

                    </table>

                </div>

            </td>

        </tr></tfoot>

        {/if}

    </table>

    {/if}

	{literal}

	<style type="text/css">

		@media screen and (max-width:575px){

			.table-campaign th,

			.table-campaign td{

				padding:0.225rem 0.625rem !imporant;

			}

		}

	</style>

	{/literal}

{/if}