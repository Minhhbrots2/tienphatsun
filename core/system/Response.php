<?php  if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The modules of the ISOCMS                                        # ||
|| # ISOCMS 6.0.0 VietISO Technical (support@vietiso.com)             # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
class Response{
	protected static $httpVersion = "HTTP/1.1";
	protected static function getHttpStatusMessage($statusCode){
		$httpStatus = array(
			100 => 'Continue',  
			101 => 'Switching Protocols',  
			200 => 'OK',
			201 => 'Created',  
			202 => 'Accepted',  
			203 => 'Non-Authoritative Information',  
			204 => 'No Content',  
			205 => 'Reset Content',  
			206 => 'Partial Content',  
			300 => 'Multiple Choices',  
			301 => 'Moved Permanently',  
			302 => 'Found',  
			303 => 'See Other',  
			304 => 'Not Modified',  
			305 => 'Use Proxy',  
			306 => '(Unused)',  
			307 => 'Temporary Redirect',  
			400 => 'Bad Request',  
			401 => 'Unauthorized',  
			402 => 'Payment Required',  
			403 => 'Forbidden',  
			404 => 'Not Found',  
			405 => 'Method Not Allowed',  
			406 => 'Not Acceptable',  
			407 => 'Proxy Authentication Required',  
			408 => 'Request Timeout',  
			409 => 'Conflict',  
			410 => 'Gone',  
			411 => 'Length Required',  
			412 => 'Precondition Failed',  
			413 => 'Request Entity Too Large',  
			414 => 'Request-URI Too Long',  
			415 => 'Unsupported Media Type',  
			416 => 'Requested Range Not Satisfiable',  
			417 => 'Expectation Failed',  
			500 => 'Internal Server Error',  
			501 => 'Not Implemented',  
			502 => 'Bad Gateway',  
			503 => 'Service Unavailable',  
			504 => 'Gateway Timeout',  
			505 => 'HTTP Version Not Supported');
		return ($httpStatus[$statusCode]) ? $httpStatus[$statusCode] : $status[500];
	}
	protected static function setHttpHeaders($contentType, $statusCode){
		$statusMessage = self::getHttpStatusMessage($statusCode);
		if(!$contentType){
			$contentType = 'application/json; charset=utf-8';
		}
		header(self::$httpVersion . " ". $statusCode ." ". $statusMessage);		
		header("Content-Type:". $contentType);
	}
	protected static function _echoResponseJson($responseData) {
		return json_encode($responseData, JSON_UNESCAPED_UNICODE);	
	}
	protected static function _echoResponseHtml($responseData) {
		$htmlResponse = "<table border='1'>";
		foreach($responseData as $key=>$value) {
			$htmlResponse .= "<tr><td>". $key. "</td><td>". $value. "</td></tr>";
		}
		$htmlResponse .= "</table>";
		return $htmlResponse;		
	}
	protected static function _echoResponseXml($responseData) {
		// creating object of SimpleXMLElement
		$xml = new SimpleXMLElement('<?xml version="1.0"?><api></api>');
		foreach($responseData as $key=>$value) {
			$xml->addChild($key, $value);
		}
		return $xml->asXML();
	}
	public static function echoResponse($status_code, $response, $requestContentType='application/json'){
		self::setHttpHeaders($requestContentType, $status_code);
		if(strpos($requestContentType,'text/html') !== false){
			echo self::_echoResponseHtml($response);  die();
		} else if(strpos($requestContentType,'application/xml') !== false){
			echo self::_echoResponseXml($response); die();
		}else{
			echo self::_echoResponseJson($response); 
		}
	}
}
?>