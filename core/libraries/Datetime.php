<?
/**
*  Created by   :
*  @author		: Technical Group (technical@aboutpro.com)
*  @date		: 2009/1/18
*  @version		: 2.1.1
*/
//================= Fuctions related to DateTime =================
function convertDateTime($date="1/1/1970", $time="0:0:0"){
	$timeArr = explode(':', $time);
	$dateArr = explode('/', $date);
	return mktime($timeArr[0], $timeArr[1], 0, $dateArr[1], $dateArr[0], $dateArr[2]);
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

?>