<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 Future Group.        	  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
function calculator_open(){
	global $smarty,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO
	,$profile_id,$oneProfile,$deviceType,$clsConfiguration;
	$clsStock = new Stock();
	$clsProperty = new Property();
	$uid = $clsISO->getUniqid();
	$stock_id = (int) Input::post('stock_id', 0);
	$def_configs = array('id' => 0, 'introRate' => 0, 'introMonths' => 0, 'year' => 25);
	if($stock_id > 0){
		$field = "`t1`.`ms_code`,`t1`.`block_id`,`t1`.`stock_type`,`t1`.`more_information`,`t2`.`more_information` as `block_information`";
		$oneStock = $dbconn->getRow("SELECT {$field} FROM `{$clsStock->tbl}` AS `t1` 
			INNER JOIN `{$clsProperty->tbl}` AS `t2` ON t1.`block_id`=`t2`.`property_id` 
			WHERE `t1`.`stock_id`='{$stock_id}'");
		$block_id = $oneStock['block_id'];
		$stock_type = $oneStock['stock_type'];
		$more_information = $oneStock['more_information'];
		$block_information = $oneStock['block_information'];
		$more_information = $clsISO->to_array_json($more_information);
		$block_information = $clsISO->to_array_json($block_information);
		// $oneStock['more_information'] = $more_information;
		$arr_field_configs = $list_banks = $list_banks_dbs = array();
		if($stock_type == _BLOCK_TYPE_HIGHLEVEL_SALE){
			$arr_field_configs = $core->get_field($block_information, "price_field_configs", []);
			$ratio_arrs = $price_arrs = array();
			if(!empty($arr_field_configs)){
				foreach($arr_field_configs as $field => $val){
					if($field == 'total_price_bank') $ratio = 80;
					if($field == 'total_price_bank_half') $ratio = 50;
					if(isset($val['status']) && (int) $val['status'] == 1 
						&& isset($more_information[$field]) && !empty($more_information[$field])){
						if(in_array($field, ['total_price_early', 'total_price_progress'])){
							unset($arr_field_configs[$field]);
						} else {
							$price = $clsISO->processSmartNumber($more_information[$field]);
							$arr_field_configs[$field]['price'] = round($price/1000000000,2);
							$arr_field_configs[$field]['ratio'] = $ratio;
							$ratio_arrs[] = $ratio;
							$price_arrs[$ratio] = $price;
						}
					} else {
						unset($arr_field_configs[$field]);
					}
				}
			}
		}
		if(!empty($price_arrs) && !empty($ratio_arrs)){
			@sort($ratio_arrs);
			$min = @reset($ratio_arrs);
			$def_configs['ratio'] = $min;
			$total_price_vat = $price_arrs[$def_configs['ratio']];
		} else {
			$def_configs['ratio'] = 80;
			$total_price_vat = $more_information['total_price_vat'];
			$total_price_early = $more_information['total_price_early'];
			if(!empty($total_price_early) && empty($total_price_vat)){
				$total_price_vat = $total_price_early;
			}
			$total_price_vat = $clsISO->processSmartNumber($total_price_vat);
		}
	} else {
		$total_price_vat = 5000000000;
		$def_configs['ratio'] = 80;
		$def_configs['year'] = 25;
	}
	$total_price_vat = round($total_price_vat/1000000000, 2);
	$total_price_ratio = ($total_price_vat*$def_configs['ratio'])/100;
	###
	$loan_interest = $clsConfiguration->getValue('loan_interest');
	$loan_interest = $clsISO->to_array_json($loan_interest);
	foreach ($loan_interest as $key => $value) {
		$preferentialTime = $value['introMonths'];
		$preferentialRate = $value['introRate'];
		$list_banks[] = array(
			'id' 							=> $key,
			'name' 							=> $value['bank_name'],
			'preferentialTime' 				=> $preferentialTime,
			'preferentialRate' 				=> $preferentialRate,
			'introMonthsUnit' 				=> $value['introMonthsUnit'],
			'rate' 							=> $value['rate'],
		);
		if($clsISO->checkContainer($value['bank_name'], 'TMCP Kỹ thương', "")){
			$def_configs['id'] 							= $key;
			$def_configs['introRate'] 					= $preferentialRate;
			$def_configs['introMonths'] 				= $preferentialTime;
			$def_configs['introMonthsUnit'] 			= $value['introMonthsUnit'];				
			$def_configs['rate'] 						= $value['rate'];
		}
	}
	###
	$col_w1 = ($deviceType == 'phone') ? '60' : '70'; 
	$col_w2 = ($deviceType == 'phone') ? '40' : '30'; 
	#
	$smarty->assign('uid', $uid);
	$smarty->assign('col_w1', $col_w1);
	$smarty->assign('col_w2', $col_w2);
	$smarty->assign('stock_id', $stock_id);
	$smarty->assign('oneStock', $oneStock);
	$smarty->assign('total_price_vat', $total_price_vat);
	$smarty->assign('total_price_ratio', $total_price_ratio);
	$smarty->assign('arr_field_configs', $arr_field_configs);
	$smarty->assign('list_banks', $list_banks);
	$smarty->assign('def_configs', $def_configs);
	// Return
	$html = $core->build('calculator'.DS.'_ajax.open.tpl'); 
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}
function loan_calculate(array $opt){
	global $core, $clsISO;
    // ==========================
    // Input
    // ==========================
    $total_price   = $opt['total_price'];
    $loan_rate     = $opt['loan_rate']; 
    $loan_amount   = $total_price * $loan_rate / 100;
    $total_months  = $opt['total_months'];
    // Ưu đãi
    $promo_months  = intval($opt['promo_months']);
    $promo_rate    = floatval($opt['promo_interest']) / 12 / 100;
    $after_rate    = floatval($opt['after_interest']) / 12 / 100;
    if ($promo_months <= 0 || $promo_rate <= 0) {
        $promo_months = 0;
        $promo_rate   = 0;
    }
    $method        = $opt['method']; 
    $grace_months  = $opt['grace_months'];
    // Hỗ trợ lãi suất
    $support_rate   = $opt['interest_support_rate'] / 100; 
    $support_months = $opt['interest_support_months'];
    // Tất toán sớm
    $early_fee_rate = $opt['early_payment_fee_rate'] / 100; 
    $settle_month   = $opt['settle_month']; 
    // ==========================
    // Khởi tạo
    // ==========================
    $remaining = $loan_amount;
    $first_payment = 0;
    $interest_promo   = 0;
    $principal_promo  = 0;
    $interest_after   = 0;
    $principal_after  = 0;
    $monthly_promo = 0;
    $monthly_after = 0;
    $down_payment = $total_price - $loan_amount;
    $schedule = [];
    // ==========================
    // Gốc cố định
    // ==========================
    $equal_principal = $loan_amount / $total_months;
    // ==========================
    // Tính tiền annuity ưu đãi
    // ==========================
    if ($method === "annuity" && $promo_months > 0) {
        if ($promo_rate > 0) {
            $monthly_promo = $loan_amount *
                ( $promo_rate * pow(1 + $promo_rate, $total_months) ) /
                ( pow(1 + $promo_rate, $total_months) - 1 );
        } else {
            $monthly_promo = $loan_amount / $total_months;
        }
    }
    // ==========================
    // Vòng ưu đãi
    // ==========================
    for ($m = 1; $m <= $promo_months; $m++) {
        if ($settle_month > 0 && $m > $settle_month) break;
        $begin_balance = $remaining;
        $interest = $remaining * $promo_rate;
        if ($m <= $support_months) {
            $interest *= (1 - $support_rate);
        }
        if ($m <= $grace_months) {
            $principal = 0;
            $payment   = $interest;
        } else {
            if ($method === "equal_principal") {
                $principal = $equal_principal;
                $payment   = $principal + $interest;
            } else {
                $principal = $monthly_promo - $interest;
                $payment   = $monthly_promo;
            }
        }
        $remaining -= $principal;
        if ($m == 1) $first_payment = $payment;
        $interest_promo  += $interest;
        $principal_promo += $principal;
        $schedule[] = [
            "month"          => $m,
            "begin_balance"  => $begin_balance,
            "interest"       => $interest,
            "principal"      => $principal,
            "payment"        => $payment,
            "remaining"      => max($remaining, 0),
			'is_promo' 		 => 1
        ];
    }
    // Nếu tất toán trong giai đoạn ưu đãi
    if ($settle_month > 0 && $settle_month <= $promo_months) {
        $early_fee_value = $remaining * $early_fee_rate;
        return [
            "method"         => $method,
            "loan_amount"    => $loan_amount,
            "down_payment"   => $down_payment,
            "first_payment"  => $first_payment,
            "monthly_promo"  => $monthly_promo,
            "monthly_after"  => 0,
            "interest_promo"  => $interest_promo,
            "principal_promo" => $principal_promo,
            "total_interest"  => $interest_promo,
            "total_principal" => $principal_promo,
            "total_payment"   => $interest_promo + $principal_promo + $early_fee_value,
            "remaining"        => max($remaining, 0),
            "early_fee_value"  => $early_fee_value,
            "schedule"         => $schedule
        ];
    }
    // ==========================
    // Giai đoạn sau ưu đãi
    // ==========================
    $after_months = $total_months - $promo_months;
    if ($method === "annuity") {
        if ($after_rate > 0) {
            $monthly_after = $remaining *
                ( $after_rate * pow(1 + $after_rate, $after_months) ) /
                ( pow(1 + $after_rate, $after_months) - 1 );
        } else {
            $monthly_after = $remaining / $after_months;
        }
    }
    for ($i = 1; $i <= $after_months; $i++) {
        $current_month = $promo_months + $i;
        if ($settle_month > 0 && $current_month > $settle_month) break;
        $begin_balance = $remaining;
        $interest = $remaining * $after_rate;
        if ($method === "equal_principal") {
            $principal = $equal_principal;
            $payment   = $principal + $interest;
        } else {
            $principal = $monthly_after - $interest;
            $payment   = $monthly_after;
        }
        $remaining -= $principal;
        if ($current_month == 1) $first_payment = $payment;
        $interest_after  += $interest;
        $principal_after += $principal;
        $schedule[] = [
            "month"          => $current_month,
            "begin_balance"  => $begin_balance,
            "interest"       => $interest,
            "principal"      => $principal,
            "payment"        => $payment,
            "remaining"      => max($remaining, 0),
			'is_promo' 		 => 0
        ];
    }
    // ==========================
    // Tất toán sau ưu đãi (ĐÃ BỔ SUNG ĐÚNG YÊU CẦU)
    // ==========================
    $early_fee_value = 0;
    // ==========================
	// Tất toán sau ưu đãi (ĐÃ FIX)
	// ==========================
	if ($settle_month > 0) {
		// Lãi của tháng tất toán
		$interest_last = $remaining * $after_rate;
		// Phí phạt
		$early_fee_value = $remaining * $early_fee_rate;
		// Tổng tiền phải trả = gốc còn lại + lãi tháng đó + phí phạt
		$payment = $remaining + $interest_last + $early_fee_value;
		// Ghi vào lịch: tháng tất toán
		$schedule[] = [
			"month"          => $settle_month,
			"begin_balance"  => $remaining,
			"interest"       => $interest_last,
			"principal"      => $remaining,
			"payment"        => $payment,
			"remaining"      => 0,
			'is_promo' 		 => 0,
			"early_fee"      => $early_fee_value
		];
		// Cộng lãi cuối vào tổng lãi after
		$interest_after += $interest_last;
		// **RẤT QUAN TRỌNG**: cộng phần gốc tất toán vào tổng principal_after
		$principal_after += $remaining;
		// Set dư nợ = 0 vì đã tất toán
		$remaining = 0;
	}
	// Tổng thanh toán từng giai đoạn (gốc + lãi)
    $total_payment_promo = $principal_promo + $interest_promo;
    $total_payment_after = $principal_after + $interest_after;
    // ==========================
    // Output
    // ==========================
    return [
        "method"           => $method,
        "loan_amount"      => $loan_amount,
        "down_payment"     => $down_payment,
        "first_payment"    => $first_payment,
        "monthly_promo"    => $monthly_promo,
        "monthly_after"    => $monthly_after,
        "interest_promo"   => $interest_promo,
        "principal_promo"  => $principal_promo,
        "interest_after"   => $interest_after,
        "principal_after"  => $principal_after,
		'total_payment_promo' => $total_payment_promo,
		'total_payment_after' => $total_payment_after,
        "total_interest"   => $interest_promo + $interest_after,
        "total_principal"  => $principal_promo + $principal_after,
        "total_payment"    => $interest_promo + $interest_after + $principal_promo + $principal_after + $early_fee_value,
        "remaining"        => max($remaining, 0),
        "early_fee_value"  => $early_fee_value,
        "schedule"         => $schedule
    ];
}
function calculator_do_calculator(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$uid = Input::post('uid');
	$price = Input::post('price', 0);
	$ratio = (int) Input::post('ratio', 0);
	$year = (int) Input::post('year', 1);
	$rate = Input::post('rate', 0);
	$introRate = Input::post('introRate', 0);
	$paymentMethod = (int) Input::post('paymentMethod',1);
	$method = ($paymentMethod == 1) ? 'equal_principal' : 'annuity';
	$introMonths = (int) Input::post('introMonths', 0);
	$introMonthsUnit = Input::post('introMonthsUnit', '_YEAR');
	if($introMonthsUnit == '_YEAR') $introMonths *= 12;
	$gracePeriod = (int) Input::post('gracePeriod', 0);
	$gracePeriodUnit = Input::post('gracePeriod', '_MONTH');
	if($gracePeriodUnit == '_YEAR') $gracePeriod *= 12;
	$interestRateSupportPeriod = (int) Input::post('interestRateSupportPeriod', 0);
	$interestRateSupportPeriodUnit = Input::post('interestRateSupportPeriodUnit', '_MONTH');
	if($interestRateSupportPeriodUnit == '_YEAR') $interestRateSupportPeriod *= 12;
	$interestRateSupportPeriodSuffix = ($interestRateSupportPeriodUnit == '_MONTH') ? 'Tháng' : 'Năm';
	$early_payment_fee_rate = Input::post('early_payment_fee_rate', 0);
	$settle_period = Input::post('settle_period', 0);
	$settle_period_unit = Input::post('settle_period_unit', '_MONTH');
	if($settle_period_unit == '_YEAR') $settle_period *= 12;
	###
	$_results = array();
	$_results = loan_calculate(array(
		'total_price' => $clsISO->convertToNumber($price)*1000000000,
		'loan_rate' => $clsISO->convertToNumber($ratio),
		'total_months' => $year * 12,
		'promo_months' => $introMonths,
		'promo_interest' => $clsISO->convertToNumber($introRate),
		'after_interest' => $clsISO->convertToNumber($rate),
		'method' => $method,
		// Ân hạn gốc
		'grace_months' => $gracePeriod,
		// HTLS
		'interest_support_rate' => 100,
		'interest_support_months' => $interestRateSupportPeriod,
		// Tất toán trước hạn
		'early_payment_fee_rate' => $early_payment_fee_rate,
		'settle_month' => $settle_period
	));
	// $clsISO->print_pre($_results); die();
	$html_MonthlyPayment = "";
	if(isset($_results['schedule']) && !empty($_results['schedule'])){
		foreach($_results['schedule'] as $key => $val){
			$html_MonthlyPayment.= '<tr'.($val['is_promo']?' class="promo"':'').'>
				<td class="text-center">'.$val['month'].'</td>
				<td class="text-center">'.$clsISO->formatPrice($val['begin_balance']).'</td>
				<td>'.$clsISO->formatPrice($val['principal']).'</td>
				<td>'.$clsISO->formatPrice($val['interest']).'</td>
				<td>'.$clsISO->formatPrice($val['payment']).'</td>
				<td>'.$clsISO->formatPrice($val['remaining']).'</td>
			</tr>';
		}
	}
	// Return
	echo json_encode(array_merge($_results, array(
		'uid' => $uid,
		'ratio' => $ratio, 
		'year' => $year, 
		'rate' => $rate,
		'html_MonthlyPayment' => $html_MonthlyPayment
	))); die();
}
function calculator_view(){
	global $assign_list,$_CONFIG,$core,$dbconn,$mod,$act,$_LANG_ID,$extLang,$clsISO,$profile_id,$oneProfile;
	$uid = $clsISO->getUniqid();
	$html_MonthlyPayment = Input::post('html_MonthlyPayment');
	$html = '<div class="modal-dialog modal-ipad">
		<div class="modal-content" id="frmIssue" enctype="multipart/form-data">
			<div class="modal-header">
				<h5 class="modal-title">Chi tiết khoản vay theo từng kỳ</h5>
				<div class="d-none d-lg-block">
					<a onClick="$Core.calculator.export2excel(this, event)" toId="'.$uid.'" class="btn btn-sm btn-outline-default">
						<i class=\'bx bx-export\'></i> Xuất Excel
					</a>
				</div>
				<button type="button" class="btn-close closeEv" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="overflow-auto webkit-scrollbar rounded-1 tableFixHead">
					<table id="table_'.$uid.'" cellspacing="0" cellpadding="0" class="table table-striped" width="100%">
						<thead><tr>
							<th widtd="5%" class="align-center text-center bg-grayter h-px-40">Kỳ</th>
							<th class="align-center h-px-40 bg-grayter">Dư nợ</th>
							<th class="align-center h-px-40 bg-grayter">Gốc phải trả</th>
							<th class="align-center h-px-40 bg-grayter">Lãi phải trả</th>
							<th class="align-center h-px-40 bg-grayter">Gốc + Lãi</th>
							<th class="align-center h-px-40 bg-grayter">Dư nợ cuối kỳ</th>
						</tr></thead>
						'.html_entity_decode($html_MonthlyPayment).'
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<div class="d-flex w-100 align-items-center justify-content-between mb-2 mb-lg-0">
					<div class="d-flex gap-2 align-items-center">
						<div class="d-flex gap-1 align-items-center">
							<span class="d-block w-px-15 h-px-15 rounded-pill border bg-promo"></span> Ưu đãi
						</div>
						<div class="d-flex gap-1 align-items-center">
							<span class="d-block w-px-15 h-px-15 rounded-pill border bg-white"></span> Không ưu đãi
						</div>
					</div>
					<small class="text-muted text-right">Đơn vị tính: VNĐ</small>
				</div>
				<div class="d-lg-none w-100">
					<a onClick="$Core.calculator.export2excel(this, event)" class="btn btn-block btn-sm btn-outline-default" 
						toId="'.$uid.'"><i class=\'bx bx-export\'></i> Xuất Excel</a>
				</div>
			</div>
		</div>
	</div>
	<style type="text/css">
		.tableFixHead{
			position:relative;
			max-height:calc(100vh - 160px);
		}
		.tableFixHead thead th{
			position:sticky;
			top: 0; left:0;
		}
		.bg-promo,
		.tableFixHead tr.promo td{
			background:rgb(225 255 227);
			box-shadow:inset 0 0 0 9999px rgb(225 255 227);
		}
	</style>';
	// Return
	echo json_encode(array(
		'uid' => $uid,
		'html' => $html
	)); die();
}