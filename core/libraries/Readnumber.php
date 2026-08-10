<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # The Classes configurations of the ISOCMS                         # ||
|| # ISOCMS 6.0.0 VietISO Techical Team (vanthiembui.it@gmail.com)    # ||
|| # ---------------------------------------------------------------- # ||
|| # All PHP code in this file is ©2007-2014 VietISO JSC.             # ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- ISOCMS IS NOT FREE SOFTWARE ----------------    # ||
|| # http://www.vietiso.com | http://www.vietiso.com/license.html     # ||
|| #################################################################### ||
\*======================================================================*/
/**
*  Convert Number => String
*  @author		: Technical Group
*  @date		: 2009/1/18
*  @version		: 2.1.1
*/

$arrMoneyName = array(
	"VND"	=>	"&#273;&#7891;ng",
	"USD"	=>	"&#273;&#244; la"
);


if (!defined("CURRENT_MONEY")){
	define("CURRENT_MONEY", "VND");//money	
}
define("BILLION", "t&#7881;");//ty
define("MILLION", "tri&#7879;u");//trieu
define("THOUSAND", "ngh&#236;n");//nghin
define("HUNDRED", "tr&#259;m");//tram
define("TENS", "m&#432;&#417;i");//muoi
define("OLD", "l&#7867;");//linh

$arrBaseString = array(
	2	=>	THOUSAND,
	3	=>	MILLION,
	4	=>	BILLION
);

$arrNumString = array(
	0	=>	"kh&#244;ng",//khong
	1	=>	"m&#7897;t",//mot
	2	=>	"hai",//hai
	3	=>	"ba",//ba
	4	=>	"b&#7889;n",//bon
	5	=>	"n&#259;m",//nam
	6	=>	"s&#225;u",//sau
	7	=>	"b&#7843;y",//bay
	8	=>	"t&#225;m",//tam
	9	=>	"ch&#237;n",//chin
	10	=>	"m&#432;&#7901;i",//muoi
);

//test
//$number = $_SERVER['QUERY_STRING'];
//echo convertNumberToString($number);
/**
 *  Description	: convert a number to string
 *  @param 1 	: $number
 *  @return		: string
 *  @date		: 2006/12/07
 */
function convertNumberToString($number){
	global $arrNumString, $arrBaseString, $arrMoneyName;
	if ($number==null || $number=="") $number = 0; 
	$number = "$number";
	//filter zero
	$i = 0;
	while ($number[$i]=='0'){
		$i++;
	}
	$number = substr($number, $i);
	//get length
	$n = strlen($number);
	if ($n>12) return "Number too large!";
	$arrStr = array();
	for ($i=0; $i<$n; $i++){
		$index = ceil(($n-$i)/3);
		$arrStr[$index].=$number[$i];
	}
	$str = "";
	foreach ($arrStr as $k => $v){
		$str3 = convertNumberToString3($v);
		if ($str3!="")
			$str.= $str3." ".$arrBaseString[$k]." ";
	}
	$str.= $arrMoneyName[CURRENT_MONEY];
	if (function_exists(uft8html2utf8)){
		$str = uft8html2utf8($str);
	}
	return ucfirst($str);
}

/**
 *  Description	: convert number <999 to string
 *  @param 1 	: $number
 *  @return		: string
 *  @date		: 2006/12/07
 */
function convertNumberToString3($number){
	global $arrNumString, $arrBaseString;
	$n = strlen($number);
	$a = intval($number[0]);
	if ($n>3) return "Number must be <999!";
	if ($n==1) return $arrNumString[$a];
	if ($n==2) return convertNumberToString2($number);
	$str = "";
	$b = intval($number[1]);
	$c = intval($number[2]);
	$str2 = convertNumberToString2("$b$c");
	if ($a==0 && $str2==""){
		$str .= "";
	}else{
		$str .= $arrNumString[$a]." ".HUNDRED." ".$str2;
	}
	return $str;
}

/**
 *  Description	: convert number <99 to string
 *  @param 1 	: $number
 *  @return		: string
 *  @date		: 2006/12/07
 */
function convertNumberToString2($number){
	global $arrNumString, $arrBaseString;
	$n = strlen($number);
	$a = intval($number[0]);
	if ($n>2) return "Number must be <99!";
	if ($n==1) return $arrNumString[$a];
	$str = "";	
	$b = intval($number[1]);
	if ($a==0){
		$str .= ($b!=0)? OLD." " : "";
	}elseif ($a==1){
		$str .= $arrNumString[10]." ";
	}else{
		$str .= $arrNumString[$a]." ".TENS." ";
	}
	if ($b>0){
		$str.= $arrNumString[$b];
	}
	return $str;
}
?>