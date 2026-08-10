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
class HubJS {
	public $api_url = 'https://crm.futurehomes.vn';
	public $api_username = 'vanthiembui.it@gmail.com';
	public $api_password = 'vanthiembui.it@gmail.com';
	function __construct(){
		// Some code
	}
	function sendData($source = array(), $object_type = 'employee', $sale_type = 0){
		global $core, $dbcon, $clsISO;
		$result = "_error";
		// $sale_type: 0 = Free; 1 = VVIP
		$url = sprintf('%s/api/erp/contact', $this->api_url);
		try{
			$more = array();
			$curl = new Curl\Curl();
			$curl->setHeader('Content-Type', 'application/json');
			$curl->setBasicAuthentication($this->api_username, $this->api_password);
			if($object_type == 'employee'){
				$id_vendor = $source['code'];
			} else if($object_type == 'sale'){
				$id_vendor = $source['profile_id'];
				$id_vendor = sprintf('MOC%s',$id_vendor);
				$more['sale_type'] = $sale_type;
			}
			$first_name = !empty($source['first_name']) ? $source['first_name'] : "";
			$last_name = !empty($source['last_name']) ? $source['last_name'] : "";
			$full_name = !empty($source['full_name']) ? $source['full_name'] : "";
			if(!empty($full_name) && empty($first_name) && empty($last_name)){
				$tmp = $clsISO->parseName($full_name);
				$first_name = $tmp['first_name'];
				$last_name = $tmp['last_name'];
			}
			$mobile = !empty($source['phone']) ? $source['phone'] : "";
			$email = !empty($source['email']) ? $source['email'] : "";
			$address = !empty($source['address']) ? $source['address'] : "";
			if(!empty($mobile)){
				$curl->post($url, array(array_merge($more, array(
					'id_vendor' => $id_vendor,
					'object_type' => $object_type,
					// 'lead_status ' => 0,1
					'firstname' => $first_name,
					'lastname' => $last_name,
					'mobile' => $mobile,
					'dateAdded' => date('Y-m-d H:i:s'),
					'email' => $email,
					'address1' => $address
				))));
				if($curl->error){
					$result = '_error';
				} else {
					$response = toArray($curl->response);
					if(isset($response['errors']) && $response['errors'] != 'false'){
						$result = '_success';
					}
				}
			}
		} catch(Exception $e){
			$result = '_error';
		}
		return $result;
	}
}