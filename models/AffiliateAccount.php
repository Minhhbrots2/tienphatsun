<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

class AffiliateAccount extends DbBasic {
    function __construct(){
        $this->pkey = "id";
        $this->tbl = DB_PREFIX."affiliate_accounts"; 
    }
    /**
     * Lấy tài khoản affiliate của user. Nếu chưa có thì tự động tạo.
     */
    function getAccount($member_id) {
        if (!$member_id) return false;
        $account = $this->getByCond("member_id = '{$member_id}' LIMIT 1");
        if (!empty($account)) {
            return $account;
        }
        // Tạo tài khoản mới
        $code = $this->generateUniqueCode($member_id);
        $referral_link = PCMS_URL . '/ref/' . $code;
        $datastore = array(
            'member_id' => $member_id,
            'affiliate_code' => $code,
            'referral_link' => $referral_link,
            'level' => 1,
            'total_referral' => 0,
            'total_commission' => 0,
            'total_paid' => 0,
            'total_pending' => 0,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        );
        if ($this->insert($datastore)) {
            return $this->getByCond("member_id = '{$member_id}' LIMIT 1");
        }
        return false;
    }
    /**
     * Sinh mã giới thiệu độc nhất dựa vào ID + SĐT + Email
     */
    function generateUniqueCode($member_id) {
        $clsProfile = new Profile();
        $oneProfile = $clsProfile->getOne($member_id, "phone, email");
        $phone = isset($oneProfile['phone']) ? $oneProfile['phone'] : '';
        $email = isset($oneProfile['email']) ? $oneProfile['email'] : '';
        // Ghép chuỗi cơ sở
        $baseString = $member_id . '_' . $phone . '_' . $email;
        $nonce = 0;
        do {
            // Bam chuỗi kết hợp với nonce để đề phòng trùng lặp (dù tỷ lệ rất thấp)
            $hashStr = md5($baseString . '_' . $nonce);
            // Lấy 6 ký tự từ mã hash, viết hoa và ghép tiền tố MT
            $code = strtoupper(substr($hashStr, 0, 10));
            $exists = $this->countItem("affiliate_code = '{$code}'");
            $nonce++;
        } while ($exists > 0);
        return $code;
    }
}
