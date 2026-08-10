{if !empty($lstBlocksMenu)}
	{foreach from=$lstBlocksMenu item=_oBlock key=k_block name=n_block}
		{if !empty($_oBlock.building)}
			<div class="mb-3" >
				{assign var = more_information_block value=$_oBlock.more_information}
				{assign var = _oProject value=$_oBlock.project_info}
				<div class="block-one mt-0 mt-lg-2">
					<div class="divider my-2">
						<div class="divider-text">Phân khu {$_oBlock.title}</div>
					</div>
					<div class="d-flex gap-2 align-items-center justify-content-center">
						<img src="{$clsISO->getImageWH($_oProject.logo,0,30)}" class="h-px-30" />
						<h3 class="fs-6 mb-0 text-upper">{$_oProject.title}</h3>
					</div>
					<ul class="mb-0 list-unstyled d-flex flex-wrap gap-2 mt-2">
						{assign var=lstBuilding value=$_oBlock.building}
						{foreach from=$lstBuilding item=_oBuilding key=k_block name=n_building}
						<li class="flex-fill ">
							<a data-toggle="ripple" href="{$_oBuilding.link}" title="{$_oBuilding.title}" {$more_information_block.bgcolor} class="btn btn-sm btn-outline-primary building-name w-100" data-color="{$more_information_block.bgcolor}" style="border-color: {$more_information_block.bgcolor} !important; background-color: {$more_information_block.bgcolor} !important; color: {$more_information_block.textcolor} !important;" >{$_oBuilding.title}</a>
						</li>
						{/foreach}
					</ul>
				</div>
			</div>
		{/if}
	{/foreach}
{/if}