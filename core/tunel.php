<?php
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the MaxxCMS                        # ||
|| # MaxxCMS 6.0.0 Techical Team (vanthiembui.it@gmail.com)    		  # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2014-2015 by Future Group.         # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- MaxxCMS IS NOT FREE SOFTWARE ----------------   # ||
|| #################################################################### ||
\*======================================================================*/
define('DEBUG_MODE', TRUE);
//set allowTestMenu to false to disable System/Server test page
$testMenu = false;
$allowTestMenu = true;
if (DEBUG_MODE) {
	ini_set('display_errors',1);
	//trigger_error("Custom user notice", E_USER_NOTICE);
	error_log('POST Array: '.print_r($_POST, true));
	error_reporting(E_ALL);
	set_time_limit(5);
} else {
	error_reporting(0);
	set_time_limit(0);
}
//set_magic_quotes_runtime(0);
header("Content-Type: text/plain; charset=x-user-defined");
function phpversion_int(){
	$php = preg_split("/[\/\.-]/", phpversion());
	$maVer = $php[0];
	$miVer = $php[1];
	$edVer = $php[2];
	return $maVer*10000 + $miVer*100 + $edVer;
}
function GetLongBinary($num){
	return pack("N",$num);
}
function GetShortBinary($num){
	return pack("n",$num);
}
function GetDummy($count){
	$str = "";
	for($i=0;$i<$count;$i++)
		$str .= "\x00";
	return $str;
}
function GetBlock($val){
	$len = strlen($val);
	if( $len < 254 )
		return chr($len).$val;
	else
		return "\xFE".GetLongBinary($len).$val;
}
function EchoHeader($errno)
{
	$str = GetLongBinary(1111);
	$str .= GetShortBinary(201);
	$str .= GetLongBinary($errno);
	if (DEBUG_MODE) {
		error_log('EchoHeader ($errno): '.$errno);
	}
	$str .= GetDummy(6);
	echo $str;
}
function EchoConnInfo($conn){
	$str = GetBlock(mysqli_get_host_info($conn));
	if (DEBUG_MODE) {
		error_log('EchoConnInfo (mysqli_get_host_info($conn)): '.mysqli_get_host_info($conn));
	}
	$str .= GetBlock(mysqli_get_proto_info($conn));
	if (DEBUG_MODE) {
		error_log('EchoConnInfo (mysqli_get_proto_info($conn)): '.mysqli_get_proto_info($conn));
	}
	$str .= GetBlock(mysqli_get_server_info($conn));
	if (DEBUG_MODE) {
		error_log('EchoConnInfo (mysqli_get_server_info($conn)): '.mysqli_get_server_info($conn));
	}
	echo $str;
}
function EchoResultSetHeader($errno, $affectrows, $insertid, $numfields, $numrows){
	$str = GetLongBinary($errno);
	if (DEBUG_MODE) {
		error_log('EchoResultSetHeader ($errno): '.$errno);
	}
	$str .= GetLongBinary($affectrows);
	if (DEBUG_MODE) {
		error_log('EchoResultSetHeader ($affectrows): '.$affectrows);
	}
	$str .= GetLongBinary($insertid);
	if (DEBUG_MODE) {
		error_log('EchoResultSetHeader ($insertid): '.$insertid);
	}
	$str .= GetLongBinary($numfields);
	if (DEBUG_MODE) {
		error_log('EchoResultSetHeader ($numfields): '.$numfields);
	}
	$str .= GetLongBinary($numrows);
	if (DEBUG_MODE) {
		error_log('EchoResultSetHeader ($numrows): '.$numrows);
	}
	$str .= GetDummy(12);
	echo $str;
}
function EchoFieldsHeader($res, $numfields){
	$str = "";
	for( $i = 0; $i < $numfields; $i++ ) {
		$finfo = mysqli_fetch_field_direct($res, $i);
		$str .= GetBlock($finfo->name);
		if (DEBUG_MODE) {
			error_log('EchoFieldsHeader ($finfo->name): '.$finfo->name);
		}
		$str .= GetBlock($finfo->table);
		if (DEBUG_MODE) {
			error_log('EchoFieldsHeader ($finfo->table): '.$finfo->table);
		}
		$str .= GetLongBinary($finfo->type);
		if (DEBUG_MODE) {
			error_log('EchoFieldsHeader ($finfo->type): '.$finfo->type);
		}
		$str .= GetLongBinary($finfo->flags);
		if (DEBUG_MODE) {
			error_log('EchoFieldsHeader ($finfo->flags): '.$finfo->flags);
		}
		$str .= GetLongBinary($finfo->length);
		if (DEBUG_MODE) {
			error_log('EchoFieldsHeader ($finfo->length): '.$finfo->length);
		}
	}
	echo $str;
}
function EchoData($res, $numfields, $numrows){
	for( $i = 0; $i < $numrows; $i++ ) {
		$str = "";
		$row = mysqli_fetch_row( $res );
		for( $j = 0; $j < $numfields; $j++ ){
			if( is_null($row[$j]) ) {
				$str .= "\xFF";
			} else {
				$str .= GetBlock($row[$j]);
				if (DEBUG_MODE) {
					error_log('EchoData ($row['.$j.']): '.$row[$j]);
				}
			}
		}
		echo $str;
	}
}
if (phpversion_int() < 40005) {
	EchoHeader(201);
	echo GetBlock("unsupported php version");
	exit();
}
if (phpversion_int() < 40010) {
	global $HTTP_POST_VARS;
	$_POST = &$HTTP_POST_VARS;
}
if (!isset($_POST["actn"]) || !isset($_POST["host"]) || !isset($_POST["port"]) || !isset($_POST["login"])) {
	$testMenu = $allowTestMenu;
	if (!$testMenu){
		EchoHeader(202);
		echo GetBlock("invalid parameters");
		exit();
	}
}
if (!$testMenu){
	if ($_POST["encodeBase64"] == '1') {
		for($i=0;$i<count($_POST["q"]);$i++)
			$_POST["q"][$i] = base64_decode($_POST["q"][$i]);
	}
	if (!function_exists("mysqli_connect")) {
		EchoHeader(203);
		echo GetBlock("MySQL not supported on the server");
		exit();
	}
	$errno_c = 0;
	$hs = $_POST["host"];
	if( $_POST["port"] ) $hs .= ":".$_POST["port"];
	$conn = mysqli_connect($hs, $_POST["login"], $_POST["password"]);
	$errno_c = mysqli_connect_errno();
	if(($errno_c <= 0) && ( $_POST["db"] != "" )) {
		$res = mysqli_select_db($conn, $_POST["db"]);
		$errno_c = mysqli_errno($conn);
	}
	EchoHeader($errno_c);
	if($errno_c > 0) {
		echo GetBlock(mysqli_error($conn));
	} elseif($_POST["actn"] == "C") {
		EchoConnInfo($conn);
	} elseif($_POST["actn"] == "Q") {
		for($i=0;$i<count($_POST["q"]);$i++) {
			$query = $_POST["q"][$i];
			if($query == "") continue;
			if(get_magic_quotes_gpc())
				$query = stripslashes($query);
			$resSet = false;
			$res = mysqli_query($conn, $query);
			$errno = mysqli_errno($conn);
			if ($res === TRUE) {
				$affectedrows = 0;
				$insertid = 0;
				$numfields = 0;
				$numrows = 0;
			} else if ($res) {
				$resSet = true;
				$affectedrows = mysqli_affected_rows($conn);
				$insertid = mysqli_insert_id($conn);
				$numfields = mysqli_num_fields($res);
				$numrows = mysqli_num_rows($res);
			}
			EchoResultSetHeader($errno, $affectedrows, $insertid, $numfields, $numrows);
			if($errno > 0)
				echo GetBlock(mysqli_error($conn));
			else {
				if($numfields > 0) {
					EchoFieldsHeader($res, $numfields);
					EchoData($res, $numfields, $numrows);
				} else {
					if(phpversion_int() >= 40300)
						echo GetBlock(mysqli_info($conn));
					else
						echo GetBlock("");
				}
			}
			if($i<(count($_POST["q"])-1))
				echo "\x01";
			else
				echo "\x00";
			if ($resSet) {
				mysqli_free_result($res);
			}
		}
	}
	exit();
}
?>