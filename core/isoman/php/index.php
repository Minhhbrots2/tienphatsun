<?
function default_isoman_load_open_dialog(){
	global $dbconn, $_LANG_ID, $core;
	#
	if ($core->_SESS->isLoggedin()){}
	else{
		header("location: /");
		exit();
	}
	#
	if(_isoman_ftp=="1"){
		$dir = _isoman_ftp_dir; 
		$url = _isoman_ftp_url;
		$abs_url = _isoman_ftp_url;
	}else{
		$dir = _isoman_dir; 
		$url = _isoman_url;
		$abs_url = _isoman_abs_url; 
	}	
	#
	$for_id = $_POST['for_id'];
	$isInIframe = $_POST['isInIframe'];
	$isoman_multiple = $_POST['isoman_multiple'];
	$typelist = vnSessionGetVar("isoman_typelist")==''?0:vnSessionGetVar("isoman_typelist");
	$level = 0;
	vnSessionSetVar("isoman_dir_root",$dir);
	vnSessionSetVar("isoman_url_root",$url);
	#
	$html = '
	<div class="headPop"> 
		<a href="javascript:void();" class="closeEv close_pop"></a> 
		<b>IsoManager</b> 
	</div> 
	<input type="hidden" class="isoman-current-for_id" value="'.$for_id.'" />
	<input type="hidden" class="isoman-current-multiple-'.$for_id.'" value="'.$isoman_multiple.'" />
	<input type="hidden" class="isoman-current-multiple-store-'.$for_id.'" value="" />
	<input type="hidden" id="isoman-current-abs_url-'.$for_id.'" value="'.$abs_url.'" />
	<input type="hidden" id="isoman-current-typelist-'.$for_id.'" value="'.($typelist==1?'1':'0').'" />
	<input type="hidden" id="isoman-current-dir-'.$for_id.'" value="'.$dir.'" />
	<input type="hidden" id="isoman-current-url-'.$for_id.'" value="'.$url.'" />
	<input type="hidden" id="isoman-current-level-'.$for_id.'" value="'.$level.'" />
	<input type="hidden" id="isoman-current-dir_root-'.$for_id.'" value="'.vnSessionGetVar("isoman_dir_root").'" />
	<input type="hidden" class="isInIframe" id="isoman-current-isInIframe-'.$for_id.'" value="'.$isInIframe.'" />
	<input type="hidden" id="isoman-current-domain-'.$for_id.'" value="'._isoman_domain.'" />
	<div id="isoman-46-body" class="isoman-container-body isoman-abs-layout" style="width: 798px; height: 498px;">
    	<div id="isoman-46-absend" class="isoman-abs-end"></div>
   		<div id="isoman-create" class="isoman-container isoman-panel isoman-first isoman-abs-layout-item" hidefocus="1" tabindex="-1" style="border-width: 0px 0px 1px; left: 0px; top: 0px; width: 798px; height: 38px;">
			<div id="isoman-47-body" class="isoman-container-body isoman-abs-layout" style="width: 798px; height: 38px;">
				<div id="isoman-47-absend" class="isoman-abs-end"></div>
				<div id="isoman-48" class="isoman-folder-create isoman-widget isoman-btn isoman-menubtn isoman-first isoman-abs-layout-item" tabindex="-1" role="button" aria-haspopup="true" aria-disabled="false" style="left: 4px; top: 4px; width: 95.8125px; height: 28px;" aria-expanded="true" title="'.$core->get_Lang('Create Folder').'">
					<button id="isoman-48-open" role="presentation" type="button" tabindex="-1" style="height: 100%; width: 100%;">
						<i class="isoman-ico isoman-i-create"></i><span>&nbsp;'.$core->get_Lang('Folder').'</span>
					</button>
				</div>
				<div id="isoman-49" class="isoman-widget isoman-btn isoman-abs-layout-item" tabindex="-1" role="button" aria-disabled="false" style="left: 105.8125px; top: 4px; width: 84.265625px; height: 28px;" title="'.$core->get_Lang('Upload File').'">
					<form method="post" action="" enctype="multipart/form-data">
						<button class="isoman-upload-file" role="presentation" type="button" tabindex="-1" style="height: 100%; width: 100%;">
							<i class="isoman-ico isoman-i-upload"></i>&nbsp;'.$core->get_Lang('Upload').'
						</button>
						<input class="isoman-upload-file-input" name="attachments[]" type="file" id="isoman-upload-file-'.$for_id.'" style="display:none;" multiple="multiple" />
					</form>
				</div>
				<div id="isoman-50" class="isoman-menu-paste isoman-widget isoman-btn isoman-abs-layout-item" tabindex="-1" role="button" style="display: none;left: 196.078125px;top: 4px;">
					<button role="presentation" type="button" tabindex="-1">
						<i class="isoman-ico isoman-i-paste"></i>&nbsp;Paste
					</button>
				</div>
				<div id="isoman-action" class="isoman-widget isoman-btn isoman-menubtn isoman-abs-layout-item" tabindex="-1" role="button" aria-haspopup="true" aria-disabled="false" style="left: 196.078125px; top: 4px; width: 104.375px; height: 28px; display:none;" aria-expanded="true">
					<button id="isoman-51-open" role="presentation" type="button" tabindex="-1" style="height: 100%; width: 100%;">
						<i class="isoman-ico isoman-i-manage"></i><span>&nbsp;Manage</span> <i class="isoman-caret"></i>

					</button>
				</div>
				<div id="isoman-52" class="isoman-abs-layout-item isoman-spacer" style="left: 306.453125px; top: 4px; width: 97.96875px; height: 0px;"></div>
				<div class="isoman-widget isoman-btn isoman-abs-layout-item" tabindex="-1" role="button" aria-labeledby="isoman-53" aria-label="Refresh file list" style="left: 490.421875px; top: 4px; width: 36px; height: 28px;">
					<button role="presentation" type="button" tabindex="-1" style="height: 100%; width: 100%;">
						<i class="isoman-ico isoman-i-refresh" id="isoman-reload-'.$for_id.'" cval=""></i>
					</button>
				</div>
				<div id="isoman-54" class="isoman-container isoman-abs-layout-item isoman-btn-group" role="toolbar" style="left: 532.421875px; top: 4px; width: 74px; height: 30px;">
					<div id="isoman-54-body" style="width: 74px; height: 30px;">
						<div id="isoman-55" typelist="1" class="isoman-widget isoman-switch isoman-btn isoman-first '.($typelist==0?'':'isoman-active').'" tabindex="-1" role="button" aria-pressed="false" aria-labeledby="isoman-55" aria-label="List" style="margin-right:-5px;" title="'.$core->get_Lang('View By List').'">
							<button role="presentation" type="button" tabindex="-1" style="padding: 6px;"><i class="isoman-ico isoman-i-list"></i></button>
						</div>
						<div id="isoman-56" typelist="0" class="isoman-widget isoman-switch isoman-btn isoman-last '.($typelist==1?'':'isoman-active').'" tabindex="-1" role="button" aria-labeledby="isoman-56" aria-label="Thumbnails" aria-pressed="true" title="'.$core->get_Lang('View By Thumbail').'">
							<button role="presentation" type="button" tabindex="-1" style="padding: 6px;"><i class="isoman-ico isoman-i-thumbs"></i></button>
						</div>
					</div>
				</div>
				<div id="isoman-sort" class="isoman-widget isoman-btn isoman-menubtn isoman-abs-layout-item" tabindex="-1" role="button" aria-haspopup="true" style="left: 528.421875px; top: 4px; width: 59.578125px; height: 28px; display:none;">
					<button id="isoman-57-open" role="presentation" type="button" tabindex="-1" style="height: 100%; width: 100%;"><span>'.$core->get_Lang('Sort').'</span> <i class="isoman-caret"></i></button>
				</div>
				<div id="isoman-58" class="isoman-combobox isoman-placeholder isoman-abs-layout-item isoman-has-open" aria-disabled="false" style="left: 594px; top: 4px; width: 200px; height: 30px;">
					<input id="isoman-search-'.$for_id.'" class="isoman-textbox isoman-placeholder isoman-search" value="" placeholder="'.$core->get_Lang('Filter').'" hidefocus="true" style="width: 157px; margin-right:-5px;">
					<div id="isoman-58-open" class="isoman-btn isoman-open isoman-search-action" tabindex="-1">
						<button id="isoman-search-action-'.$for_id.'" type="button" hidefocus="" tabindex="-1" style="padding: 6px;"><i class="isoman-ico isoman-i-search"></i></button>
					</div>
				</div>
				<div id="isoman-59" class="isoman-widget isoman-btn isoman-last isoman-abs-layout-item" tabindex="-1" role="button" aria-labeledby="isoman-59" aria-label="'.$core->get_Lang('logout').'" style="left: 756px; top: 4px; width: 36px; height: 28px; display: none;">
					<button role="presentation" type="button" tabindex="-1" style="height: 100%; width: 100%;"><i class="isoman-ico isoman-i-logout"></i></button>
				</div>
			</div>
		</div>
		<div id="isoman-63" class="isoman-container isoman-panel isoman-last isoman-abs-layout-item" hidefocus="1" tabindex="-1" style="top: 39px; width: 798px; height: 459px;">
			<div id="isoman-63-body" class="isoman-container-body isoman-abs-layout" style="width: 798px; height: 459px;">
				<div id="isoman-63-absend" class="isoman-abs-end"></div>
				<div id="isoman-64" class="isoman-container isoman-panel isoman-first isoman-last isoman-abs-layout-item" hidefocus="1" tabindex="-1" style="border-width: 0px; left: 0px; top: 0px; width: 798px; height: 459px;">
					<div id="isoman-64-body" class="isoman-container-body isoman-abs-layout" style="width: 798px; height: 459px;">
						<div id="isoman-64-absend" class="isoman-abs-end"></div>
						<div id="isoman-path" class="isoman-path isoman-first isoman-abs-layout-item" style="left: 0px; top: 0px; width: 798px; height: 32px;">
														
						</div>					
						<div id="isoman-main-container" class="isoman-thumbnailview isoman-container isoman-last isoman-abs-layout-item" role="undefined" style="border-width: 1px 0px 0px; left: 0px; top: 32px; width: 798px; height: 426px;">
							<div id="isoman-main-container-body" class="isoman-container-body isoman-flow-layout" style="width: 798px; height: 426px;">
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="isoman-action-menu" class="isoman-container isoman-panel isoman-floatpanel isoman-menu isoman-menu-has-icons isoman-fixed isoman-menu-align" hidefocus="1" tabindex="-1" role="menu" style="border-width: 1px; z-index: 65540; '.($isInIframe==1?'left: 227.078125px;top: 87.5px;':'left: 449.078125px; top: 120.5px;').'  width: 178.5px; display:none;">
		<div id="isoman-309-body" class="isoman-container-body isoman-stack-layout" style="width: 178.5px;">
			<div id="isoman-menu-cut-'.$for_id.'" class="isoman-menu-cut isoman-menu-item isoman-menu-item-normal isoman-stack-layout-item isoman-first" tabindex="-1" role="menuitem"><i class="isoman-ico isoman-i-cut"></i>&nbsp;<span id="isoman-310-text" class="isoman-text">'.$core->get_Lang('cut').'</span><div id="isoman-310-shortcut" class="isoman-menu-shortcut">Ctrl+X</div></div>
			<div id="isoman-menu-copy-'.$for_id.'" class="isoman-menu-copy isoman-menu-item isoman-menu-item-normal isoman-stack-layout-item" tabindex="-1" role="menuitem"><i class="isoman-ico isoman-i-copy"></i>&nbsp;<span id="isoman-311-text" class="isoman-text">'.$core->get_Lang('copy').'</span><div id="isoman-311-shortcut" class="isoman-menu-shortcut">Ctrl+C</div></div>
			<div style="display:none;" id="isoman-menu-paste-'.$for_id.'" class="isoman-menu-paste isoman-menu-item isoman-menu-item-normal isoman-stack-layout-item" tabindex="-1" role="menuitem" aria-disabled="true"><i class="isoman-ico isoman-i-paste"></i>&nbsp;<span id="isoman-312-text" class="isoman-text">'.$core->get_Lang('Paste').'</span><div id="isoman-312-shortcut" class="isoman-menu-shortcut">Ctrl+V</div></div>
			<div id="isoman-313" class="isoman-menu-item isoman-menu-item-sep isoman-menu-item-normal isoman-stack-layout-item" tabindex="-1" role="separator"></div>
			<div id="isoman-menu-view-'.$for_id.'" class="isoman-disabled isoman-menu-view isoman-menu-item isoman-menu-item-normal isoman-stack-layout-item" tabindex="-1" role="menuitem" aria-disabled="false"><i class="isoman-ico isoman-i-view"></i>&nbsp;<span id="isoman-314-text" class="isoman-text">'.$core->get_Lang('view').'</span></div>
			<div id="isoman-316" class="isoman-rename isoman-menu-item isoman-menu-item-normal isoman-stack-layout-item" tabindex="-1" role="menuitem" aria-disabled="false"><i class="isoman-ico isoman-i-none"></i>&nbsp;<span id="isoman-316-text" class="isoman-text">'.$core->get_Lang('Rename').'</span></div>
			<!--<div id="isoman-317" class="isoman-menu-item isoman-menu-item-normal isoman-stack-layout-item" tabindex="-1" role="menuitem"><i class="isoman-ico isoman-i-download"></i>&nbsp;<span id="isoman-317-text" class="isoman-text">'.$core->get_Lang('Download').'</span></div>
			<div id="isoman-319" class="isoman-menu-item isoman-menu-item-sep isoman-menu-item-normal isoman-stack-layout-item" tabindex="-1" role="separator"></div>-->
			<!--<div id="isoman-320" class="isoman-menu-item isoman-menu-item-normal isoman-stack-layout-item" tabindex="-1" role="menuitem"><i class="isoman-ico isoman-i-zip"></i>&nbsp;<span id="isoman-320-text" class="isoman-text">'.$core->get_Lang('Zip').'</span></div>
			<div id="isoman-321" class="isoman-menu-item isoman-menu-item-normal isoman-stack-layout-item isoman-disabled" tabindex="-1" role="menuitem" aria-disabled="true"><i class="isoman-ico isoman-i-none"></i>&nbsp;<span id="isoman-321-text" class="isoman-text">'.$core->get_Lang('Unzip').'</span></div>-->
			<div id="isoman-322" class="isoman-menu-item isoman-menu-item-sep isoman-menu-item-normal isoman-stack-layout-item" tabindex="-1" role="separator"></div>
			<div id="isoman-323" class="isoman-menu-remove isoman-menu-item isoman-menu-item-normal isoman-stack-layout-item isoman-last" tabindex="-1" role="menuitem"><i class="isoman-ico isoman-i-delete"></i>&nbsp;<span id="isoman-323-text" class="isoman-text">'.$core->get_Lang('delete').'</span><div id="isoman-323-shortcut" class="isoman-menu-shortcut">'.$core->get_Lang('delete').'</div>
						</div>
		</div>
	</div>
	<div id="isoman-sort-menu" class="isoman-container isoman-panel isoman-floatpanel isoman-menu isoman-menu-has-icons isoman-fixed isoman-menu-align" hidefocus="1" tabindex="-1" role="menu" style="border-width: 1px; z-index: 65539; left: 781.421875px; top: 120.5px; width: 160px; display:none;"><div id="isoman-324-body" class="isoman-container-body isoman-stack-layout" style="width: 160px;"><div id="isoman-325" class="isoman-menu-item isoman-menu-item-checkbox isoman-first isoman-stack-layout-item isoman-active" tabindex="-1" role="menuitemcheckbox" aria-checked="true" aria-pressed="true"><i class="isoman-ico isoman-i-selected"></i>&nbsp;<span id="isoman-325-text" class="isoman-text">'.$core->get_Lang('Name').'</span></div><div id="isoman-326" class="isoman-menu-item isoman-menu-item-checkbox isoman-stack-layout-item" tabindex="-1" role="menuitemcheckbox" aria-checked="true" aria-pressed="false"><i class="isoman-ico isoman-i-selected"></i>&nbsp;<span id="isoman-326-text" class="isoman-text">'.$core->get_Lang('Size').'</span></div><div id="isoman-327" class="isoman-menu-item isoman-menu-item-checkbox isoman-stack-layout-item" tabindex="-1" role="menuitemcheckbox" aria-checked="true" aria-pressed="false"><i class="isoman-ico isoman-i-selected"></i>&nbsp;<span id="isoman-327-text" class="isoman-text">'.$core->get_Lang('Type').'</span></div><div id="isoman-328" class="isoman-menu-item isoman-menu-item-checkbox isoman-last isoman-stack-layout-item" tabindex="-1" role="menuitemcheckbox" aria-checked="true" aria-pressed="false"><i class="isoman-ico isoman-i-selected"></i>&nbsp;<span id="isoman-328-text" class="isoman-text">'.$core->get_Lang('Modification date').'</span></div></div></div>	
	<div class="modal-footer"> 
		<button class="btn btn-success isoman-insert" id="isoman-insert-'.$for_id.'" isoman_url="" style="display:none;">'.$core->get_Lang('insert').'</button> 
		<button class="btn btn-warning close_pop" data-dismiss="modal" aria-hidden="true">'.$core->get_Lang('close').'</button> 
	</div>
	';
	#
	echo $html; die();
}
function default_isoman_load_open_folder(){
	global $dbconn, $_LANG_ID, $core;
	#
	$lstItemDirTemp = array();
	$cval = $_POST['cval'];
	if(_isoman_ftp==0){
		$dir = isset($_POST['isoman_dir'])?$_POST['isoman_dir']:_isoman_dir;
		$url = isset($_POST['isoman_url'])?$_POST['isoman_url']:_isoman_url;
		$abs_url = _isoman_abs_url;
		$typelist = $_POST['isoman_typelist'];
		
		vnSessionSetVar("isoman_dir",$dir);
		vnSessionSetVar("isoman_typelist",$typelist);
		$isoman_dir_root = vnSessionGetVar("isoman_dir_root");
		$isoman_url_root = vnSessionGetVar("isoman_url_root");
		
		$isoman_search = $_POST['isoman_search'];
		if ($handle = opendir($dir)) {
			/* This is the correct way to loop over the directory. */
			while (false !== ($entry = readdir($handle))) {
				if($entry!='.'&&$entry!='..'){
					if(is_dir($dir.'/'.$entry))
						$tmp[0] = '0';
					else
						$tmp[0] = '1';
					$tmp[1] = $entry;
					$lstItemDirTemp[] = $tmp;
				}
			}
			closedir($handle);
		}
	}
	else{
		$dir = $_POST['isoman_dir']!=""?$_POST['isoman_dir']:_isoman_ftp_dir;	
		vnSessionSetVar("isoman_dir",$dir);
		
		$url = $_POST['isoman_url']!=""?$_POST['isoman_url']:_isoman_ftp_url;	
		$abs_url = _isoman_abs_url;
		$typelist = $_POST['isoman_typelist'];
		vnSessionSetVar("isoman_typelist",$typelist);
		
		$isoman_dir_root = vnSessionGetVar("isoman_dir_root");
		$isoman_url_root = vnSessionGetVar("isoman_url_root");
		
		$isoman_search = $_POST['isoman_search'];
		
		$conn_id = ftp_connect(_isoman_ftp_host_info);
		$login_result = ftp_login($conn_id, _isoman_ftp_usr_info, _isoman_ftp_pwd_info);
		$cdir = str_replace(_isoman_ftp_dir,'',$dir); //echo($cdir.'.');die();
		$contents = ftp_nlist($conn_id, $cdir);
		#
		for($i=0;$i<count($contents);$i++){
			$entry = $contents[$i];
			$t = explode('/',$contents[$i]);			
			$entry = $t[count($t)-1];
			if($entry!='.'&&$entry!='..'){
				if(@ftp_chdir($conn_id,$contents[$i]))
					$tmp[0] = 0;
				else
					$tmp[0] = 1;
				$tmp[1] = $entry;
				$lstItemDirTemp[] = $tmp;
				ftp_cdup($conn_id);
			}
		}
		ftp_close($conn_id);
	}
	#sort
	$lstItemDirFolder = array();
	foreach($lstItemDirTemp as $k => $v){
		if($v[0]==0){
			$lstItemDirFolder[] = $v;
			unset($lstItemDirTemp[$k]);
		}
	}
	for($i=0,$max=count($lstItemDirFolder);$i<$max;$i++){
		for($j=0;$j<$i;$j++){
			if($lstItemDirFolder[$j][1][0]>$lstItemDirFolder[$i][1][0]){
				$tmp = $lstItemDirFolder[$j];
				$lstItemDirFolder[$j] = $lstItemDirFolder[$i];
				$lstItemDirFolder[$i] = $tmp;
			}
		}
	}
	$lstItemDirFileTemp = array();
	foreach($lstItemDirTemp as $k => $v){
		if($v[0]==1){
			$lstItemDirFileTemp[] = array(
				'filemtime'	=> filemtime($dir.'/'.$v[1]),
				'filename'	=> $v
			);
			unset($lstItemDirTemp[$k]);
		}
	}
	function sortOrder($a, $b) {
		return $b['filemtime'] - $a['filemtime'];
	}
	$lstItemDirFile = array();	
	if(!empty($lstItemDirFileTemp)){
		usort($lstItemDirFileTemp, 'sortOrder');
		foreach($lstItemDirFileTemp as $k => $v){
			$lstItemDirFile[] = $v['filename'];
		}
		unset($lstItemDirFileTemp);
	}
	#
	$lstItemDir = array_merge($lstItemDirFolder,$lstItemDirFile);
	$dirArr = explode('/',$dir);
	$data_parent_dir = '';
	for($i=0;$i<count($dirArr);$i++){
		if($i<count($dirArr)-1){
			$data_parent_dir .= ($i==0?'':'/').$dirArr[$i];
		}
	}
	$data_parent_url = 'http://';
	$urlArr = explode('/',str_replace('http://','',$url));
	for($i=0;$i<count($urlArr);$i++){
		if($i<count($urlArr)-1){
			$data_parent_url .= ($i==0?'':'/').$urlArr[$i];
		}
	}
	#
	$abs_url_arr = explode('/',str_replace('http://','',$abs_url));
	if($typelist==0){
		#
		$data_parent_dir = str_replace('http://','',$data_parent_dir);
		$data_parent_url = str_replace('http://','',$data_parent_url);
		#
		if(vnSessionGetVar("isoman_dir_root")!=$dir)
		$html .= '
		<div class="isoman-image isoman-go-back" unselectable="on" data-isoman-isparent="parent" isoman_dir="'.$data_parent_dir.'" isoman_url="'.$data_parent_url.'"> 
			<i class="isoman-ico isoman-thumb isoman-i-parent"></i>
			<div class="isoman-info" title="..">..</div>
		</div>';
		for($i=0;$i<count($lstItemDir);$i++){
			$isoman_full_url = $url.'/'.$lstItemDir[$i][1];
			$isoman_short_url = $isoman_full_url;
			#
			if($isoman_search!=''){
				if(str_replace($isoman_search,'',$lstItemDir[$i][1])!=$lstItemDir[$i][1]){
					if($lstItemDir[$i][0]){
						$html .= '
							<div isoman_dir="'.$dir.'/'.$lstItemDir[$i][1].'" isoman_url="'.$isoman_short_url.'" class="isoman-image unselectable="on" data-isoman-isparent="" isoman_type="'.$lstItemDir[$i][0].'">
								<img src="'.recove_images($isoman_full_url,100,77).'" style="height:77px !important;width:100px !important; margin-top:0px !important;">
								<div class="isoman-info" title="'.$lstItemDir[$i][1].'"><i class="isoman-ico isoman-i-checkbox" '.($lstItemDir[$i][0]==0?'':'isoman_url="'.$isoman_short_url.'"').' isoman_type="'.$lstItemDir[$i][0].'"></i>'.$lstItemDir[$i][1].'</div>
							</div>
						';
					}
					else{
						$html .= '
						<div class="isoman-image" unselectable="on" data-isoman-isparent="" isoman_dir="'.$dir.'/'.$lstItemDir[$i][1].'" isoman_url="" isoman_type="'.$lstItemDir[$i][0].'">
							<i class="isoman-ico isoman-thumb isoman-i-folder" isoman_dir="'.$dir.'/'.$lstItemDir[$i][1].'" isoman_url="'.$isoman_short_url.'" data="'.$lstItemDir[$i][1].'" isoman_type="'.$lstItemDir[$i][0].'"></i>
							<div class="isoman-info isoman-open-folder" isoman_dir="'.$dir.'/'.$lstItemDir[$i][1].'" isoman_url="'.$isoman_short_url.'" title="'.$lstItemDir[$i][1].'">
								<i class="isoman-ico isoman-i-checkbox" '.($lstItemDir[$i][0]==0?'':'isoman_url="'.$isoman_short_url.'"').' isoman_type="'.$lstItemDir[$i][0].'"></i>'.$lstItemDir[$i][1].'
							</div>
						</div>
					';
					}
				}
			}
			else{
				if($lstItemDir[$i][0]){
					$html .= '
						<div isoman_dir="'.$dir.'/'.$lstItemDir[$i][1].'" isoman_url="'.$isoman_short_url.'" isoman_type="'.$lstItemDir[$i][0].'" class="isoman-image" unselectable="on" data-isoman-isparent="">
							<img src="'.recove_images($isoman_full_url,100,77).'" style="height:77px !important;width:100px !important; margin-top:0px !important;">
							<div class="isoman-info" title="'.$lstItemDir[$i][1].'"><i class="isoman-ico isoman-i-checkbox" '.($lstItemDir[$i][0]==0?'':'isoman_url="'.$isoman_short_url.'"').' isoman_dir="'.$dir.'/'.$lstItemDir[$i][1].'" isoman_type="'.$lstItemDir[$i][0].'"></i>'.$lstItemDir[$i][1].'</div>
						</div>
					';
				}
				else{
					$html .= '
					<div isoman_dir="'.$dir.'/'.$lstItemDir[$i][1].'" isoman_url="" isoman_type="'.$lstItemDir[$i][0].'" class="isoman-image" unselectable="on" data-isoman-isparent="">
						<i class="isoman-ico isoman-thumb isoman-i-folder" isoman_dir="'.$dir.'/'.$lstItemDir[$i][1].'" isoman_url="'.$isoman_short_url.'" data="'.$lstItemDir[$i][1].'" isoman_dir="'.$dir.'/'.$lstItemDir[$i][1].'" isoman_type="'.$lstItemDir[$i][0].'"></i>
						<div class="isoman-info isoman-open-folder" isoman_dir="'.$dir.'/'.$lstItemDir[$i][1].'" isoman_url="'.$isoman_short_url.'" title="'.$lstItemDir[$i][1].'">
							<i class="isoman-ico isoman-i-checkbox" '.($lstItemDir[$i][0]==0?'':'isoman_url="'.$isoman_short_url.'"').' isoman_dir="'.$dir.'/'.$lstItemDir[$i][1].'" isoman_type="'.$lstItemDir[$i][0].'"></i>'.$lstItemDir[$i][1].'
						</div>
					</div>
				';
				}
			}
		}
	}
	else{
		$html .= '
			<div id="isoman-95" class="isoman-abs-layout-item isoman-grid" style="border-width: 0px 0px 0px; left: 0px; width: 798px; height: 426px;">
				<div class="isoman-grid-head" unselectable="true">
					<table id="isoman-95-head" class="isoman-grid-head" style="width: 790px;">
						<tbody><tr id="isoman-95-headr"><td class="isoman-grid-head-item" style="width: 25px"><div class="isoman-txt isoman-grid-cell"><i class="isoman-ico isoman-i-checkbox" unselectable="on"></i>&nbsp;</div></td><td class="isoman-grid-head-item"><div class="isoman-txt isoman-grid-cell">Filename</div></td><td class="isoman-grid-head-item" style="width: 60px"><div class="isoman-txt isoman-grid-cell">Size<i class="isoman-caret"></i></div></td><td class="isoman-grid-head-item" style="width: 70px"><div class="isoman-txt isoman-grid-cell">Type<i class="isoman-caret"></i></div></td><td class="isoman-grid-head-item" style="width: 200px"><div class="isoman-txt isoman-grid-cell">Size/Modification date<i class="isoman-caret isoman-up"></i><i class="isoman-caret"></i></div></td></tr></tbody>
					</table>
				</div>
				<div id="isoman-95-body" class="isoman-grid-body" style="width: 798px; height: 398px;">
					<table>
						<thead id="isoman-95-thead"><tr><td style="width: 25px"><div class="isoman-txt isoman-grid-cell">0</div></td><td><div class="isoman-txt isoman-grid-cell">1</div></td><td style="width: 60px"><div class="isoman-txt isoman-grid-cell">2</div></td><td style="width: 70px"><div class="isoman-txt isoman-grid-cell">3</div></td><td style="width: 200px"><div class="isoman-txt isoman-grid-cell">4</div></td></tr></thead>
						<tbody id="isoman-95-tbody">';
		if(vnSessionGetVar("isoman_dir_root")!=$dir){
			$html .= '
				<tr class="isoman-grid-row">
					<td>
						<div class="isoman-txt isoman-grid-cell">
							<span class="isoman-txt isoman-reset" title="undefined">&nbsp;</span>
						</div>
					</td>
					<td>
						<div class="isoman-txt isoman-grid-cell isoman-go-back" style="cursor:pointer;" data-isoman-isparent="parent"  isoman_dir="'.$data_parent_dir.'" isoman_url="'.$data_parent_url.'">
							<i class="isoman-ico isoman-i-parent" unselectable="on"></i>
							<span class="isoman-txt isoman-reset" title="..">..</span>
						</div>
					</td>
					<td>
						<div class="isoman-txt isoman-grid-cell">
							<span class="isoman-txt isoman-reset" title="">&nbsp;</span>
						</div>
					</td>
					<td>
						<div class="isoman-txt isoman-grid-cell">
							<span class="isoman-txt isoman-reset" title="undefined">&nbsp;</span>
						</div>
					</td>
					<td>
						<div class="isoman-txt isoman-grid-cell">
							<span class="isoman-txt isoman-reset" title="-">-</span>
						</div>
					</td>
				</tr>
			';
		}
		for($i=0;$i<count($lstItemDir);$i++){
			$isoman_full_url = $url.'/'.$lstItemDir[$i][1];
			$isoman_short_url = $isoman_full_url;
			#
			$ext = explode('.',$lstItemDir[$i][1]);
			/*if($lstItemDir[$i][0]){
				if(_isoman_ftp==1){
					list($width, $height, $type, $attr) = getimagesize($url.'/'.$lstItemDir[$i][1]);
				}else
					list($width, $height, $type, $attr) = getimagesize($dir.'/'.$lstItemDir[$i][1]);
			}*/
			$cdir_item = $dir.'/'.$lstItemDir[$i][1];
			#
			if($isoman_search!=''){
				if(str_replace($isoman_search,'',$lstItemDir[$i][1])!=$lstItemDir[$i][1]){
					$html .= '
					<tr class="isoman-grid-row" '.($lstItemDir[$i][0]==0?'isoman_url=""':'isoman_url="'.$isoman_short_url.'"').' isoman_dir="'.$dir.'/'.$lstItemDir[$i][1].'" isoman_type="'.$lstItemDir[$i][0].'">
						<td>
							<div class="isoman-txt isoman-grid-cell">
								<i class="isoman-ico isoman-i-checkbox" unselectable="on"></i>
								<span class="isoman-txt isoman-reset" title="undefined">&nbsp;</span>
							</div>
						</td>
						<td>
							<div class="isoman-txt isoman-grid-cell '.($lstItemDir[$i][0]==0?'isoman-open-folder':'').'" style="cursor:pointer;" isoman_dir="'.$dir.'/'.$lstItemDir[$i][1].'" isoman_url="'.$isoman_short_url.'" data="'.$lstItemDir[$i][1].'" isoman_type="'.$lstItemDir[$i][0].'">
								<i class="isoman-ico isoman-i-'.($lstItemDir[$i][0]==0?'folder':'file-image').'" unselectable="on" isoman_dir="'.$dir.'/'.$lstItemDir[$i][1].'" isoman_url="'.$isoman_short_url.'" data="'.$lstItemDir[$i][1].'" isoman_type="'.$lstItemDir[$i][0].'"></i>
								<span class="isoman-txt isoman-reset" title="'.$lstItemDir[$i][1].'">'.$lstItemDir[$i][1].'</span>
							</div>
						</td>
						<td align="right">
							<div class="isoman-txt isoman-grid-cell">
								<span class="isoman-txt isoman-reset" title="">
									'.($lstItemDir[$i][0]==0?'-':isoman_FileSizeConvert(filesize($cdir_item))).'
								</span>
							</div>
						</td>
						<td>
							<div class="isoman-txt isoman-grid-cell">
								<span class="isoman-txt isoman-reset" title="dir">
									'.($lstItemDir[$i][0]==0?'dir':$ext[count($ext)-1]).'
								</span>
							</div>
						</td>
						<td>
							<div class="isoman-txt isoman-grid-cell">
								<span class="isoman-txt isoman-reset" title="'.($lstItemDir[$i][0]==0?'-':''.date('d/m/Y h:i:s a',filemtime($dir.'/'.$lstItemDir[$i][1]))).'">
									'.''.($lstItemDir[$i][0]==0?'-':date('d/m/Y h:i:s a',filemtime($dir.'/'.$lstItemDir[$i][1]))).'
								</span>
							</div>
						</td>
					</tr>
				';
				}
			}
			else{
				$html .= '
					<tr class="isoman-grid-row" '.($lstItemDir[$i][0]==0?'isoman_url=""':'isoman_url="'.$isoman_short_url.'"').' isoman_dir="'.$dir.'/'.$lstItemDir[$i][1].'" isoman_type="'.$lstItemDir[$i][0].'">
						<td>
							<div class="isoman-txt isoman-grid-cell">
								<i class="isoman-ico isoman-i-checkbox" unselectable="on"></i>
								<span class="isoman-txt isoman-reset" title="undefined">&nbsp;</span>
							</div>
						</td>
						<td>
							<div class="isoman-txt isoman-grid-cell '.($lstItemDir[$i][0]==0?'isoman-open-folder':'isoman-image-list').'" style="cursor:pointer;" isoman_dir="'.$dir.'/'.$lstItemDir[$i][1].'" isoman_url="'.$isoman_short_url.'" data="'.$lstItemDir[$i][1].'" isoman_type="'.$lstItemDir[$i][0].'">
								<i class="isoman-ico isoman-i-'.($lstItemDir[$i][0]==0?'folder':'file-image').'" unselectable="on" isoman_dir="'.$dir.'/'.$lstItemDir[$i][1].'" isoman_url="'.$isoman_short_url.'" data="'.$lstItemDir[$i][1].'" isoman_type="'.$lstItemDir[$i][0].'"></i>
								<span class="isoman-txt isoman-reset" title="'.$lstItemDir[$i][1].'">'.$lstItemDir[$i][1].'</span>
							</div>
						</td>
						<td align="right">
							<div class="isoman-txt isoman-grid-cell">
								<span class="isoman-txt isoman-reset" title="">
									'.($lstItemDir[$i][0]==0?'-':isoman_FileSizeConvert(filesize($cdir_item))).'
								</span>
							</div>
						</td>
						<td>
							<div class="isoman-txt isoman-grid-cell">
								<span class="isoman-txt isoman-reset" title="dir">
									'.($lstItemDir[$i][0]==0?'dir':$ext[count($ext)-1]).'
								</span>
							</div>
						</td>
						<td>
							<div class="isoman-txt isoman-grid-cell">
								<span class="isoman-txt isoman-reset" title="'.($lstItemDir[$i][0]==0?'-':''.date('d/m/Y h:i:s a',filemtime($dir.'/'.$lstItemDir[$i][1]))).'">
									'.($lstItemDir[$i][0]==0?'-':''.date('d/m/Y h:i:s a',filemtime($dir.'/'.$lstItemDir[$i][1]))).'
								</span>
							</div>
						</td>
					</tr>
				';
			}
		}			
		$html .= '
						</tbody>
					</table>
				</div>
			</div>
		';
	}
	$htmlPath = '<div role="button" class="isoman-path-item isoman-open-path" isoman-dir="'.$isoman_dir_root.'" isoman-url="'.$isoman_url_root.'">root</div>';
	$dirChild = str_replace($isoman_dir_root,'',$dir);
	$dirArr = explode('/',$dirChild);
	for($i=0;$i<count($dirArr);$i++){
		if($dirArr[$i]!=''){
			$current_dir = $isoman_dir_root;
			$current_url = $isoman_url_root;
			for($j=1;$j<=$i;$j++){
				$current_dir.='/'.$dirArr[$j];
				$current_url.='/'.$dirArr[$j]; 
			}
			$htmlPath .= '
			<div class="isoman-divider" aria-hidden="true"> / </div>
			<div role="button" class="isoman-path-item isoman-open-path" isoman-dir="'.$current_dir.'" isoman-url="'.$current_url.'">
				'.$dirArr[$i].'
			</div>';
		}
	}
	echo $html.'|||'.$htmlPath; die();
}
function isoman_FileSizeConvert($bytes)
{
    $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = floor($result)." ".$arItem["UNIT"];//str_replace(".", "," , strval(round($result, 2)))
            break;
        }
    }
    return $bytes==0?'-':$result;
}
function isoman_getfilesize($path) {
	$filesize = filesize($path);
	return $filesize;
}
function isoman_getfoldersize($path) {
  $total_size = 0; 
  if(_isoman_ftp==0){
	  $files = scandir($path);
	  foreach($files as $t) {
		if (is_dir(rtrim($path, '/') . '/' . $t)) {
		  if ($t<>"." && $t<>"..") {
			  $size = isoman_getfoldersize(rtrim($path, '/') . '/' . $t);
	
			  $total_size += $size;
		  }
		} else {
		  $size = filesize(rtrim($path, '/') . '/' . $t);
		  $total_size += $size;
		}
	  }
  }
  else{
		return 0;
		$conn_id = ftp_connect(_isoman_ftp_host_info);
		$login_result = ftp_login($conn_id, _isoman_ftp_usr_info, _isoman_ftp_pwd_info);
		$files = ftp_nlist($conn_id, $path);
	    foreach($files as $t) {
		if (is_dir(rtrim($path, '/') . '/' . $t)) {
		  if ($t<>"." && $t<>"..") {
			  $size = isoman_getfoldersize(rtrim($path, '/') . '/' . $t);
	
			  $total_size += $size;
		  }
		} else {
		  $size = ftp_size($conn_id,rtrim($path, '/') . '/' . $t);
		  $total_size += $size;
		}
	  }
  }  
  return $total_size;
}
function default_isoman_upload_file(){
	global $core,$_frontIsLoggedin_user_id,$datastore_folder;
	$up = '';
	$currentDir = vnSessionGetVar("isoman_dir");
	$filename = '';
	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
		if(_isoman_ftp==0){
			$list_files = $_FILES['attachments'];
			if(!empty($list_files['name'])){
				for($i = 0; $i < count($list_files); $i++){
					$filezise = isoman_getfilesize($list_files['tmp_name'][$i]);
					if($filezise < 10485760) {
						if(is_uploaded_file($list_files['tmp_name'][$i])){
							$ftmp_name = $list_files['tmp_name'][$i];
							$fname = $list_files['name'][$i];
							$fname = str_replace('&','-',$fname); 
							$fname = str_replace(' ','-',$fname);
							$filename = $currentDir."/".$fname;
							if (@is_writable($currentDir)){
								@copy($ftmp_name, $filename);
								chmod($filename, 0777);
							} else{
								echo('_NOT_VALID');die();
							}
						}
					}
				}
			}
		}
		else{
			$list_files = $_FILES['attachments'];
			if(!empty($list_files['name'])){
				$conn_id = ftp_connect(_isoman_ftp_host_info);
				$login_result = ftp_login($conn_id, _isoman_ftp_usr_info, _isoman_ftp_pwd_info);
				$dir = str_replace(vnSessionGetVar("isoman_dir_root"),'',$currentDir);
				#
				for($i = 0; $i < count($list_files); $i++){
					$filezise = isoman_getfilesize($list_files['tmp_name'][$i]);
					if($filezise < 1048576) {
						if(is_uploaded_file($list_files['tmp_name'][$i])){
							$ftmp_name = $list_files['tmp_name'][$i];
							$fname = $list_files['name'][$i];
							$fname = str_replace('&','-',$fname); 
							$fname = str_replace(' ','-',$fname);
							$filename = $currentDir."/".$fname;
							
							$dir = str_replace('//','','/'.$dir);
							@ftp_chdir($conn_id,$dir);
							#
							ftp_put($conn_id, $filename,$list_files['tmp_name'][$i], FTP_BINARY);
							ftp_chmod($conn_id, 0777, $filename);
							ftp_close($conn_id);
						}
					}
				}
			}
		}
	}
	echo($filename);die(); 
}
function default_isoman_delete_dir(){
	global $core,$_frontIsLoggedin_user_id,$datastore_folder;
	#
	$isoman_dir_root = vnSessionGetVar("isoman_dir_root");
	$isoman_dir = isset($_POST['isoman_dir'])?$_POST['isoman_dir']:'';
	#
	if(isset($isoman_dir) && !empty($isoman_dir)) {
		$isoman_dir = rtrim($isoman_dir,'|');
		$isoman_dir = explode('|',$isoman_dir);
		#
		for($i=0;$i<count($isoman_dir);$i++) {
			if(str_replace($isoman_dir_root,'',$isoman_dir[$i])!=$isoman_dir[$i]){
				if(_isoman_ftp==0){
					if(!@is_writable($isoman_dir_root)){
						echo('_NOT_VALID_WRITE');die(); 
					}
					if(is_dir($isoman_dir[$i])){
						if(isoman_is_dir_empty($isoman_dir[$i])) {
							rmdir_recurse($isoman_dir[$i]);
						}else{
							echo('_NOT_EMPTY');die();
						}
					}else{
						unlink($isoman_dir[$i]);
					}
				}else{
					$conn_id = ftp_connect(_isoman_ftp_host_info);
					$login_result = ftp_login($conn_id, _isoman_ftp_usr_info, _isoman_ftp_pwd_info);
					#
					$dir = str_replace($isoman_dir_root,'',$isoman_dir[$i]); 
					$dir = str_replace('//','','/'.$dir);
					$tmp = explode('/',$dir);
					$dir_up = '';
					for($j=0;$j<count($tmp)-1;$j++){
						$dir_up .= '/'.$tmp[$j];
					}
					@ftp_chdir($conn_id,$dir_up);
					if(@ftp_chdir($conn_id,$tmp[count($tmp)-1])){
						@ftp_cdup($conn_id);
						@ftp_rmdir ($conn_id,$tmp[count($tmp)-1]);
						rmdir_recurse($isoman_dir[$i]);
					}
					else{
						@ftp_delete($conn_id,$tmp[count($tmp)-1]); 
					}
					ftp_close($conn_id); 
				}
			}
		}
	}
	echo('_DELETED');die(); 
}
function isoman_is_dir_empty($dir) {
	return 1;
	if (!is_readable($dir)) return NULL; 
	return (count(scandir($dir)) == 2);
}
function default_isoman_load_one_folder(){
	global $dbconn, $_LANG_ID, $core;
	#
	$dir = $_POST['isoman_dir'];
	$url = $_POST['isoman_url'];
	$abs_url = $_POST['abs_url'];
	$for_id = $_POST['for_id'];
	$folder_name = $_POST['folder_name_dir'];
	$isoman_dir_root = vnSessionGetVar("isoman_dir_root");
	$root_name = '';
	if($folder_name!=''){
		$tmp = explode('/',$folder_name);
		$root_name = $tmp[count($tmp)-1];
	}
	#
	$html = '
	<div class="headPop"> 
		<a href="javascript:void();" class="closeEv close_pop"></a> 
		<b>'.($root_name==''?'Create':'Rename').' Folder</b> 
	</div> 	
	<div style="width:300px;">
		Folder Name: 
		<input type="text" id="isoman_folder_name"  value="'.$root_name.'" />
	</div>
	<div class="modal-footer"> 
		<button class="btn btn-success isoman-update-folder" isoman_root_name="'.$root_name.'" isoman_dir_root="'.$isoman_dir_root.'" isoman_url="'.$url.'" isoman_dir="'.$dir.'" isoman_abs_url="'.$abs_url.'" isoman_for_id="'.$for_id.'">Save</button> 
		<button class="btn btn-warning close_pop" data-dismiss="modal" aria-hidden="true">Close</button> 
	</div>
	';
	#
	echo $html; die();
}
function default_isoman_update_one_folder(){
	global $dbconn, $_LANG_ID, $core;
	#
	$dir 				= isset($_POST['isoman_dir'])?$_POST['isoman_dir']:'';
	$url				= isset($_POST['isoman_url'])?$_POST['isoman_url']:'';
	$abs_url 			= isset($_POST['abs_url'])?$_POST['abs_url']:'';
	$for_id 			= isset($_POST['for_id'])?$_POST['for_id']:'';
	$folder_name 		= isset($_POST['folder_name'])?$_POST['folder_name']:'';
	$isoman_root_name 	= isset($_POST['isoman_root_name'])?$_POST['isoman_root_name']:'';
	$isoman_dir_root 	= vnSessionGetVar("isoman_dir_root");
	$isoman_type 		= isset($_POST['isoman_type'])?$_POST['isoman_type']:'';
	
	if(_isoman_ftp==0){
		if(!@is_writable($isoman_dir_root)){
			echo('_NOT_VALID_WRITE');die(); 
		}
		else{
			if($isoman_type==0){
				if ($isoman_root_name=='') {
					mkdir($dir.'/'.$folder_name);
					chmod($dir.'/'.$folder_name, 0777);    
				}
				else{
					rename($dir.'/'.$isoman_root_name, $dir.'/'.$folder_name);
				}
			}
			else{
				rename($dir.'/'.$isoman_root_name, $dir.'/'.$folder_name); 
			}
		}
	}
	else{
		if($isoman_type==0){
			if ($isoman_root_name=='') {
				$conn_id = ftp_connect(_isoman_ftp_host_info);
				$login_result = ftp_login($conn_id, _isoman_ftp_usr_info, _isoman_ftp_pwd_info);
				#
				$dir = str_replace($isoman_dir_root,'',$dir);
				ftp_mkdir($conn_id,$dir.'/'.$folder_name);
				ftp_chmod($conn_id,0777,$dir.'/'.$folder_name);    
			}
			else{
				$conn_id = ftp_connect(_isoman_ftp_host_info);
				$login_result = ftp_login($conn_id, _isoman_ftp_usr_info, _isoman_ftp_pwd_info);
				#
				$dir = str_replace($isoman_dir_root,'',$dir);
				ftp_rename($conn_id,$dir.'/'.$isoman_root_name, $dir.'/'.$folder_name);
			}
		}
		else{
			$conn_id = ftp_connect(_isoman_ftp_host_info);
			$login_result = ftp_login($conn_id, _isoman_ftp_usr_info, _isoman_ftp_pwd_info);
			#
			$dir = str_replace($isoman_dir_root,'',$dir);
			ftp_rename($conn_id,$dir.'/'.$isoman_root_name, $dir.'/'.$folder_name); 
		}
	}	
	#
	echo $html; die();
}
function default_isoman_paste(){
	global $dbconn, $_LANG_ID, $core;
	#
	$isoman_dir_root = vnSessionGetVar("isoman_dir_root");
	$dir = isset($_POST['isoman_dir'])?$_POST['isoman_dir']:'';
	$paste_isoman_dir = isset($_POST['paste_isoman_dir'])?$_POST['paste_isoman_dir']:'';
	$isoman_paste_type = isset($_POST['isoman_paste_type'])?$_POST['isoman_paste_type']:'';
	$action = isset($_POST['isoman_paste_action'])?$_POST['isoman_paste_action']:'';
	#
	$html = '';
	if(isset($paste_isoman_dir) && !empty($paste_isoman_dir)) {
		$paste_isoman_dir = rtrim($paste_isoman_dir,'|');
		$paste_isoman_dir = explode('|',$paste_isoman_dir);
		#
		for($i=0;$i<count($paste_isoman_dir);$i++) {
			$tmp = explode('/',$paste_isoman_dir[$i]);
			$folder_name = $tmp[count($tmp)-1];
			#
			if(_isoman_ftp==0){
				if(!@is_writable($isoman_dir_root)){
					echo('_NOT_VALID_WRITE');die(); 
				} else {
					if($isoman_paste_type==0){
						if (is_dir($dir.'/'.$folder_name)) {
						} else {
							if($action=='copy'){
								if($paste_isoman_dir[$i]!=$dir.'/'.$folder_name){
									mkdir($dir.'/'.$folder_name); 
									recurse_copy($paste_isoman_dir[$i], $dir.'/'.$folder_name);
									chmod_r($dir.'/'.$folder_name, 0777);
								}
							}
							else{
								if($paste_isoman_dir[$i]!=$dir.'/'.$folder_name){
									mkdir($dir.'/'.$folder_name); 
									recurse_copy($paste_isoman_dir[$i], $dir.'/'.$folder_name);
									chmod_r($dir.'/'.$folder_name, 0777);
									rmdir_recurse($paste_isoman_dir[$i]);
								}
							}
						}
					} else {
						if($action=='copy'){
							if($paste_isoman_dir[$i]!=$dir.'/'.$folder_name){
								copy($paste_isoman_dir[$i], $dir.'/'.$folder_name);
								chmod($dir.'/'.$folder_name, 0777);
							}
							else{
								$tmpname = explode('.',$folder_name);
								$extension = $tmpname[count($tmpname)-1];
								$folder_namecopy = str_replace('.'.$extension,'',$folder_name).'(1).'.$extension;
								copy($paste_isoman_dir[$i], $dir.'/'.$folder_namecopy);
								chmod($dir.'/'.$folder_name, 0777);
							}
						} 
						else{
							if($paste_isoman_dir[$i]!=$dir.'/'.$folder_name){
								copy($paste_isoman_dir[$i], $dir.'/'.$folder_name);
								chmod($dir.'/'.$folder_name, 0777);
								unlink($paste_isoman_dir[$i]);
							}
						}
					}
				}
			} else {
				$conn_id = ftp_connect(_isoman_ftp_host_info);
				$login_result = ftp_login($conn_id, _isoman_ftp_usr_info, _isoman_ftp_pwd_info);
				$dir = str_replace($isoman_dir_root,'',$dir); 
				$dir = str_replace('//','','/'.$dir);
				@ftp_chdir($conn_id,$dir);
				if($isoman_paste_type==0){
					if (is_dir($dir.'/'.$folder_name)) {	
					} else {
						if($action=='copy'){
							if($paste_isoman_dir[$i]!=$dir.'/'.$folder_name){}else{}
						} else {
							if($paste_isoman_dir[$i]!=$dir.'/'.$folder_name){}
						}
					}
				} else {
					if($action=='copy'){
						if($paste_isoman_dir[$i]!=$dir.'/'.$folder_name){
							ftp_put($conn_id, $folder_name,$paste_isoman_dir[$i], FTP_BINARY);
						} else {
							$tmpname = explode('.',$folder_name);
							$extension = $tmpname[count($tmpname)-1];
							$folder_namecopy = str_replace('.'.$extension,'',$folder_name).'(1).'.$extension;
							ftp_put($conn_id, $folder_namecopy,$paste_isoman_dir[$i], FTP_BINARY);
						}
					} else {
						if($paste_isoman_dir[$i]!=$dir.'/'.$folder_name){
							ftp_put($conn_id, $folder_name,$paste_isoman_dir[$i], FTP_BINARY);
							$paste_dir = str_replace($isoman_dir_root,'',$paste_isoman_dir[$i]); 
							ftp_delete($conn_id, $paste_dir);
						}
					}
				}
				#
				ftp_close($conn_id);
			}
		}
	}

	echo $html; die();
}
function is_ftp_dir($itempath) {
    if ($itempath[0]!=="/") {
        $itempath = "/".$itempath;
    }

    $conn_id = ftp_connect(_isoman_ftp_host_info);
	$login_result = ftp_login($conn_id, _isoman_ftp_usr_info, _isoman_ftp_pwd_info);

    $contents = ftp_nlist($conn_id, "$itempath");

    if (!empty($contents[0])) {
        $check = str_replace($itempath, "", $contents[0]);
        if (!empty($check)) {
            return true;
        }
    }
    ftp_close($conn_id);
}
function rmdir_recurse($path) {
	$path = rtrim($path, '/').'/';
	$handle = opendir($path);
	while(false !== ($file = readdir($handle))) {
		if($file != '.' and $file != '..' ) {
			$fullpath = $path.$file;
			if(is_dir($fullpath)) rmdir_recurse($fullpath); else unlink($fullpath);
		}
	}
	closedir($handle);
	rmdir($path);
}
function recurse_copy($src,$dst) {
    $dir = opendir($src);
    mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
}
function chmod_r($path, $filemode) {
    $dp = opendir($path);
    while($file = readdir($dp)) {
		if($file != "." AND $file != "..") {
			if(is_dir($file)){
				chmod($file, $filemode);
			}else{
				chmod($path."/".$file, $filemode);
			}
			chmod($path, $filemode);
			chmod_r($path."/".$file, $filemode);
		}
	}
	closedir($dp);
}
function recove_images($path, $w, $h) {
	global $core, $clsISO;
    if(!empty($path)) {
		return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($path);
	}
}
?>