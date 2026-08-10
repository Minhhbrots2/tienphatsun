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
class Order extends dbBasic{
	function __construct(){
		$this->pkey = "order_id";
		$this->tbl = DB_PREFIX."order";
	}
	function getMaxByCond($cond){
		$tmp = $this->getAll("is_trash=0 and {$cond} order by {$this->pkey} DESC", $this->pkey);
		return !empty($tmp) ? intval($tmp[0][$this->pkey])+1 : 1;
	}
	function genCode($number){
		if($number < 10) return '0000'.$number;
		if($number >= 10 && $number<=100 ) return '000'.$number;
		if($number >= 100 && $number<1000 ) return '00'.$number;
		if($number >= 1000 && $number<10000 ) return '0'.$number;
		return $number;
	}
	function genOrderDraftCode($order_id, $draft=1){
		$max_Id = $this->getMaxByCond("is_draft='{$draft}'");
		return '#D'.$max_Id;
	}
	function genOrderDoneCode($order_id, $done=0){
		$max_Id = $this->getMaxByCond("is_done='{$done}'");
		return '#'.$this->genCode($max_Id);
	}
	function getCode($order_id){
		return $this->getOneField('vpc_OrderNo', $order_id);
	}
	function getAdress($order_id, $tp){
		//header('Content-Type: text/html; charset=utf-8');
		$field = $tp.'_address';
		$address_info = $this->getOneField($field, $order_id);
		//$address_information = @html_entity_decode($address_information, ENT_COMPAT, 'UTF-8');
		$address_info = !empty($address_info) 
			? @json_decode($address_info, true) : array();
		//var_dump($address_information); die();
		$html = '';
		if(isset($address_info['fullname']) && !empty($address_info['fullname'])){
			$html .= '<p class="type--subdued m-0">'.$address_info['fullname'].'</p>';
		}
		if(isset($address_info['phone']) && !empty($address_info['phone'])){
			$html .= '<p class="type--subdued m-0">'.$address_info['phone'].'</p>';
		}
		if(isset($address_info['email']) && !empty($address_info['email'])){
			$html .= '<p class="type--subdued m-0">'.$address_info['email'].'</p>';
		}
		if(isset($address_info['address']) && !empty($address_info['address'])){
			$html .= '<p class="type--subdued m-0">'.$address_info['address'].'</p>';
		}
		if(isset($address_info['companyname']) && !empty($address_info['companyname'])){
			$html .= '<p class="type--subdued m-0">'.$address_info['companyname'].'</p>';
		}
		if(isset($address_info['country_id']) && !empty($address_info['country_id'])){
			$clsCountry = new Country();
			$html .= '<p class="type--subdued m-0">'.$clsCountry->getTitle($address_info['country_id']).'</p>';
		}
		if(isset($address_info['city_id']) && !empty($address_info['city_id'])){
			$clsCity = new City();
			$html .= '<p class="type--subdued m-0">'.$clsCity->getTitle($address_info['city_id']).'</p>';
		}
		if(isset($address_info['district_id']) && !empty($address_info['district_id'])){
			$clsDistrict = new District();
			$html .= '<p class="type--subdued m-0">'.$clsDistrict->getTitle($address_info['district_id']).'</p>';
		}
		return $html;
	}
	function getTotalMoney($order_id){
		global $core, $dbconn, $clsISO;
		$total_money = $this->getOneField('vpc_total_money', $order_id);
		return $clsISO->formatPrice($total_money);
	}
	function countOrderByStatus($status){
		return $this->countItem("vpc_status='{$status}'");
	}
	function getCustomer($order_id){
		$oneTable = $this->getOne($order_id,"profile_id,shipping_address");
		$profile_id = $oneTable['profile_id'];
		if($profile_id > 0){
			$clsProfile = new Profile();
			return $clsProfile->getFullName($profile_id);
		} else {
			$shipping_address = $oneTable['shipping_address'];
			$shipping_address = !empty($shipping_address) 
				? @json_decode($shipping_address, true) : array();
			return $shipping_address['fullname'];
		}
	}
	function getVpcMoney($order_id, $format=false){
		global $core, $clsISO;
		$one = $this->getOne($order_id,"vpc_total_money");
		$vpc_total_money = $clsISO->parsePriceDecimal($one['vpc_total_money']);
		if($format)
			return $clsISO->formatPrice($vpc_total_money);
		return $vpc_total_money;
	}
	function getStatus($vpc_status){
		global $clsISO, $core;
		$text = ($vpc_status==1) ? $core->get_Lang('Paid') : $core->get_Lang('UnPaid');
		return '<span class="label label-default">'.$text.'<span>';
	}
	function getVpc_PaymentType($order_id){
		$oneTable = $this->getOne($order_id);
		if($oneTable['vpc_payment_type']=='1'){ return 'Thanh toán tại nhà khi giao hàng';}
		if($oneTable['vpc_payment_type']==2){ return 'Thanh toán tại cửa hàng khi tôi tới nhận hàng';}
		if($oneTable['vpc_payment_type']==3){ return 'Thanh toán qua chuyển khoản ngân hàng';}
	}
	function getFieldValue($order_id, $field){
		global $core,$clsISO;
		$clsCity = new City();
		$clsProfile = new Profile();
		$one = $this->getOne($order_id);
		$profile_id = $one['profile_id'];
		$add_billing = $one['add_billing'];
		$add_shipping = $one['add_shipping'];
		$billing_address = !empty($one['billing_address']) 
			? @json_decode($one['billing_address'], true) : array();
		
		if((int) $add_shipping==0){
			$shipping_address = $billing_address;
		} else {
			$shipping_address = !empty($one['shipping_address']) 
				? @json_decode( $one['shipping_address'], true) : array();
		}
		if(in_array($field, array('fullname','phone','email','address'))){
			return $billing_address[$field];
		}
		else if(in_array($field, array('shipping_fullname','shipping_email','shipping_phone','shipping_address'))){
			if(!$add_shipping){
				return '#SAME';
			} else {
				$tmp = explode('_', $field);
				return $shipping_address[$tmp[1]];
			}
		} 
		return $this->getOneField($field, $order_id);
	}
	function sendEmail($order_id){
		global $smarty,$core,$dbconn,$clsISO,$clsConfiguration,$clsProfile,$clsProduct;
		$clsOrderDetail = new OrderDetail();
		//var_dump($lstOrderItem); die();
		$email_template_id = 8;
		$clsEmailTemplate = new EmailTemplate();
		$oneEmailTemplate = $clsEmailTemplate->getOne($email_template_id);
		$subject = $clsEmailTemplate->getSubject($email_template_id, $oneEmailTemplate);
		$message = $clsEmailTemplate->getContent($email_template_id, $oneEmailTemplate);
		
		$email_logo_url = $clsEmailTemplate->getLogoUrl($email_template_id);
		$width_logo = $clsEmailTemplate->getLogoWidth($email_template_id);
		$height_logo = $clsEmailTemplate->getLogoHeight($email_template_id);
		
		$smarty->assign('shop_url', $clsConfiguration->getValue('company_name'));
		$smarty->assign('shop_phone', $clsConfiguration->getValue('company_phone'));
		$smarty->assign('shop_email', $clsConfiguration->getValue('company_email'));
		$smarty->assign('shop_address', $clsConfiguration->getValue('company_address'));
		
		$smarty->assign('email_logo_url', $email_logo_url);
		$smarty->assign('width_logo', $width_logo?$width_logo:'auto');
		$smarty->assign('height_logo', $height_logo?$height_logo:'auto');
		
		$oneItem = $this->getOne($order_id);
		$profile_id = $oneItem['profile_id'];
		$order_link = PCMS_URL.'/order/detail/'.$order_id.'.html';
		$smarty->assign('order_link', $order_link);
		$smarty->assign('order_code', $oneItem['vpc_OrderNo']);
		$smarty->assign('customer_name', $clsProfile->getFullName($profile_id));
	
		$smarty->assign('billing_fullname',$this->getFieldValue($order_id,'fullname'));
		$smarty->assign('billing_phone',$this->getFieldValue($order_id,'phone'));
		$smarty->assign('billing_email',$this->getFieldValue($order_id,'email'));
		$smarty->assign('billing_address',$this->getFieldValue($order_id,'address'));
		
		$smarty->assign('shipping_fullname',$this->getFieldValue($order_id,'shipping_fullname'));
		$smarty->assign('shipping_phone',$this->getFieldValue($order_id,'shipping_phone'));
		$smarty->assign('shipping_email',$this->getFieldValue($order_id,'shipping_email'));
		$smarty->assign('shipping_address',$this->getFieldValue($order_id,'shipping_address'));
		
		$list_product_items = array();
		$tmp = $dbconn->getAll("select t1.*,t2.`title` from ".$clsOrderDetail->tbl." as t1 
		inner join ".$clsProduct->tbl." as t2 on t1.product_id=t2.product_id where t1.order_id='{$order_id}'");
		if(!empty($tmp)){
			foreach($tmp as $product){
				$list_product_items[] = array(
					'name' => $product['title'],
					'price' => $clsISO->formatPrice($product['price_dg']),
					'quantity' => $product['quantity'],
					'amount' => $clsISO->formatPrice($product['total_price']),
				);
			}
		}
		/** Price */
		$subtotal_price = $clsISO->formatPrice($oneItem['vpc_subtotal_money']);
		$discount_price = $clsISO->formatPrice($oneItem['discount_price']);
		$shipping_price = $clsISO->formatPrice($oneItem['shipping_price']);
		$total_price 	= $clsISO->formatPrice($oneItem['vpc_total_money']);
		
		$smarty->assign('list_product_items',$list_product_items);
		$smarty->assign('subtotal_price',$subtotal_price);
		$smarty->assign('discount_price',$discount_price);
		$smarty->assign('shipping_price',$shipping_price);
		$smarty->assign('total_price',$total_price);
		
		$template_string = $clsEmailTemplate->getContent($email_template_id);
		$subject = $smarty->fetch('eval:'.$subject);
		$message = $smarty->fetch('eval:'.$message);
		//var_dump($message); die();
		/* Send customer */
		$toemail = $this->getFieldValue($order_id,'email');
		$toname = $this->getFieldValue($order_id,'fullname');
		$clsISO->sendEmail($toemail, $toname, $subject, $message);
		/** Send admin */
		$lst_email_admins = $clsISO->getEmailNotifier('order');
		if(!empty($lst_email_admins)){
			foreach($lst_email_admins as $email){
				if($email['status']){
					$email_name = $email['email_name'];
					if(is_null($email_name)) $email_name = PAGE_NAME;
					$email_address = $email['email_address'];
					$clsISO->sendEmail($email_address, $email_name, $subject, $message);
				}
			}
		}
	}
	function doDelete($order_id){
//		$clsOrderDetail = new OrderDetail();
//		$clsOrderDetail->deleteByCond("order_id='{$order_id}'");
		$this->deleteOne($order_id);
	}
	function deleteOrderInTrash($cond){
		$tmp = $this->getAll($cond, $this->pkey);
		if(!empty($tmp)){
			$clsOrderDetail = new OrderDetail();
			foreach($tmp as $val){
				$this->doDelete($val[$this->pkey]);
			}
		}
	}
	function getLinkOrder($order_id,$one=null){
		global $oneProfile,$profile_id;
		if(!isset($one['order_code'])) {
			$one = $this->getOne($order_id,"order_code");
		}
		return "/view/order/MOC".$one['order_code'];
	}
}
?>