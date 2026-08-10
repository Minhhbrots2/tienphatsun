<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 code by Bui Van Thiem (vanthiembui.it@gmail.com)   # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 MaxCMS.                  # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| #################################################################### ||
\*======================================================================*/
class ZaloUser extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = DB_PREFIX."zalo_users";
	}
	function init($zaloId){
		global $core, $dbconn, $clsISO;
		$msg = "_error";
		if($this->countItem("`user_id`='{$zaloId}'")  > 0){
			$msg = "_duplicated";
		} else {
			$curl = new \Curl\Curl();
			$curl->setHeaders(array(
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0eXBlIjoiRlVOQ1RJT04iLCJpZCI6IjY4OTMzZjExNTAxOTEwMmQ0MjhmNmY1YiIsImlhdCI6MTc1NDQ4MDQwMiwiZXhwIjoxNzg2MDE2NDAyfQ.q-Lrzlv4fVMdQYcVdFF3nKJoz4rytn2rDfy27lvq_PQ'
			));
			$curl->post('https://public-api.bizflow.vn/functions/68933f115019102d428f6f5b', array(
				'user_id' => (string) $zaloId
			));
			if(!$curl->error){
				$response = toArray($curl->response);
				if(isset($response['status']) && $response['status'] == 200){
					$msg = "_success";
					$user_info = isset($response['data']['data'][$zaloId]) ? $response['data']['data'][$zaloId] : [];
					if(!empty($user_info)){
						$this->insert(array(
							'reg_date' => time(),
							'user_id' => $zaloId,
							'user_info' => json_encode($user_info, JSON_UNESCAPED_UNICODE)
						));
					}
				}
			}
		}
		return $msg;
	}
}