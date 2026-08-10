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
$app->get('/get-points', function ($request, $response) use ($app) {
	global $dbconn, $clsISO;
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	#- End require
	$fpoint_configs = array();
	$cachedFile = DIR_CACHE_JSON.'/fpoint.json';
	if(@file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$fpoint_configs = $decoder->decodeFile($cachedFile);
	}
	// Return
	echo echoResponse('200', array(
		'result' => 'success',
		'fpoint_configs' => $fpoint_configs
	));
});
$app->get('/good-morning', function ($request, $response) use ($app) {
	global $dbconn, $clsISO;
	$clsQuote = new Quote();
	#- Require library
	require_once(DIR_INCLUDES.'/json_master/autoload.php');
	$cachedFile = DIR_CACHE_JSON.'/system/quoted.json';
	$encoder = new Webmozart\Json\JsonEncoder();
	#
	$result = "error";
	$sql_query_notin = "";
	$sql_query = "`apply_to`='all'";
	#
	$quoteCached = array();
	if(@file_exists($cachedFile)){
		$decoder = new Webmozart\Json\JsonDecoder();
		$quoteCached = $decoder->decodeFile($cachedFile);
		if(!empty($quoteCached)) {
			$sql_query_notin = " AND `{$clsQuote->pkey}` NOT IN (".implode(',',$quoteCached).")";
		}
	}
	$oneQuote = $clsQuote->getByCond($sql_query.$sql_query_notin." ORDER BY RAND() LIMIT 1","`{$clsQuote->pkey}`,`content`,`author`");
	if(!empty($oneQuote)){
		$quoteCached[] = $oneQuote[$clsQuote->pkey];
	} else{
		$quoteCached = array();
		$oneQuote = $clsQuote->getByCond($sql_query." ORDER BY RAND() LIMIT 1","`{$clsQuote->pkey}`,`content`,`author`");
		$quoteCached[] = $oneQuote[$clsQuote->pkey];
	}
	$encoder->encodeFile($quoteCached, $cachedFile);
	if(!empty($oneQuote)){
		$result = "success";
		$curl = new \Curl\Curl();
		$curl->setHeaders(array(
			'Content-Type' => 'application/json',
			'Authorization' => sprintf('Bearer %s', ZALO_GROUP_SEND_MESSAGE_API_KEY)
		));
		$message= sprintf('“%s“', strip_tags(html_entity_decode($oneQuote['content'])));
		$message.= "\n";
		if($oneQuote['author']){
			$message.= '--' . $oneQuote['author'];
			$message.= "\n";
		}
		$curl = new \Curl\Curl();
		$curl->setHeaders(array(
			'Content-Type' => 'application/json',
			'Authorization' => sprintf('Bearer %s', ZALO_GROUP_SEND_MESSAGE_API_KEY)
		));
		$curl->post('https://public-api.func.vn/functions/68011b1db49c8ee7d3eac010', array(
			'message' => $message,
			'group_id' => _FH_GROUP_ZALO_ID
		));
	}
	// Return
	echo echoResponse('200', array(
		'result' => $result
	));
});