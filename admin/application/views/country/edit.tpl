<div class="breadcrumb">
	<strong>{$core->get_Lang('youarehere')} : </strong>
	<a href="{$PCMS_URL}" title="{$core->get_Lang('home')}">{$core->get_Lang('home')}</a>
    <a>&raquo;</a>
    <a href="{$PCMS_URL}/?mod=continent">{$core->get_Lang('Continent')}</a>
    <a>&raquo;</a>
    <a href="{$PCMS_URL}/index.php?mod={$mod}">{$core->get_Lang('Country')}</a>
    <a>&raquo;</a>
    <a href="{$curl}" title="{$act}"> {if $pvalTable}{$core->get_Lang('edit')} #{$pvalTable}{else}{$core->get_Lang('add')}{/if}</a>
    <!-- // -->
    <a href="javascript:window.history.back();" class="back fr">{$core->get_Lang('back')}</a>
</div>
<div class="clearfix"></div>
{if $msg eq 'DuplicateCountry'}
<div style="padding:15px; padding-top:0;">
	<div style="padding:10px; background:red; color:#fff; font-size:14px; text-align:center; ">
    	<img src="{$URL_IMAGES}/warning-20.png" title="" align="absmiddle" />
		<strong>{$core->get_Lang('Warning')}:</strong> {$core->get_Lang('identicalposts')}
	</div>
</div>
<div class="clearfix"></div>
{/if}
<div class="container-fluid">
    <div class="page-title">
        <h2>{if $pvalTable}{$clsClassTable->getTitle($pvalTable)}{else}{$core->get_Lang('Add New Country')}{/if}</h2>
    </div>
	<div class="clearfix"><br /></div>
    <form id="edititem" method="post" action="" enctype="multipart/form-data" class="validate-form">
    	<div id="clienttabs">
            <ul>
            	<li class="tabchild"><a href="#"><i class="iso-bassic"></i> {$core->get_Lang('generalinformation')}</a></li>
				<li class="tabchild"><a href="#"><i class="iso-bassic"></i> {$core->get_Lang('information')}</a></li>
                {if $pvalTable}<li class="tabchild"><a href="#">{$core->get_Lang('seosdvanced')}</a></li>{/if}
            </ul>
        </div>
        <div class="clearfix"></div>
        <div id="tab_content">
            <div class="tabbox" style="display:block">
                <div class="photobox fl mr20 image">
                    {if $_isoman_use eq '1'}
                        <img src="{$oneItem.image}" alt="{$core->get_Lang('images')}" id="isoman_show_image" />
                        <input type="hidden" id="isoman_hidden_image" name="isoman_url_image" value="{$oneItem.image}" />
                        <a href="javascript:void()" title="{$core->get_Lang('change')}" class="photobox_edit ajOpenDialog" isoman_for_id="image" isoman_val="{$oneItem.image}" isoman_name="image"><i class="iso-edit"></i></a>
                        {if $oneItem.image}
                        <a pvalTable="{$pvalTable}" clsTable="Country" href="javascript:void()" title="{$core->get_Lang('delete')}" class="photobox_edit deleteItemImage" g="imgItem" style="margin-left:25px;line-height:27px;background:red;color:#fff;text-align:center; text-decoration:none">X</a>
                        {/if}
                    {else}
                        <img src="{$oneItem.image}" alt="{$core->get_Lang('noimages')}" id="imgTour_image" />
                        <input type="hidden" name="image_src" value="{$oneItem.image}" class="hidden_src" id="imgTour_hidden" />
                        <a href="javascript:void()" title="{$core->get_Lang('Change')}" class="photobox_edit editInlineImage" g="imgTour">
                            <i class="iso-edit"></i>
                        </a> 
                        <input type="file" style="display:none" id="imgTour_file" g="imgTour" class="editInlineImageFile" name="image" />
                    {/if}
                </div>
                <div class="fl span75">
                    <div class="span100">
                        <div class="row-span" style="padding-bottom:10px;">
                             <div class="fieldlabel">{$core->get_Lang('Country Name')} <span class="requiredMask">*</span> </strong></div>
                             <div class="fieldarea">
                                <input style="border:2px solid #ccc;" class="text full required fontLarge" id="title" name="iso-title" value="{$clsClassTable->getTitle($pvalTable)}" maxlength="255" type="text">
                             </div>
                        </div>
                    </div>
                    {if $lstContinent && $clsConfiguration->getValue('SiteModActive_continent') and $core->checkAccess('continent')}
                    <div class="row-span" >
                        <div class="fieldlabel"><span class="requiredMask">*</span> {$core->get_Lang('selectcontinent')}</div>
                        <div class="fieldarea">
                            <select class="glSlBox required full" name="iso-continent_id">
                            	<option value="">-- {$core->get_Lang('Select Continent')} --</option>
                                {section name=i loop=$lstContinent}
                                <option {if $oneItem.continent_id eq $lstContinent[i].continent_id}selected="selected"{/if} value="{$lstContinent[i].continent_id}">{$clsContinent->getTitle($lstContinent[i].continent_id)}</option>
                                {/section}
                            </select>
                        </div>
                    </div>
                    {/if}
                    <div class="row-span">
                        <div class="fieldlabel">{$core->get_Lang('Status')}</div>
                        <div class="fieldarea">
                            <div class="vietiso_status_button"></div>
                            <script type="text/javascript">
                                var is_online = '{$clsClassTable->getOneField("is_online",$pvalTable)}';
                            </script>
                            {literal}
                            <script type="text/javascript">
                                $(document).ready(function(){
                                    $('.vietiso_status_button').isoswitchvalue({
                                        _value:is_online,
                                        _selector:'iso-is_online'
                                    });
                                });
                            </script>
                            {/literal}
                            <span class="notice" id="prv_status" {if $clsClassTable->getOneField("is_online",$pvalTable) eq 1}style="display:none;"{/if}>PRIVATE: {$core->get_Lang('This article can only be seen via the link in the admin page')}.</span>
                            <span class="notice" id="pub_status" {if $clsClassTable->getOneField("is_online",$pvalTable) eq 0}style="display:none;"{/if}>PUBLIC: {$core->get_Lang('This article is available online show normal status')}.</span>
                        </div>
                    </div>
                    <div class="row-span">
                        <span class="notice bold" style="font-size:16px; padding-left:0;"><span class="requiredMask">*</span> {$core->get_Lang('aboutlinformation')} {$clsClassTable->getTitle($pvalTable)}</span>
                        <div class="clearfix" style="height:5px"></div>
                        {$clsForm->showInput('intro')}
                    </div>
                    <div class="row-span">
                        <div class="fieldlabel-full">
                            <i class="iso-pos"></i> <strong>{$core->get_Lang('Location on map')}</strong>
                        </div>
                        <div class="clearfix" style="height:5px"></div>
                        <div class="fieldarea" style="width:100%; max-width:100%">
                            <div class="searchmap">
                                <div class="wrap">
                                    <input class="text full fl" id="search_location" type="text" />
                                    <a class="btn fr btn-success" id="searchMap">
                                        <i class="icon-white icon-search"></i>
                                        <span>{$core->get_Lang('search')}</span>
                                    </a>
                                </div>
                            </div>
                            <script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>
                            <div style="width:100%; height:220px; overflow:hidden; position:relative">
                                <div id="map_canvas" style="width:100%; height:220px; overflow:hidden">
                                    <!-- Map Sitebar -->
                                </div>
                                <div class="map_sitebar" style="height:160px">
                                    <div class="row-field">
                                        <div class="row-heading notToogle">{$core->get_Lang('latitude')}</div>
                                        <div class="coltrols">
                                            <input class="text full" name="iso-map_la" id="map_la" value="{$oneItem.map_la}" maxlength="255" type="text" style="width:95% !important" />
                                        </div>
                                    </div>
                                    <div class="row-field">
                                        <div class="row-heading notToogle">{$core->get_Lang('longitude')}</div>
                                        <div class="coltrols">
                                           <input class="text full" name="iso-map_lo" id="map_lo" value="{$oneItem.map_lo}" maxlength="255" type="text" style="width:95% !important" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row-field">
                        <div class="row-heading notToogle">{$core->get_Lang('Banner Slide')}:</div>
                        <div class="controls">
                            <div class="photobox span98">
                                {if $_isoman_use eq '1'}
                                    <img src="{$oneItem.banner}" alt="Chưa có hình ảnh"  id="isoman_show_banner" class="span100" height="156px" style="width:100%;" />
                                    <input type="hidden" id="isoman_hidden_banner" name="isoman_url_banner" value="{$oneItem.banner}" />
                                    <a href="javascript:void()" class="photobox_edit ajOpenDialog" isoman_for_id="banner" isoman_val="{$oneTable.banner}" isoman_name="banner" title="Chọn ảnh bài viết">
                                        <i class="iso-edit"></i>
                                    </a>
                                    {if $oneTable.banner}
                                        <a pvalTable="{$pvalTable}" clsTable="Country" href="javascript:void()" title="Xóa" class="photobox_edit deleteItemImage" g="imgItem">X</a>
                                    {/if}
                                {else}
                                    <img class="span100" src="{$oneItem.image}" height="156px" id="img_Slide_image" />
                                    <input type="hidden" name="image_src" id="img_Slide_hidden" />
                                    <input type="file" name="image" class="editInlineImageFile" id="img_Slide_file" g="img_Slide" style="display:none" />
                                    <a href="javascript:void()" title="Thay đổi" class="photobox_edit editInlineImage" g="img_Slide">
                                        <i class="iso-edit"></i>
                                    </a>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
			</div>
			<div class="tabbox" style="display:none">
				<div class="row-span">
					<span class="notice bold" style="font-size:16px; padding-left:0;"><span class="requiredMask">*</span> {$core->get_Lang('tourinformation')} {$clsClassTable->getTitle($pvalTable)}</span>
					<div class="clearfix" style="height:5px"></div>
					{$clsForm->showInput('intro_tour')}
				</div>
				<div class="row-span">
					<span class="notice bold" style="font-size:16px; padding-left:0;"><span class="requiredMask">*</span> {$core->get_Lang('hotelinformation')} {$clsClassTable->getTitle($pvalTable)}</span>
					<div class="clearfix" style="height:5px"></div>
					{$clsForm->showInput('intro_hotel')}
				</div>
				<div class="row-span">
					<span class="notice bold" style="font-size:16px; padding-left:0;"><span class="requiredMask">*</span> {$core->get_Lang('guideinformation')} {$clsClassTable->getTitle($pvalTable)}</span>
					<div class="clearfix" style="height:5px"></div>
					{$clsForm->showInput('intro_guide')}
				</div>
				<div class="row-span">
					<span class="notice bold" style="font-size:16px; padding-left:0;"><span class="requiredMask">*</span> {$core->get_Lang('generalinformation')} {$clsClassTable->getTitle($pvalTable)}</span>
					<div class="clearfix" style="height:5px"></div>
					{$clsForm->showInput('content')}
				</div>
			</div>
            {if $pvalTable}
			<div class="tabbox" style="display:none">
                <div class="row-field">
                    <div class="row-heading">{$core->get_Lang('Meta Title')}:</div>
                    <div class="coltrols">
                        <input class="text full" name="config_value_title" value="{$clsISO->getPageTitle($pvalTable,Country)}" maxlength="255" type="text" />
                        <div class="clearfix mt5"></div>
                        <i>{$core->get_Lang('notetitlemeta')}</i>
                    </div>
                </div>
                <div class="row-field">
                    <div class="row-heading">{$core->get_Lang('Meta Description')}:</div>
                    <div class="coltrols">
                        <textarea name="config_value_intro" class="text full" style="height:60px">{$clsISO->getPageDescription($pvalTable,Country)}</textarea>
                        <div class="clearfix mt5"></div>
                        <i>{$core->get_Lang('noteintrometa')}</i>
                    </div>
                </div>
                <div class="row-field">
                    <div class="row-heading">{$core->get_Lang('Meta Keyword')}:</div>
                    <div class="coltrols">
                        <textarea name="config_value_keyword" class="text full" style="height:60px">{$clsISO->getPageKeyword($pvalTable,Country)}</textarea>
                        <div class="clearfix mt5"></div>
                        <i>{$core->get_Lang('notekeywordmeta')}</i>
                        <br style="clear:both" />
                        <br style="clear:both" />
                        <table>
                            <tr>
                                <td style="background:#CCC">{$core->get_Lang('Meta Robots Index')}</td>
                                <td>
                                    <select name="meta_index">
                                        <option value="0">{$core->get_Lang('Index')}</option>
                                        <option value="1" {if $oneMeta.meta_index eq 1}selected="selected"{/if}>{$core->get_Lang('NoIndex')}</option>
                                    </select>
                                </td>
                                <td style="background:#CCC">{$core->get_Lang('Meta Robots Follow')}</td>
                                <td>
                                    <select name="meta_follow">
                                        <option value="0">{$core->get_Lang('Follow')}</option>
                                        <option value="1" {if $oneMeta.meta_follow eq 1}selected="selected"{/if}>{$core->get_Lang('NoFollow')}</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            {/if}
        </div>
        <div class="clearfix"><br /></div>
        <fieldset class="submit-buttons">
            {$saveBtn}{$saveList}
            <input value="Update" name="submit" type="hidden">
        </fieldset>
    </form>
</div>
<script type="text/javascript">
	var map_lo="{$clsClassTable->getOneField('map_lo',$pvalTable)}";
	var map_la="{$clsClassTable->getOneField('map_la',$pvalTable)}";
	var not_find_location = "{$core->get_Lang('Sorry but Google Maps could not find this location')}";
</script>
{literal}
<style>.searchmap{ background:#E9EFF3; padding:5px; margin:5px 0 0;}</style>
<script type="text/javascript">
	var geocoder=new google.maps.Geocoder();
	var map; 
	var marker; 
	function $getID(id){
		return document.getElementById(id);
	}
	function geocode(position) {
		geocoder.geocode({
			latLng: position
			},function(responses) {
				$getID('search_location').value = responses[0].formatted_address;
				$getID('map_la').value = marker.getPosition().lat(); 
				$getID('map_lo').value = marker.getPosition().lng();
				map.panTo(marker.getPosition());
		});
	}
	function initialize(){
		map_lo=map_lo!='' ? map_lo : '105.86727258378903'; 
		map_la=map_la!='' ? map_la : '20.988668210459167'; 
		/**/
		var mapOptions = {
			center: new google.maps.LatLng(map_la,map_lo),
			zoom: 8,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}; 
		map = new google.maps.Map(document.getElementById('map_canvas'),mapOptions); 
		var input = document.getElementById('search_location'); 
		var autocomplete = new google.maps.places.Autocomplete(input); 
		autocomplete.bindTo('bounds', map); 
		var location = new google.maps.LatLng (map_la,map_lo); 
		marker = new google.maps.Marker({ position:location}); 
		marker.setMap(map); 
		marker.setDraggable(true); 
		google.maps.event.addListener(marker, "dragend", function(event){ 
			var point = marker.getPosition(); 
			map.panTo(point); 
			geocode(point);
		}); 
		/**/ 
		google.maps.event.addListener(autocomplete, 'place_changed', function(){
			var place = autocomplete.getPlace();
			if(place.geometry.viewport){ 
				map.fitBounds(place.geometry.viewport); 
			}else{
				map.setCenter(place.geometry.location); map.setZoom(17); 
			}
			geocode(place.geometry.location);
			marker.setPosition(place.geometry.location); 
		}); 
	}  
	/**/ 
	function showAddress(address){
		geocoder = new google.maps.Geocoder(); 
		geocoder.geocode({'address': address},function(results,status){
			if (status == google.maps.GeocoderStatus.OK) {
				marker.setPosition(results[0].geometry.location);
				geocode(results[0].geometry.location);
			} else {
				alert(not_find_location);
			}
		});
	};
	/*End showAddress*/ 
	$(document).ready(function(){
		initialize(); 
		$('#searchMap').click(function(){
			var address=$('#search_location').val(); 
			showAddress(address); 
			return false; 
		}); 
	});
</script>
{/literal}