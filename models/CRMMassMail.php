<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # CRM Private By VietISO (support@vietiso.com)                     # ||
|| # ---------------------------------------------------------------- # ||
|| # All Script code in this file is ©2007-2013 VietISO JSC.          # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
class CRMMassMail extends dbBasic{
	function __construct(){
		$this->pkey = "id";
		$this->tbl = "_crm_mass_mail";
		$this->setTable($this->tbl);
	}
	function getListType(){
		global $clsISO;
		$lstType = array();
		$lstType['customer'] =  __('Customer');
		$lstType['customergroup'] =  __('CustomerGroup');
		$lstType['campaign'] =  __('Campaigns');
		return $lstType;
	}
	function getTargetType($type){
		global $clsISO;
		$lstType = $this->getListType();
		$label = 'warning';
		if($type=='customer') $label = 'info';
		if($type=='customergroup') $label = 'success';
		return '<span class="label label-'.$label.' bold">'.$lstType[$type].'</span>';
	}
	function getListMsgType(){
		global $clsISO;
		$lstType = array();
		$lstType['email'] =  __('Email');
		/*$lstType['sms'] =  __('SMS');*/
		return $lstType;
	}
	function getMailType($type){
		global $clsISO;
		$lstType = $this->getListMsgType();
		return '<span class="label bold">'.$lstType[$type].'</span>';
	}
	function getListSystemVariable(){
		global $clsISO;
		$arr = array();
		$arr['{$company_name}'] = __('Company Name');
		$arr['{$company_domain}'] = __('Company Domain');
		$arr['{$company_logo_url}'] = __('Company Logo URL');
		$arr['{$signature}'] = __('Global Email Signature');
		$arr['{$date}'] = __('Date when send');
		$arr['{$time}'] = __('Date and Time when send');
		return $arr;
	}
	function getListClientVariable(){
		global $clsISO;
		$arr = array();
		$arr['{$client.id}'] = __('Client ID');
		$arr['{$client.firstname}'] = __('Client First Name');
		$arr['{$client.lastname}'] = __('Client Last Name');
		$arr['{$client.email}'] = __('Client Email');
		$arr['{$client.phonenumber}'] = __('Client Phone');
		$arr['{$client.companyname}'] = __('Client Company Name');
		$arr['{$client.address1}'] = __('Client Adress 1');
		$arr['{$client.address2}'] = __('Client Adress 2');
		$arr['{$client.city}'] = __('Client City');
		$arr['{$client.state}'] = __('Client State');
		$arr['{$client.postcode}'] = __('Client PostCode');
		$arr['{$client.country}'] = __('Client Country');
		return $arr;
	}
	function getListClientVariableValue($client_id, $oneCompany=null){
		global $clsISO;
		$clsCity = new City();
		$clsCountry = new Country();
		$clsVS_Client = new VS_Client();
		#
		$city_id = 0;
		$country_id = 0;
		if(is_null($oneCompany)){
			$oneCompany = $clsVS_Client->GetOne($client_id);
		}
		$country_id = 0; 
		if(!empty($oneCompany['country'])){
			$oneCountry = $clsCountry->getRow("code='".$oneCompany["country"]."' limit 0,1", "country_id");
			$country_id = empty($oneCountry) ? $oneCountry['country_id'] : 0;
		}
		#
		$arr = array();
		$arr['{$client.id}'] = $client_id;
		$arr['{$client.firstname}'] = $oneCompany['firstname'];
		$arr['{$client.lastname}'] = $oneCompany['lastname'];
		$arr['{$client.email}'] = $oneCompany['email'];
		$arr['{$client.phonenumber}'] = $oneCompany['phonenumber'];
		$arr['{$client.companyname}'] = $oneCompany['companyname'];
		$arr['{$client.address1}'] = $oneCompany['address1'];
		$arr['{$client.address2}'] = $oneCompany['address2'];
		$arr['{$client.city}'] = $oneCompany['city'];
		$arr['{$client.state}'] = $oneCompany['state'];
		$arr['{$client.postcode}'] = $oneCompany['postcode'];
		$arr['{$client.country}'] = ($country_id > 0 ? $clsCountry->getTitle($country_id):'');
		return $arr;
	}
	function getListContactVariable(){
		global $clsISO;
		$arr = array();
		$arr['{$contact.id}'] = __('Client ID');
		$arr['{$contact.name}'] = __('Client Company Name');
		$arr['{$contact.email}'] = __('Client Email');
		$arr['{$contact.phone}'] = __('Client Phone');
		$arr['{$contact.priority}'] = __('Contact Priority as number');
		$arr['{$contact.reg_date}'] = __('Date when Contact was created');
		$arr['{$contact.upd_date}'] = __('Date when Contact was updated');
		$arr['{$contact.status.id}'] = __('Contact Status Id');
		$arr['{$contact.status.name}'] = __('Contact Status Name');
		$arr['{$contact.status.color}'] = __('Contact Status Color');
		
		$arr['{$contact.type.id}'] = __('Contact Type Id');
		$arr['{$contact.type.name}'] = __('Contact Type Name');
		$arr['{$contact.type.color}'] = __('Contact Type Color');
		
		$arr['{$contact.admin.firstname}'] = __('Assigned Admin First Name');
		$arr['{$contact.admin.lastname}'] = __('Assigned Admin Last Name');
		$arr['{$contact.admin.email}'] = __('Assigned Admin Email');
		
		$arr['{$contact.client.id}'] = __('Client assigned ID');
		$arr['{$contact.client.firstname}'] = __('Client assigned First Name');
		$arr['{$contact.client.lastname}'] = __('Client assigned Last Name');
		$arr['{$contact.client.email}'] = __('Client assigned Email');
		$arr['{$contact.client.companyname}'] = __('Client assigned Company Name');
		$arr['{$contact.client.address1}'] = __('Client assigned Address 1');
		$arr['{$contact.client.address2}'] = __('Client assigned Address 2');
		$arr['{$contact.client.city}'] = __('Client assigned City');
		$arr['{$contact.client.state}'] = __('Client assigned State');
		$arr['{$contact.client.postcode}'] = __('Client assigned Postcoe');
		$arr['{$contact.client.country}'] = __('Client assigned Country');
		$arr['{$contact.client.phonenumber}'] = __('Client assigned Phone');
		return $arr;
	}
	function getListContactVariableValue($potential_id){
		global $clsISO;
		$clsVS_Admin = new VS_Admin();
		$clsCountry = new Country();
		$clsProperty = new Property();
		$clsVS_Client = new VS_Client();
		$clsPotential = new Potential();
		$onePotential = $clsPotential->GetOne($potential_id);
		#
		$status_id = $onePotential['status_id'];
		$type_id = $onePotential['type_id'];
		$admin_id = $onePotential['admin_id'];
		$client_id = $onePotential['client_id'];
		// Client
		$country_id = 0; 
		$oneCompany = array();
		if($client_id > 0){
			$CompanyField = "firstname,lastname,email,address1,address2,phonenumber,city,state,postcode,companyname,country";
			$oneCompany = $clsVS_Client->GetOne($client_id, $CompanyField);
			if(!empty($oneCompany['country'])){
				$oneCountry = $clsCountry->getRow("code='".$oneCompany["country"]."' limit 0,1", "country_id");
				$country_id = empty($oneCountry) ? $oneCountry['country_id'] : 0;
			}
		}
		// End Client
		$arr = array();
		$arr['{$contact.id}'] = $potential_id;
		$arr['{$contact.name}'] = $onePotential['name'];
		$arr['{$contact.email}'] = $onePotential['email'];
		$arr['{$contact.phone}'] = $onePotential['phone'];
		$arr['{$contact.priority}'] = $clsPotential->getPrioritySimple($potential_id, $onePotential);
		$arr['{$contact.reg_date}'] = $clsISO->convertTimeToText($onePotential['reg_date']);
		$arr['{$contact.upd_date}'] = $clsISO->convertTimeToText($onePotential['upd_date']);
		/* Status */
		$arr['{$contact.status.id}'] = $status_id;
		$arr['{$contact.status.name}'] = $clsProperty->getTitle($status_id);
		$arr['{$contact.status.color}'] = $clsProperty->getBgColor($status_id);
		/* Type */
		$arr['{$contact.type.id}'] = $type_id;
		$arr['{$contact.type.name}'] =  $clsProperty->getTitle($type_id);
		$arr['{$contact.type.color}'] = $clsProperty->getBgColor($type_id);
		/* Admin */
		$arr['{$contact.admin.firstname}'] = ($admin_id>0 ?$clsVS_Admin->getFirstName($admin_id) : "");
		$arr['{$contact.admin.lastname}'] = ($admin_id>0 ?$clsVS_Admin->getLastName($admin_id) : "");
		$arr['{$contact.admin.email}'] = ($admin_id>0 ?$clsVS_Admin->getEmail($admin_id) : "");
		/* Client */
		$arr['{$contact.client.id}'] = $client_id;
		$arr['{$contact.client.firstname}'] = ($client_id>0?$oneCompany['firstname']:"");
		$arr['{$contact.client.lastname}'] = ($client_id>0?$oneCompany['lastname']:"");
		$arr['{$contact.client.email}'] = ($client_id>0?$oneCompany['email']:"");
		$arr['{$contact.client.companyname}'] = ($client_id>0?$oneCompany['companyname']:"");
		$arr['{$contact.client.address1}'] = ($client_id>0?$oneCompany['address1']:"");
		$arr['{$contact.client.address2}'] = ($client_id>0?$oneCompany['address2']:"");
		$arr['{$contact.client.city}'] = ($client_id>0?$oneCompany['city']:"");
		$arr['{$contact.client.state}'] = ($client_id>0?$oneCompany['state']:"");
		$arr['{$contact.client.postcode}'] = ($client_id>0?$oneCompany['postcode']:"");
		$arr['{$contact.client.country}'] = ($country_id > 0 ? $clsCountry->getTitle($country_id):'');
		$arr['{$contact.client.phonenumber}'] = ($client_id>0?$oneCompany['phonenumber']:"");
		return $arr;
	}
	function getTotalItem($massmail_id){
		global $clsISO;
		$clsCRMMassMailSent = new CRMMassMailSent();
		return $clsCRMMassMailSent->countItem("massmail_id='{$massmail_id}'");
	}
	function getTotalSend($massmail_id){
		global $clsISO;
		$clsCRMMassMailSent = new CRMMassMailSent();
		return $clsCRMMassMailSent->countItem("massmail_id='{$massmail_id}' and status_sent='1'");
	}
	function getTotalQueue($massmail_id){
		global $clsISO;
		$clsCRMMassMailSent = new CRMMassMailSent();
		return $clsCRMMassMailSent->countItem("massmail_id='{$massmail_id}' and status_sent='0'");
	}
	function getStatus($massmail_id, $oDataTable=null){
		global $clsISO;
		if(!isset($oDataTable['date_id']) || is_null($oDataTable)){
			$oDataTable = $this->getOne($massmail_id, "date_id");
		}
		if($oDataTable['date_id'] < time()){
			return '<span class="label bold">'.__('Expired').'</span>';
		}else{
			return '<span class="label bold">'.__('Pending').'</span>';
		}
	}
}
?>