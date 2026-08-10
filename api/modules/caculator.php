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
$app->post('/loan_calculator', function ($request, $response, $args) use ($app) {
	global $clsISO, $dbconn, $_LANG_ID;
	$apiresults = array(
		'error' => 1, 
		'result' => 'error', 
		'body' => "Error"
	);
	$inputs = $request->getParsedBody();
	$total_price = isset($inputs['total_price']) ? $inputs['total_price'] : 0;
	$loan_rate = isset($inputs['loan_rate']) ? $inputs['loan_rate'] : 0;
	$total_months = isset($inputs['total_months']) ? (int) $inputs['total_months'] : 0;
	$promo_months = isset($inputs['promo_months']) ? (int) $inputs['promo_months'] : 0;
	$promo_interest = isset($inputs['promo_interest']) ? $inputs['promo_interest'] : 0;
	$after_interest = isset($inputs['after_interest']) ? $inputs['after_interest'] : 0;
	$method = isset($inputs['method']) ? $inputs['method'] : 'equal_principal'; // annuity
	// Ân hạn gốc
	$grace_months = isset($inputs['grace_months']) ? (int) $inputs['grace_months'] : 0;
	// HTLS
	$interest_support_rate = isset($inputs['interest_support_rate']) ? (int) $inputs['interest_support_rate'] : 100;
	$interest_support_months = isset($inputs['interest_support_months']) ? (int) $inputs['interest_support_months'] : 0;
	// Tất toán trước hạn
	$early_payment_fee_rate = isset($inputs['early_payment_fee_rate']) ? $inputs['early_payment_fee_rate'] : 0;
	$settle_period = isset($inputs['settle_period']) ? (int) $inputs['settle_period'] : 0;
	$settle_period_unit = isset($inputs['settle_period_unit']) ? $inputs['settle_period_unit'] : '_MONTH';
	if($settle_period_unit == '_YEAR') $settle_period *= 12;
	$helper = new Helper();
	$_results = $helper->loan_calculate(array(
		'total_price' => $clsISO->convertToNumber($total_price)*1000000000,
		'loan_rate' => $clsISO->convertToNumber($loan_rate),
		'total_months' => $total_months,
		'promo_months' => $promo_months,
		'promo_interest' => $clsISO->convertToNumber($promo_interest),
		'after_interest' => $clsISO->convertToNumber($after_interest),
		'method' => $method,
		// Ân hạn gốc
		'grace_months' => $grace_months,
		// HTLS
		'interest_support_rate' => $interest_support_rate,
		'interest_support_months' => $interest_support_months,
		// Tất toán trước hạn
		'early_payment_fee_rate' => $early_payment_fee_rate,
		'settle_month' => $settle_period
	));
	$apiresults = array(
		'error' => 0, 
		'result' => 'success', 
		'body' => $_results
	);
	// Return
	echo echoResponse(200, $apiresults);
});