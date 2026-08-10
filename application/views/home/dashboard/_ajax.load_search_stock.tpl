{if !empty($list_logs)}

	{foreach from=$list_logs item=_oItem key=key name=i}

		{assign var=oProfile value=$_oItem.oProfile }

		<tr>

			<td>{$smarty.foreach.i.iteration}</td>

			<td data-label="Mã căn"><a href="javascript:void(0);" onclick="$Core.helper.open_stock(137507)"> <strong>{$_oItem.ms_code}</strong></a></td>

			<td data-label="Thời gian">{$clsISO->formatDate($_oItem.reg_date,4)}</td>

		</tr>

	{/foreach}

{else}

	<tr>	

		<td colspan="3">

			<div class="d-flex flex-column justify-content-center align-items-center">

				<div class="py-4">

					<img src="{$URL_IMAGES}/listing-empty.svg" width="100">

					<p class="text-muted">Không có lịch sử tra cứu</p>

				</div>

			</div>

		</td>

	</tr>

{/if}