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
 *  Description	: remove space left, right of string
*  @author		: Technical Group
*  @date		: 2009/1/18
*  @version		: 2.1.1
*/	
function strimSpace($item, $key){
	return trim($item);
}
/**
 *  Description	: remove char not aphabet from left, right of string
 *  @param 1 	: $str
 *  @return		: 1/0
 *  @date		: 2006/08/31
 */		
function StripString($str){
	if ($str==="") return $str;
	$i = 0;
	$j = strlen($str)-1;
	while (!isAphabet($str[$i]) && $i<$j){ $i++;}	
	while (!isAphabet($str[$j]) && $j>0) $j--;
	return substr($str, $i, $j-$i+1);
}

/**
 *  Description	: hightlight a string
 *  @param 1 	: $str
 *  @return		: 1/0
 *  @date		: 2006/08/31
 */		
function my_highlighter ($matches) {
	return '<font color=red>' . $matches[0] . '</font>';
}

/**
 *  Description	: split a keyword to array
 *  @param 1 	: $str
 *  @return		: 1/0
 *  @date		: 2006/08/31
 */		
function splitKeywordStr($keywordStr){
	//$keywordStr = mb_strtolower($keywordStr);
	preg_match_all("/\"([^\"]*)\"/", $keywordStr, $phase);
	$keywordStr = preg_replace("/\".*\"/U", "",$keywordStr);
	$words = preg_split('/(?<=\w-)|\s+/',$keywordStr);
	return array_merge($words, $phase[1]);	
}

function createCondition($field, $arrWords, $flag=true){
	$cond = "";
	if (!is_array($arrWords)) return $cond;
	foreach ($arrWords as $val)
	if($val!=""){
		//$val = filterAphabet($val);
		$cond.= ($cond=="")? "" : " OR ";
		if ($flag==false){
			$cond.= " ".$field." LIKE '%".$val."%'";
		}else{
			$cond.= " ".$field." LIKE '% ".$val."%' ";
		}
	}
	return $cond;
}

function filterAphabet($word){
	$special = array('/','!','<','>','?','"','\'', '+', '*', '\\','-' ,'[', ']', '(', ')', '$', '^','.', '~', '&', '#', '%'. '`',':',';','@'); 
	//$word = ereg_replace("[^[a-zA-Z0-9] ]", "",$word);
	$word = @str_replace($special,'',$word);
	$word = preg_replace('~^(\s*)(.*?)(\s*)$~m', "\\2", $word);	
	return $word;
}

function br2nl($str){
	$str = str_replace("<br>", "\n", $str);
	$str = str_replace("<br />", "", $str);
	return $str;
}

function uft8html2utf8( $s ) {
       if ( !function_exists('uft8html2utf8_callback') ) {
             function uft8html2utf8_callback($t) {
                     $dec = $t[1];
           if ($dec < 128) {
             $utf = chr($dec);
           } else if ($dec < 2048) {
             $utf = chr(192 + (($dec - ($dec % 64)) / 64));
             $utf .= chr(128 + ($dec % 64));
           } else {
             $utf = chr(224 + (($dec - ($dec % 4096)) / 4096));
             $utf .= chr(128 + ((($dec % 4096) - ($dec % 64)) / 64));
             $utf .= chr(128 + ($dec % 64));
           }
           return $utf;
             }
       }                               
       return preg_replace_callback('|&#([0-9]{1,});|', 'uft8html2utf8_callback', $s );                               
}

?>