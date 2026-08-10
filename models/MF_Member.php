<?php if (!defined('ABSPATH')) exit('No direct script access allowed');
/*======================================================================*\
|| #################################################################### ||
|| # Model MF_Member — đọc/ghi trên CSDL thứ 2 (fhgroupt_mf)          # ||
|| # extends DbBasicMF (dùng kết nối $dbconnMF)                       # ||
|| # ---------------------------------------------------------------- # ||
|| # Các helper dưới đây được PORT từ models/Member.php, đã CHỈNH cho  # ||
|| # khớp schema bảng MF (default_member): chỉ dùng full_name,        # ||
|| # KHÔNG dùng first_name/last_name/department_id/role_id...          # ||
|| # Vì nằm trong MF_Member nên mọi $this->getOne/getAll/countItem     # ||
|| # tự chạy qua $dbconnMF (đúng CSDL MF).                             # ||
|| #################################################################### ||
\*======================================================================*/
class MF_Member extends DbBasicMF{
	function __construct(){
		// Bảng MF (theo schema thực): khóa chính profile_id, tên bảng default_member
		$this->pkey = "profile_id";
		$this->tbl  = "default_member";
	}

	# ---- Mã hoá mật khẩu (giống Member) ----
	function encrypt($password){
		return md5(md5($password));
	}

	# ---- Che chuỗi (email/phone) ----
	function mask($str, $mask=false){
		if($mask==false) return $str;
		if($str==''){ return ''; }
		$len = strlen($str);
		if($len > 6){
			$stat = 2; $end = 6;
			return substr_replace($str,'****',$stat,($end-$stat));
		}
		return $str;
	}

	# ---- Họ tên: MF chỉ có full_name (bỏ fallback first_name/last_name) ----
	function getFullName($user_id, $one=array()){
		if(!isset($one['full_name'])){
			$one = $this->getOne($user_id, "full_name");
		}
		return trim($one['full_name']);
	}

	# ---- Tên hiển thị nhanh ----
	function getName($member_id, $oDataTable=null){
		if(!isset($oDataTable['full_name']) || is_null($oDataTable)){
			$oDataTable = $this->getOne($member_id,"full_name");
		}
		return $oDataTable['full_name'];
	}

	# ---- Email (có mask) ----
	function getEmail($member_id, $oDataTable = array(), $mask=false){
		if(!isset($oDataTable['email'])){
			$oDataTable = $this->getOne($member_id,"email");
		}
		return $this->mask($oDataTable['email'], true);
	}

	# ---- Địa chỉ ----
	function getAddress($member_id, $is_link=true){
		$oDataTable = $this->getOne($member_id,"address");
		return !empty($oDataTable['address']) ? $oDataTable['address'] : '[Chưa cập nhật]';
	}

	# ---- Avatar ----
	function getAvatar($user_id, $oDataTable = array(), $w=60, $h=60){
		global $core, $clsISO;
		if(!isset($oDataTable['avatar'])){
			$oDataTable = $this->getOne($user_id, "avatar");
		}
		$avatar = trim($oDataTable['avatar']);
		if(!empty($avatar)){
			if($clsISO->checkContainer($avatar, '/images/avatar/','')){
				return '/files/thumb/'.$w.'/'.$h.'/'.$clsISO->parseImageURL($avatar);
			} else {
				return $avatar;
			}
		} else {
			return URL_IMAGES.'/no-avatar.jpg';
		}
	}

	# ---- Định danh: [avatar] code-full_name ----
	function getIndentity($member_id, $has_avatar = false, $class="xs"){
		if($member_id > 0){
			$one = $this->getOne($member_id, "code,full_name,avatar");
			if($has_avatar){
				$avatar = '<img class="avatar avatar-'.$class.' mr-2 rounded-pill"
				src="'.$this->getAvatar($member_id, $one).'" />';
				return $avatar . sprintf('%s-%s', $one['code'], $one['full_name']);
			}
			return sprintf('%s-%s', $one['code'], $one['full_name']);
		} else {
			return '-';
		}
	}

	# ---- Kiểm tra trùng username/email ----
	function checkValidUsername($user_name){
		return $this->countItem("user_name='{$user_name}' or email='{$user_name}'");
	}

	# ---- Sinh permalink duy nhất (đệ quy). MF dùng pkey profile_id ----
	function setPermalink($permalink, $check=1, $member_id=0){
		$cond = "";
		if($member_id > 0){
			$cond = " AND profile_id <>'{$member_id}'";
		}
		$countCheck = $this->countItem("permalink='{$permalink}'".$cond);
		if($countCheck > 0){
			$permalink = $this->setPermalink($permalink.($check+1), ++$check, $member_id);
		}
		return $permalink;
	}

	# ---- Upload ảnh lên Google Drive (giữ nguyên logic Member) ----
	function uploadImageGoogleDriver($files=[], $member_id=0){
		$results = array();
		if(!empty($files['name']) && $member_id > 0){
			for($i = 0; $i < count($files); $i++){
				$clsUploadFile = new UploadFile();
				$image = array();
				$image["name"]     = $files['name'][$i];
				$image["type"]     = $files['type'][$i];
				$image["tmp_name"] = $files['tmp_name'][$i];
				$image["error"]    = $files['error'][$i];
				$image["size"]     = $files['size'][$i];
				if(!empty($image["name"])){
					if(@is_uploaded_file($image['tmp_name'])){
						$clsUploadFile = new UploadFile();
						$upload_file = $clsUploadFile->uploadItem($image, "/BROKER", EXTENSION_FILE_UPLOAD);
						$file_name = $image['name'];
						$file_size = $image['size'];
						$mimeType  = $image['type'];
						// Upload file to google drive
						$clsGoogleUpload = new GoogleUpload(GOOGLE_DRIVE_FOLDER_TTDG_ID, true);
						$folder_id = $clsGoogleUpload->create_folder($member_id);
						$createdFile = $clsGoogleUpload->upload($file_name, $mimeType, ROOTPATH.$upload_file, $folder_id);
						$upload_file = 'https://drive.google.com/file/d/'.$createdFile->getId().'/view';
						if(!empty($upload_file)){
							$results[] = "https://drive.google.com/thumbnail?id=".$createdFile->getId()."&sz=w1000";
						}
						@unlink(ROOTPATH . $upload_file);
					}
				}
			}
		}
		return $results;
	}

	# ---- Gửi email khi nâng cấp VIP (dùng full_name) ----
	function sendEmailVIP($member_id){
		global $core, $clsISO;
		$clsEmailTemplate = new EmailTemplate();
		$oneMember = $this->getOne($member_id);
		$customer_name  = $this->getFullName($member_id, $oneMember);
		$customer_email = trim($oneMember['email']);
		###
		$oneEmailTemplate = $clsEmailTemplate->getOne(_MAIL_MEMBER_VIP_ID);
		$subject   = $clsEmailTemplate->getSubject(_MAIL_MEMBER_VIP_ID, $oneEmailTemplate);
		$message   = $clsEmailTemplate->getContent(_MAIL_MEMBER_VIP_ID, $oneEmailTemplate);
		$fromname  = $clsEmailTemplate->getFromName(_MAIL_MEMBER_VIP_ID, $oneEmailTemplate);
		$fromemail = $clsEmailTemplate->getFromEmail(_MAIL_MEMBER_VIP_ID, $oneEmailTemplate);
		$cc        = $clsEmailTemplate->getCopyTo(_MAIL_MEMBER_VIP_ID, $oneEmailTemplate);
		###
		$replace = array(
			'{time}'           => date('d/m/Y H:i:s'),
			'{customer_name}'  => $customer_name,
			'{customer_email}' => $customer_email
		);
		foreach($replace as $key => $val){
			$subject = @str_replace($key, $val, $subject);
			$message = @str_replace($key, $val, $message);
		}
		$toname  = $customer_name;
		$toemail = trim($customer_email);
		$is_send = $clsISO->sendEmailSystem($fromemail, $fromname, $toemail, $toname, $subject, $message, $cc);
		return $is_send;
	}
}
