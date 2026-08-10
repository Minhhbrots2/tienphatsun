<?php
// Thi đua định danh — quản lý chương trình (lưu Configuration qua model Competition).
function default_default(){
	global $assign_list, $mod, $core, $clsModule, $clsISO;
	$assign_list['clsModule'] = $clsModule;
	$clsComp = new Competition();
	$assign_list['clsComp'] = $clsComp;
	$assign_list['clsProperty'] = new Property();
	$assign_list['programs'] = $clsComp->getPrograms();
}

function default_edit(){
	global $assign_list, $mod, $core, $clsModule, $clsISO;
	$assign_list['clsModule'] = $clsModule;
	$clsComp = new Competition();
	$assign_list['clsComp'] = $clsComp;
	$assign_list['clsProperty'] = new Property();
	$id = (int) Input::get('id', 0);
	$program = ($id > 0) ? $clsComp->getProgram($id) : array();
	if(empty($program)){
		// Thêm mới: seed thang điểm + hạng mặc định (theo bảng "Thang điểm thi đua định danh 2026")
		$program = array(
			'id' => 0, 'name' => '', 'status' => 1, 'order_no' => 0,
			'color' => '#c0392b', 'color2' => '#7b1f13', 'gradient_angle' => 135, 'gradient_animate' => 0, 'icon' => 'bx-trophy', 'visibility' => 'all',
			'period_type' => 'year',
			'start_date' => 0, 'end_date' => 0, 'department_ids' => array(), 'include_children' => 1,
			'value_field' => 'totalgrand', 'revenue_condition' => 'both',
			'tiers' => array(
				array('from'=>0,  'to'=>5,  'exclusive'=>5,   'cross'=>2.5, 'note'=>''),
				array('from'=>5,  'to'=>10, 'exclusive'=>7.5, 'cross'=>3.5, 'note'=>''),
				array('from'=>10, 'to'=>20, 'exclusive'=>10,  'cross'=>5,   'note'=>''),
				array('from'=>20, 'to'=>35, 'exclusive'=>15,  'cross'=>7.5, 'note'=>''),
				array('from'=>35, 'to'=>50, 'exclusive'=>20,  'cross'=>12.5,'note'=>''),
				array('from'=>50, 'to'=>0,  'exclusive'=>30,  'cross'=>20,  'note'=>'Trở lên'),
			),
			'ranks' => array(
				array('from'=>50,  'name'=>'Profession', 'reward'=>'Chuyến đi Đông Nam Á'),
				array('from'=>100, 'name'=>'Elite',      'reward'=>'Chuyến đi Châu Á'),
				array('from'=>200, 'name'=>'Legend',     'reward'=>'Chuyến đi Châu Âu'),
			),
		);
	}
	$assign_list['program'] = $program;
}

function default_save(){
	global $mod, $clsISO;
	if(!isset($_POST['submit'])){
		header('location:'.PCMS_URL.'/?mod='.$mod); exit();
	}
	$clsComp = new Competition();
	$post = $_POST;
	$post['start_date'] = (!empty($post['start_date_txt'])) ? $clsISO->toTime($post['start_date_txt']) : 0;
	$post['end_date']   = (!empty($post['end_date_txt']))   ? $clsISO->toTime($post['end_date_txt'])   : 0;
	$data = $clsComp->sanitize($post);
	$period = $clsComp->resolvePeriod($data['period_type'], $data['start_date'], $data['end_date']);
	$data['start_date'] = $period[0];
	$data['end_date'] = $period[1];
	if($data['name'] === ''){
		header('location:'.PCMS_URL.'/?mod='.$mod.'&act=edit'.($data['id'] > 0 ? '&id='.$data['id'] : '').'&message=errName');
		exit();
	}
	$clsComp->saveProgram($data);
	header('location:'.PCMS_URL.'/?mod='.$mod.'&message=saveSuccess');
	exit();
}

function default_delete(){
	global $mod;
	$id = (int) Input::get('id', 0);
	if($id > 0){
		$clsComp = new Competition();
		$clsComp->deleteProgram($id);
	}
	header('location:'.PCMS_URL.'/?mod='.$mod.'&message=deleteSuccess');
	exit();
}
?>
