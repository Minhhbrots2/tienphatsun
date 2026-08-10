<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
class QrCode {
	function __construct(){
		// Some Code
	}
	public function encryptID($text){
		return base64_encode($text.'-VietISO');
	}
	public function decryptID($text){
		return str_replace('-VietISO','',base64_decode($text));
	}
	function getQRCode($booking_id){
		global $clsISO;
		$datastore_path = ABSPATH . DS . 'datastore' . DS . 'QrCode';
		$datastore_url =  DOMAIN_URL . DS . 'datastore' . DS . 'QrCode';
		if(!file_exists($datastore_path . DS . $booking_id.'_QrCode.png')){
			if(file_exists(DIR_INCLUDES.'/qrcode/autoload.php')){
				require_once(DIR_INCLUDES.'/qrcode/autoload.php');
				$qrCode = new Endroid\QrCode\QrCode();
				//$errorCorrectionLevel = new Endroid\QrCode\ErrorCorrectionLevel();
				//$labelAlignment = new Endroid\QrCode\LabelAlignment();
				$qrCode->setText($this->encryptID($booking_id));
				//header('Content-Type: '.$qrCode->getContentType());
				//echo $qrCode->writeString(); die;
				$qrCode->setSize(156);
				// Set advanced options
				$qrCode->setWriterByName('png');
				$qrCode->setMargin(10);
				$qrCode->setEncoding('UTF-8');
				$qrCode->setErrorCorrectionLevel('high');
				$qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
				$qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
				$qrCode->setValidateResult(false);
				//$qrCode->writeFile(__DIR__.'/qrcode.png');
				if(!is_dir($datastore_path)){
					$clsISO->rmkdir($datastore_path,0777);
				}
				$qrCode->writeFile($datastore_path . DS . $booking_id .'_QrCode.png');
			}
		}
		return $datastore_url . DS . $booking_id.'_QrCode.png';
	}
	function getQRCodeText($string,$params = array()){
		global $core, $clsISO;
		extract($params);//uid, size, margin
		$datastore_path = ABSPATH . DS . 'datastore' . DS . 'QrCode';
		$datastore_url =  DOMAIN_URL . DS . 'datastore' . DS . 'QrCode';
		if(empty($uid))$uid = $clsISO->getUniqid();
		if(empty($size))$size = 156;
		if(!isset($margin))$margin = 10;
		if(!file_exists($datastore_path . DS . $uid.'_QrCode.png')){
			if(file_exists(DIR_INCLUDES.'/qrcode/autoload.php')){
				require_once(DIR_INCLUDES.'/qrcode/autoload.php');
				$qrCode = new Endroid\QrCode\QrCode();
				//$errorCorrectionLevel = new Endroid\QrCode\ErrorCorrectionLevel();
				//$labelAlignment = new Endroid\QrCode\LabelAlignment();
				$qrCode->setText($string);
				//header('Content-Type: '.$qrCode->getContentType());
				//echo $qrCode->writeString(); die;
				$qrCode->setSize($size);
				// Set advanced options
				$qrCode->setWriterByName('png');
				$qrCode->setMargin($margin);
				$qrCode->setEncoding('UTF-8');
				$qrCode->setErrorCorrectionLevel('high');
				$qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0]);
				$qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
				$qrCode->setValidateResult(false);
				//$qrCode->writeFile(__DIR__.'/qrcode.png');
				if(!is_dir($datastore_path)){
					$clsISO->rmkdir($datastore_path,0777);
				}
				$qrCode->writeFile($datastore_path . DS . $uid .'_QrCode.png');
			}
		}
		return $datastore_url . DS . $uid.'_QrCode.png';
	}
}
