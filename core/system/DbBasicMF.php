<?php
class DbBasicMF{

	var $pkey 			= 	"";

	var $tbl 			= 	"";

	var $alias			= 	"";

	var $arrCond 		= 	array();

	var $arrOperator 	=	array();

	var $arrError 		= 	array();

	var $hasError 		= 	0;

	var $objName		=	"ObjTable";

	var $arrQuery		= 	array();

	function __construct(){

		// Some code

	}

	function updateDataPosition($pvalTable,$direct,$_dataload){

		$one = $this->getOne($pvalTable);

		$pkeyTable = $this->pkey;

		$order_no = $one['order_no'];

		$where = "is_trash=0";

		if($_dataload!=''){

			$where .= " and ".$_dataload;

		}

		if($direct=='up'){

			$lst = $this->getAll($where." and order_no < $order_no order by order_no desc limit 0,1");

			if($lst[0][$pkeyTable]!=''){

				$this->updateOne($pvalTable,"order_no='".$lst[0]['order_no']."'");

				$this->updateOne($lst[0][$this->pkey],"order_no='".$order_no."'");

			}

		}

		if($direct=='down'){

			$lst = $this->getAll($where." and order_no > $order_no order by order_no asc limit 0,1");

			if($lst[0][$pkeyTable]!=''){

				$this->updateOne($pvalTable,"order_no='".$lst[0]['order_no']."'");

				$this->updateOne($lst[0][$this->pkey],"order_no='".$order_no."'");

			}

		}

		if($direct=='bottom'){

			$lst = $this->getAll($where." and order_no > $order_no order by order_no desc LIMIT 0,1");

			if($lst[0][$pkeyTable]!=''){

				$this->updateOne($pvalTable,"order_no='".$lst[0]['order_no']."'");

				$lst = $this->getAll($where." and $pkeyTable <> '$pvalTable' and order_no > $order_no order by order_no DESC");

				for($i=0;$i<count($lst);$i++) {

					$this->updateOne($lst[$i][$this->pkey],"order_no='".($lst[$i]['order_no']-1)."'");	

				}

			}

		}

		if($direct=='top'){

			$lst = $this->getAll($where." and order_no < $order_no order by order_no ASC LIMIT 0,1");

			if($lst[0][$pkeyTable]!=''){

				$this->updateOne($pvalTable,"order_no='".$lst[0]['order_no']."'");

				$lst = $this->getAll($where." and $pkeyTable <> '$pvalTable' and order_no < $order_no order by order_no asc");

				for($i=0;$i<count($lst);$i++) {

					$this->updateOne($lst[$i][$this->pkey],"order_no='".($lst[$i]['order_no']+1)."'");	

				}

			}

		}

		return 1;

	}

	function createTableDB($sqlCreate,$sqlInit_f,$sqlInit_v){

		global $dbconnMF;

		$sqlExist="SELECT * FROM ".$this->tbl;

		$result =  $dbconnMF->Execute($sqlExist);

		$tblName = str_replace(DB_PREFIX,'',$this->tbl);

		if (!$result){

			$sqlCreate = "CREATE TABLE ".DB_PREFIX.$tblName."(						

						".$sqlCreate.",PRIMARY KEY(".$this->pkey.")) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";

			$dbconnMF->Execute($sqlCreate);

			/*Create Init Row*/

			if($sqlInit_f!=''&&$sqlInit_v!='')

				$this->insertOne($sqlInit_f,$sqlInit_v);

			return 1;

		} else {								

			return 2;

		}		

		return 0;

	}	

	function getMaxId(){

		global $dbconnMF;

		$sql = "SELECT DISTINCT ".$this->pkey." FROM ".$this->tbl." where 1=1 order by ".$this->pkey." desc LIMIT 0,1";

		$res = $dbconnMF->GetAll($sql);

		return intval($res[0][$this->pkey])+1;

	}

	function getMaxOrderNo(){

		global $dbconnMF;

		$sql = "SELECT DISTINCT order_no FROM ".$this->tbl." where 1=1 order by order_no desc LIMIT 0,1";

		$res = $dbconnMF->GetAll($sql);

		if(isset($res[0]['order_no']))

			return intval($res[0]['order_no'])+1;

		return 1;

	}

	function getOneField($field,$item_id){

		global $dbconnMF;

		$stime = array_sum(explode(' ',microtime()));

		$sql = "SELECT DISTINCT ".$field." FROM ".$this->tbl." where ".$this->pkey."=".$item_id." LIMIT 0,1";

		$res = $dbconnMF->GetAll($sql);

		$time = array_sum(explode(" ",microtime())) - $stime;

		ObjQuery::addQuery(array(

			'query'	=> $sql,

			'time'	=> $time

		));

		return isset($res[0][$field])?$res[0][$field]:'';

	}

	function getImageUrl($_pval){

		return $this->getOneField('image',$_pval);

	}

	//Set debug mode On/Off

	function SetDebug($debug=true){

		global $dbconnMF;

		$dbconnMF->debug = $debug;

	}

	//set condition $cond + $operator(AND, OR)

	function SetCond($cond, $operator=""){

		array_push($this->arrCond, $cond);

		array_push($this->arrOperator, $operator);

	}

	function setAlias($alias){

		$this->alias = $alias;

	}

	//get contition string

	function GetCond(){

		$condStr = "";

		if (is_array($this->arrCond)){

			foreach ($this->arrCond as $key => $val){

				$condStr.= " $val ".$this->arrOperator[$key];

			}

		}

		return $condStr;

	}

	//empty condition

	function EmptyCond(){

		$this->arrCond = array();

	}

	//Select One

	function SelectOne($_pkey=""){

		global $dbconnMF;		

		//get condition

		$cond = $this->getCond();

		if ($cond==""){

			$pkey = $this->pkey;

			$pkeyvalue = $_pkey;

			$cond = ($pkeyvalue!="")? "".$pkey."='".$pkeyvalue."'" : "";

		}

		if ($cond!=""){

			$where .= " WHERE $cond";

		}

		$sql = "SELECT * FROM ".$this->tbl." $where";

		$dbconnMF->debug =true;

		$rs = $dbconnMF->Execute($sql); 

		$obj = new $this->objName;

		if ($rs){

			$arr = $rs->FetchRow();//get a row

			if (is_array($arr)){

				foreach ($arr as $key => $val){

					$obj->set($key, $val);

				}  		

			}

		}

		return $obj;		

	}

	//Select All

	function SelectAll($orderby="", $start=0, $limit=0){

		global $dbconnMF;

		//get condition

		$cond = $this->getCond();

		$where = ($cond!="")? " WHERE $cond" : "";

		$orderby = ($orderby!="")? "ORDER BY $orderby" : "";

		$limit = ($limit!="")? "LIMIT $start, $limit" : "";

		$sql = "SELECT * FROM ".$this->tbl." $where $orderby $limit";

		$rs = $dbconnMF->Execute($sql); 

		$arrObj = array();

		if ($rs){

			while ($arr = $rs->FetchRow()) { 

				$obj = new $this->objName; 

				foreach ($arr as $key => $val){

					$obj->set($key, $val);

				}  	

				array_push($arrObj, $obj);	

			} 

		}

		return $arrObj;				

	}

	//Insert obj

	function insert_query($objTable){

		global $dbconnMF;

		$class_vars = get_class_vars(get_class($objTable));

		$fields = $values = array();

		foreach ($class_vars as $name => $value) {

			array_push($fields, $name);

			array_push($values, $objTable->$name);

		}

		$sql  = "INSERT INTO ".$this->tbl."($fields) VALUES($values)";

		if (!$dbconnMF->Execute($sql)){

			trigger_error("Cannot run SQL: `$sql`", E_USER_ERROR);

			return 0;

		}		

		return 1;

	}

	//Update obj

	function update_query($objTable){

		global $dbconnMF;

		$class_vars = get_class_vars(get_class($objTable));

		$set = "";

		foreach ($class_vars as $name => $value) {

			$set = ($set=="")? "$name = '".$this->$name."'" : ", $name = '".$objTable->$name."'";

		}

		//get condition

		$cond = $obj->getCond();

		if ($cond==""){

			$pkey = $this->pkey;

			$pkeyvalue = $this->$pkey;

			$cond = ($pkeyvalue!="")? "".$pkey."='".$pkeyvalue."'" : "";

		}

		if ($cond!=""){

			$where .= " WHERE $cond";

		}

		$sql = "UPDATE ".$this->tbl." SET $set $where";

		if (!$dbconnMF->Execute($sql)){

			trigger_error("Cannot run SQL: `$sql`", E_USER_ERROR);

			return 0;

		}

		return 1;				

	}

	//Delete obj

	function delete_query(){

		global $dbconnMF;

		//get condition

		$cond = $this->getCond();

		if ($cond!=""){

			$where .= " WHERE $cond";

		}

		$sql = "DELETE FROM ".$this->tbl." $where";

		if (!$dbconnMF->Execute($sql)){

			trigger_error("Cannot run SQL: `$sql", E_USER_ERROR);

			return 0;

		}

		return 1;		

	}

	//Count Item

	function Count($cond=""){

		global $dbconnMF;

		$sql = "SELECT COUNT(*) AS total FROM ".$this->tbl;

		//get condition

		$cond = $this->getCond();

		if ($cond!=""){

			$where .= " WHERE $cond";

		}

		$res = $dbconnMF->GetRow($sql);

		if ($res['total']=="" || $res['total']==null)

			return 0;

		return $res['total'];

	}

	function Max($field, $cond=""){

		global $dbconnMF;

		$sql = "SELECT MAX($field) AS total FROM ".$this->tbl;

		//get condition

		$cond = $this->getCond();

		if ($cond!=""){

			$where .= " WHERE $cond";

		}

		$res = $dbconnMF->GetRow($sql);

		if ($res['total']=="" || $res['total']==null)

			return 1;

		return ($res['total']+1);

	}

	function Sum($field, $cond=""){

		global $dbconnMF;

		$sql = "SELECT SUM($field) AS total FROM ".$this->tbl;

		//get condition

		$cond = $this->getCond();

		if ($cond!=""){

			$where .= " WHERE $cond";

		}

		$res = $dbconnMF->GetRow($sql);

		if ($res['total']=="" || $res['total']==null)

			return 0;

		return $res['total'];

	}

	//Execute a sql

	function ExecSql($sql){

		global $dbconnMF;

		return $dbconnMF->Execute($sql);

	}

	//=======================================

	//Integrate with old version

	//=======================================

	function getAllCache($cond="", $field="*", $time=ADODB_CACHED_TIME){

		global $dbconnMF,$_LANG_ID,$mod,$act;

		$where = !empty($cond) ? " WHERE {$cond}" : "";

		$sql = sprintf("SELECT %s FROM %s%s", $field, $this->tbl, $where);

		if(defined('ADODB_CACHED') && ADODB_CACHED == 1){

			$this->arrResults = $dbconnMF->CacheGetAll($time, $sql);

		} else {

			$this->arrResults = $dbconnMF->GetAll($sql);

		}

		return !empty($this->arrResults) ? $this->arrResults : false;

	}

	function getAll($cond="", $field="*", $debug=false){

		global $dbconnMF,$_LANG_ID;

		$where = "";

		if(!empty($cond)) $where.= " WHERE {$cond}";

		if(isset($this->alias) && !empty($this->alias)){

			$sql = "SELECT {$field} FROM `".$this->tbl."` AS `".$this->alias."` {$where}";

			$this->alias = "";

		} else {

			$sql = "SELECT {$field} FROM `".$this->tbl."` {$where}"; 

		}

		$this->arrResults = $dbconnMF->GetAll($sql, $debug);

		// var_dump($this->arrResults); die();

		return !empty($this->arrResults) ? $this->arrResults : array();

	}

	function getOne($_pkey="", $field="*", $debug=false){

		global $dbconnMF;

		$sql = "SELECT ".$field." FROM ".$this->tbl." WHERE ".$this->pkey."='$_pkey'";

		$this->arrResults = $dbconnMF->GetRow($sql, $debug);

		return !empty($this->arrResults) ? $this->arrResults : 0;

	}

	function getByCond($cond="", $field="*"){

		global $dbconnMF,$_LANG_ID;

		$where = "";

		#multiple language

		if(MULTIPLE_LANG){

			$sql_lang_id = "SHOW COLUMNS FROM `".$this->tbl."` LIKE 'lang_id'";

			$lstTbl = $dbconnMF->GetAll($sql_lang_id);

			if($lstTbl[0][0]=='lang_id'){

				$clsConfiguration = new Configuration();

				$SiteDefaultLanguage = $clsConfiguration->getValue('SiteDefaultLanguage');

				if($_LANG_ID!=$SiteDefaultLanguage && $_LANG_ID!=''){

					$where .= " WHERE lang_id='".$_LANG_ID."' ";

				} else{

					$where .= " WHERE lang_id='' ";

				}

			} 

		}

		#end multiple language

		if ($cond!=""){

			if ($where!=""){

				$where .= " and $cond";

			} else{

				$where .= " WHERE $cond";

			}

		}

		$sql = "SELECT ".$field." FROM ".$this->tbl." $where";

		$res = $dbconnMF->GetRow($sql);

		if (count($res)>0){

			return $res;

		}else{

			return 0;

		}

	}

	function checkColumnInTable($column_name){

		global $dbconnMF;

		$sql = "SHOW COLUMNS FROM `{$this->tbl}` LIKE '$column_name'";

		$lstTbl = $dbconnMF->GetAll($sql);

		return !empty($lstTbl) && $lstTbl[0][0]==$column_name ? 1: 0;

	}

	//Insert

	function insert($datastore=array(), $debug=false){

		global $dbconnMF, $_LANG_ID;

		if(empty($datastore)) return false;

		$fields = implode(", ",array_keys($datastore));

		$x = 1; $values = "";

		foreach($datastore as $key=>$value){

			$values .= $dbconnMF->qstr($value);

			if($x < count($datastore)){

				$values .= ', ';

			}

			$x++;

		}

		#multiple language

		if(MULTIPLE_LANG){

			if($this->checkColumnInTable('lang_id')){

				$clsConfiguration = new Configuration();

				$SiteDefaultLanguage = $clsConfiguration->getValue('SiteDefaultLanguage',LANG_DEFAULT);

				if($_LANG_ID!=$SiteDefaultLanguage && $_LANG_ID!=''){

					$fields .= ",lang_id";

					$values .= ",'$_LANG_ID'";

				}

			} 

		}

		#end multiple language

		$sql = "INSERT INTO ".$this->tbl." ({$fields}) VALUES({$values})";

		try{

			$res = $dbconnMF->Execute($sql, $debug);

			$class_name = get_class($this);

			if(!in_array($class_name, ARRAY_CLASS_NOT_INSERT_LOG)) {

				$pval = $dbconnMF->insert_Id();

				$clsActivityLog = new ActivityLog();

				$clsActivityLog->addActivityLog($class_name,"insert");		

			}

			return !empty($res) ? 1 : 0;

		}catch(Exception $e){

			throw new Exception($e->getMessage());

		}

	}

	function insertOne($fields="", $values="", $debug=false){

		global $dbconnMF,$_LANG_ID; 

		if (count($fields)!=count($values))return 0;

		#multiple language

		if(MULTIPLE_LANG){

			$sql_lang_id = "SHOW COLUMNS FROM `".$this->tbl."` LIKE 'lang_id'";

			$lstTbl = $dbconnMF->GetAll($sql_lang_id);

			if($lstTbl[0][0]=='lang_id'){

				$clsConfiguration = new Configuration();

				$SiteDefaultLanguage = $clsConfiguration->getValue('SiteDefaultLanguage');

				if($_LANG_ID!=$SiteDefaultLanguage && $_LANG_ID!=''){

					$fields .= ",lang_id";

					$values .= ",'$_LANG_ID'";

				}

			} 

		}

		#end multiple language

		$sql  = "INSERT INTO ".$this->tbl."(".$fields.") VALUES(".$values.")";

		//if($debug) return $sql;

		if (!$dbconnMF->Execute($sql, $debug))
//			echo "ERROR: " . $conn->ErrorMsg();
			return 0;

		#log

		$class_name = get_class($this);

		if(!in_array($class_name,ARRAY_CLASS_NOT_INSERT_LOG)) {

			$pval = $dbconnMF->insert_Id();

			$clsActivityLog = new ActivityLog();

			$clsActivityLog->addActivityLog($class_name,"insert");

		}

		return 1;

	}

	//Update

	function _array_to_string($datastore, $paid=','){

		global $dbconnMF, $core;

		$x = 0; $set = "";

		foreach($datastore as $key => $value) {

			$set .= ($x==0 ? "" : (strpos($key, 'OR')===FALSE ? $paid : "")) . sprintf("%s=%s", $key, $dbconnMF->qstr($value));

			++$x;

		}

		return $set;

	}

	function updateOne($_pkey="", $set="", $debug=false){

		global $dbconnMF;

		if($_pkey==0 || empty($set)) return;

		if(is_array($set)){

			$sql = "UPDATE {$this->tbl} SET ".$this->_array_to_string($set)." WHERE {$this->pkey}='{$_pkey}'";

		}else{

			$sql = "UPDATE ".$this->tbl." SET {$set} WHERE ".$this->pkey."='{$_pkey}'";

		}

		try{

			$res = $dbconnMF->Execute($sql, $debug);	

			#log
			$class_name = get_class($this);

			if(!in_array($class_name,ARRAY_CLASS_NOT_UPDATE_LOG)) {

				$clsActivityLog = new ActivityLog();

				$clsActivityLog->addActivityLog($class_name,"update");

			}

			return $res;

		}catch(Exception $e){

			throw new Exception($e->getMessage());

		}

	}

	function update($_pkey, $data, $_args=true){

		global $CONFIG, $dbconnMF;

		if(empty($data)) return;

		$set = "";

		foreach ($data as $key => $value) {

			$set .= "{$key} = '".$this->make_safe_field($value)."',";

		}

		$set = substr($set, 0, 0 - 1);

		$sql = "UPDATE ".$this->tbl." SET $set WHERE ".$this->pkey."='$_pkey'";

		//echo $sql; die();

		if(!$dbconnMF->Execute($sql)) return 0;

		#log

		$class_name = get_class($this);

		if(!in_array($class_name,ARRAY_CLASS_NOT_UPDATE_LOG)) {

			$clsActivityLog = new ActivityLog();

			$clsActivityLog->addActivityLog($class_name,"update");

		}

		return 1;

	}

	function delete($_id, $_args){

		global $dbconnMF;

		if((int) $_id==0) return '';

		$sql = "DELETE FROM ".$this->tbl." WHERE ".$this->pkey."='$_pkey'";

		$dbconnMF->Execute($sql);		

		#log

		$class_name = get_class($this);

		if(!in_array($class_name, ARRAY_CLASS_NOT_DELETE_LOG)) {

			$clsActivityLog = new ActivityLog();

			$clsActivityLog->addActivityLog($class_name,"delete");

		}

		return 1;

	}

	//Update by condition

	function updateByCond($cond="", $set="", $debug=false){

		global $dbconnMF;

		$where = "";

		if(!empty($cond)){

			$where .= " WHERE {$cond}";

		}

		$sql = "UPDATE ".$this->tbl." SET {$set}{$where}";

		//echo $sql; die();

		return $dbconnMF->Execute($sql, $debug);

	}

	//Delete

	function deleteOne($_pkey=""){

		global $dbconnMF;

		$sql = "DELETE FROM ".$this->tbl." WHERE ".$this->pkey."='$_pkey'";

		$dbconnMF->Execute($sql);	

		#log

		$class_name = get_class($this);

		if(!in_array($class_name,ARRAY_CLASS_NOT_DELETE_LOG)) {

			$clsActivityLog = new ActivityLog();

			$clsActivityLog->addActivityLog($class_name,"delete");

		}

		return 1;

	}

	function deleteByCond($cond=""){

		global $dbconnMF;

		$where = "";

		if ($cond!=""){

			$where .= " WHERE $cond";

		}

		$sql = "DELETE FROM ".$this->tbl." $where";

		$dbconnMF->Execute($sql);

		return 1;

	}

	function countItem($cond="", $field=""){

		global $dbconnMF;

		if(isset($this->alias) && !empty($this->alias)){

			$sql = "SELECT COUNT(*) AS `totalitem`".(!empty($field) ? $field:"")." FROM {$this->tbl} AS {$this->alias}";

			$this->alias = "";

		} else {

			$sql = "SELECT COUNT(*) AS totalitem".(!empty($field)?$field:"")." FROM ".$this->tbl;

		}

		if ($cond!="") $sql.= " WHERE $cond";

		$res = $dbconnMF->GetRow($sql);

		if (!isset($res['totalitem']) || $res['totalitem']=="" || $res['totalitem']==null)

			return 0;

		return $res['totalitem'];

	}

	function maxItem($field, $cond=""){

		global $dbconnMF;

		$sql = "SELECT MAX($field) AS total FROM ".$this->tbl;

		if ($cond!=""){

			$sql.= " WHERE $cond";

		}

		$res = $dbconnMF->GetRow($sql);

		if ($res['total']=="" || $res['total']==null)

			return 1;

		return ($res['total']+1);

	}

	function sumItem($field, $cond=""){

		global $dbconnMF;

		$sql = "SELECT SUM($field) AS total FROM ".$this->tbl;

		if ($cond!=""){

			$sql.= " WHERE $cond";

		}

		$res = $dbconnMF->GetRow($sql);

		if ($res['total']=="" || $res['total']==null)

			return 0;

		return $res['total'];

	}

	function getByField($pkey, $field){

		$res = $this->getOne($pkey);

		return $res[$field];

	}

}
?>
