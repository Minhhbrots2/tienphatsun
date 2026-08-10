<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
class Transaction extends dbBasic{
	function __construct(){
		global $_LANG_ID;
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."transactions";
	}
	function genCode(){
		global $core, $clsISO;
		$billing_id = $this->getMaxId();
		if($billing_id < 10) return sprintf('XNDT-0000%s',$billing_id);
		if($billing_id >= 10 && $billing_id<100) return sprintf('XNDT-000%s',$billing_id);
		if($billing_id >= 100 && $billing_id<1000) return sprintf('XNDT-00%s',$billing_id);
		if($billing_id >= 1000 && $billing_id<10000) return sprintf('XNDT-0%s',$billing_id);
		if($billing_id >= 10000 && $billing_id<100000) return sprintf('XNDT-%s',$billing_id);
		return sprintf('GD%s',$billing_id);
	}
	function get_row_billing($uid, $transaction_id, $billing_id, $oneBilling=array()){
		global $core, $dbconn, $clsISO, $profile_id;
		$clsBilling = new Billing();
		$clsProject = new Project();
		if(empty($oneBilling)){
			$oneBilling = $clsBilling->getOne($billing_id);
		}
		$project_id = $oneBilling['project_id'];
		$contract_date = $oneBilling['contract_date'];
		return '<tr class="'.$uid.' transaction_item_'.$transaction_id.'">
			<td class="p-0 text-center">1</td>
			<td class="p-0 w-px-125">
				<input type="hidden" class="billing_id" name="billing_store['.$uid.'][billing_id]" value="'.$oneBilling.billing_id.'" /> 
				<input type="hidden" class="billing_code" name="billing_store['.$uid.'][billing_code]" value="'.$oneBilling.billing_code.'" /> 
				<select prompt="Giao dịch" class="cboProduct" uid="'.$uid.'" id="cboProduct_'.$uid.'" data-options="panelWidth:900,
					url:\'/index.php?mod=transaction&act=search_billing&uid='.$uid.'&staff_id='.$profile_id.'\',
					mode:\'remote\',
					method:\'post\',
					height:\'34px\',
					width:\'130px\',
					multiple:false, 
					value: \''.$oneBilling['billing_code'].'\',
					columns: [[
						{field:\'billing_code\',title:\'Mã GD\',width:70,align:\'left\'},
						{field:\'deposit_date\',title:\'Ngày cọc\',width:85,align:\'left\'},
						{field:\'staff_name\',title:\'Nhân viên\',width:120,align:\'left\'},
						{field:\'stock_code\',title:\'Mã căn\',width:80},
						{field:\'project_name\',title:\'Dự án\',width:120,align:\'left\'},
						{field:\'totalgrand\',title:\'Tồng tiền\',width:80,align:\'left\'}
					]],
					idField:\'billing_code\',
					textField:\'billing_code\',
					fitColumns:true,
					showFooter:true,
					pagination:true,
					pageSize: 20">
				</select>
			</td>
			<td class="p-0"><input type="text" class="form-control no-focus stock_code" name="billing_store['.$uid.'][stock_code]" 
				placeholder="Mã căn" value="'.$oneBilling['stock_code'].'" /></td>
			<td class="p-0"><input type="text" class="form-control no-focus project_name" name="billing_store['.$uid.'][project_name]" 
				value="'.$clsProject->getCode($project_id).'" placeholder="Dự án" /></td>
			<td class="p-0"><input type="date" placeholder="dd/mm/yy" name="billing_store['.$uid.'][contract_date]" 
				class="form-control no-focus contract_date" value="'.(!empty($contract_date) ? date('Y-m-d', $contract_date) : "").'" /></td>
			<td class="p-0"><input type="text" class="form-control no-focus numberonly price-In total_price" placeholder="0.00đ" 
				name="billing_store['.$uid.'][total_price]" value="'.$oneBilling['totalgrand'].'" /></td>
			<td class="p-0"><input type="text" class="form-control no-focus numberonly commission" name="billing_store['.$uid.'][commission]" 
				placeholder="0%" value="" /></td>
			<td class="p-0"><input type="text" class="form-control no-focus numberonly price-In price_ms" placeholder="0.00đ" 
				name="billing_store['.$uid.'][price_ms]" /></td>
			<td class="p-0"><input type="text" class="form-control no-focus numberonly price-In price_ns" placeholder="0.00đ" 
				name="billing_store['.$uid.'][price_ns]" /></td>
			<td class="p-0"><input type="text" class="form-control no-focus numberonly price-In price_ms" placeholder="0.00đ" 
				name="billing_store['.$uid.'][price_ms]" /></td>
			<td class="p-0"><input type="text" placeholder="Viết ghi chú..." class="form-control no-focus notes" 
				name="billing_store['.$uid.'][notes]" /></td>
			<td class="text-center"></td>
		</tr>';
	}
}
?>