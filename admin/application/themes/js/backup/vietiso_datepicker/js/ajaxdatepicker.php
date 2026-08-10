
<?
/*
 * VietISO Date Picker plug-in 1.1
 *
 * http://www.vietiso.com/
 *
 * Copyright (c) 2006 - 2013 Dung Luong Tien
 *
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */
 $lang = 'vn';
	if($_POST['type']=='initDateInOut'){
		$checkin = $_POST['checkin'];
		$checkout = $_POST['checkout'];
		#
		echo(getHtmlDaySelectBox($checkin).'$$'.getHtmlMonthSelectBox($checkin).'$$'.getHtmlDaySelectBox($checkout).'$$'.getHtmlMonthSelectBox($checkout));die(); 
	}
	if($_POST['type']=='getDay'){
		$year_month = explode('-',$_POST['year_month']);
		$year = $year_month[0];$month = $year_month[1];
		$num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		$html = '';
		$type = $_POST['type'];
		$k = 0;
		for($i=1;$i<=$num;$i++){
			$slt = $_POST['slted_day']==$i?' selected="selected"':'';
			$D = getDayNameVi(strtotime($month.'/'.$i.'/'.$year));//date('D',strtotime($month.'/'.$i.'/'.$year));
			if(strtotime($month.'/'.$_POST['slted_day'].'/'.$year)<time() && $k==0){
				$slt = ' selected="selected"';
			}
			if((strtotime($month.'/'.$i.'/'.$year)>time()-24*60*60 && $type=='')||(strtotime($month.'/'.$i.'/'.$year)>time())){
				$html .= '<option value="'.$i.'" '.$slt.'>'.$D.' '.$i.'</option>';
				$k = 1;
			}
		}
		if($_POST['year_month']==0){
			for($i=1;$i<=31;$i++){
				$html .= '<option value="'.$i.'">'.$i.'</option>';
			}
		}
		echo($html);die();
	}
	if($_POST['type']=='checkRange'){
		$checkin = explode('-',$_POST['checkin']);
		$checkout = explode('-',$_POST['checkout']);
		#
		if($_POST['checkout']==''||$_POST['checkout']=='0'){
			echo('empty_out$$'.date('Y-n-j',strtotime($checkin[1].'/'.$checkin[2].'/'.$checkin[0])+24*60*60));die();
		}
		if($_POST['checkin']==''||$_POST['checkin']=='0'){
			echo('empty_in$$'.date('Y-n-j',strtotime($checkout[1].'/'.$checkout[2].'/'.$checkout[0])-24*60*60));die();
		}
		if($_POST['actSubmit']=='InOut'&& strtotime($checkin[1].'/'.$checkin[2].'/'.$checkin[0])>strtotime($checkout[1].'/'.$checkout[2].'/'.$checkout[0])){
			echo('empty_out$$'.date('Y-n-j',strtotime($checkin[1].'/'.$checkin[2].'/'.$checkin[0])+24*60*60));die();
		}
		if($_POST['actSubmit']=='OutIn'&& strtotime($checkin[1].'/'.$checkin[2].'/'.$checkin[0])>strtotime($checkout[1].'/'.$checkout[2].'/'.$checkout[0])){
			echo('empty_in$$'.date('Y-n-j',strtotime($checkout[1].'/'.$checkout[2].'/'.$checkout[0])-24*60*60));die();
		}
	}
	if($_POST['type']=='checkSubmit'){
		$checkin = explode('-',$_POST['checkin']);
		$checkout = explode('-',$_POST['checkout']);
		if(strtotime($checkin[1].'/'.$checkin[2].'/'.$checkin[0])<time()||strtotime($checkout[1].'/'.$checkout[2].'/'.$checkout[0])<time()){
			echo('error');die(); 
		}
		if(strtotime($checkin[1].'/'.$checkin[2].'/'.$checkin[0])>strtotime($checkout[1].'/'.$checkout[2].'/'.$checkout[0])){
			echo('error');die();
		}
		else{
			echo('success');die();
		}
	}
	/*Function*/
	function getDayNameVi($time){
		$d = date("D",$time);
		if($d=='Mon') return 'Thứ Hai';
		if($d=='Tue') return 'Thứ Ba';
		if($d=='Wed') return 'Thứ Tư';
		if($d=='Thu') return 'Thứ Năm';
		if($d=='Fri') return 'Thứ Sáu';
		if($d=='Sat') return 'Thứ Bảy';
		if($d=='Sun') return 'Chủ nhật';
		return '';
	}
	function parseTextMonth($month_slt){
		return 'Tháng '.$month_slt;
		if($month_slt==1){
			return 'January';
		}
		if($month_slt==2){
			return 'February';
		}
		if($month_slt==3){
			return 'March';
		}
		if($month_slt==4){
			return 'April';
		}
		if($month_slt==5){
			return 'May';
		}
		if($month_slt==6){
			return 'June';
		}
		if($month_slt==7){
			return 'July';
		}
		if($month_slt==8){
			return 'August';
		}
		if($month_slt==9){
			return 'September';
		}
		if($month_slt==10){
			return 'October';
		}
		if($month_slt==11){
			return 'November';
		}
		if($month_slt==12){
			return 'December';
		}
		
		return '';
	}
	function getHtmlMonthSelectBox($date){
		$date = explode('-',$date);
		#
		$now = time();
		$day = date('d');
		$month = date('m');
		$year = date('Y');
		$html = '<option class="month prompt" value="">Tháng</option>';
		for($i=0;$i<13;$i++){
			$month_slt = $i+$month;
			$year_slt = $year;
			if($month_slt>12){
				$month_slt = $month_slt-12;
				$year_slt = $year_slt+1; 
			}
			$html .= '<option '.($year_slt.'-'.$month_slt==$date[0].'-'.$date[1]?' selected="selected"':'').' value="'.$year_slt.'-'.$month_slt.'">'.parseTextMonth($month_slt).'/'.$year_slt.'</option>';
		}
		return $html;
	}
	function getHtmlDaySelectBox($date){
		$date = explode('-',$date);
		#
		$now = time();
		$day = date('d');
		$month = date('m');
		$year = date('Y');
		$html = '<option class="day prompt" value="">Ngày</option>';
		for($i=1;$i<32;$i++){
			$html .= '<option '.($i==$date[2]?' selected="selected"':'').' value="'.$i.'">'.$i.'</option>';
		}
		return $html;
	}
?>