<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

class AffiliateWallet extends DbBasic {
    function __construct(){
        $this->pkey = "id";
        $this->tbl = DB_PREFIX."affiliate_wallets"; 
    }

    /**
     * Lấy ví affiliate của user. Nếu chưa có thì tự động tạo.
     */
    function getWallet($member_id) {
        if (!$member_id) return false;
        $wallet = $this->getByCond("member_id = '{$member_id}' LIMIT 1");
        if (!empty($wallet)) return $wallet;

        // Tạo ví mới
        $walletData = array(
            'member_id' => $member_id,
            'balance' => 0,
            'total_earned' => 0,
            'total_withdrawn' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        );
        if ($this->insert($walletData)) {
            return $this->getByCond("member_id = '{$member_id}' LIMIT 1");
        }
        return false;
    }

    /**
     * Cộng tiền vào ví affiliate và ghi log giao dịch
     */
    function addFunds($member_id, $amount, $description = '') {
        if (!$member_id || $amount <= 0) return false;
        $wallet = $this->getWallet($member_id);
        if (empty($wallet)) return false;

        $new_balance = (float)$wallet['balance'] + $amount;
        $new_earned = (float)$wallet['total_earned'] + $amount;

        $updateData = array(
            'balance' => $new_balance,
            'total_earned' => $new_earned,
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($this->updateOne($wallet['id'], $updateData)) {
            // Log giao dịch ví
            $clsWalletLog = new AffiliateWalletLog();
            $logData = array(
                'member_id' => $member_id,
                'amount' => $amount,
                'type' => 'commission',
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s')
            );
            $clsWalletLog->insert($logData);
            return true;
        }
        return false;
    }

    /**
     * Trừ tiền trong ví affiliate (khi rút tiền) và ghi log giao dịch
     */
    function deductFunds($member_id, $amount, $description = '') {
        if (!$member_id || $amount <= 0) return false;
        $wallet = $this->getWallet($member_id);
        if (empty($wallet) || (float)$wallet['balance'] < $amount) return false;

        $new_balance = (float)$wallet['balance'] - $amount;
        $new_withdrawn = (float)$wallet['total_withdrawn'] + $amount;

        $updateData = array(
            'balance' => $new_balance,
            'total_withdrawn' => $new_withdrawn,
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($this->updateOne($wallet['id'], $updateData)) {
            // Log giao dịch ví
            $clsWalletLog = new AffiliateWalletLog();
            $logData = array(
                'member_id' => $member_id,
                'amount' => -$amount,
                'type' => 'withdrawal',
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s')
            );
            $clsWalletLog->insert($logData);
            return true;
        }
        return false;
    }

    /**
     * Trừ tiền NGUYÊN TỬ (chống double-spend / race condition):
     * chỉ trừ khi số dư hiện tại CÒN ĐỦ, bằng một câu UPDATE có điều kiện.
     * Trả về true nếu trừ thành công (đúng 1 dòng bị ảnh hưởng).
     */
    function deductFundsAtomic($member_id, $amount, $description = '') {
        global $dbconn;
        $member_id = (int)$member_id;
        $amount = (float)$amount;
        if (!$member_id || $amount <= 0) return false;

        // Đảm bảo ví tồn tại trước khi trừ
        $wallet = $this->getWallet($member_id);
        if (empty($wallet)) return false;

        $amt = sprintf('%.2f', $amount);
        $now = date('Y-m-d H:i:s');
        // 1 câu UPDATE có điều kiện → nguyên tử ở mức row-lock của InnoDB.
        // Hai request rút đồng thời KHÔNG thể cùng thành công.
        $sql = "UPDATE {$this->tbl} SET balance = balance - {$amt}, total_withdrawn = total_withdrawn + {$amt}, updated_at = '{$now}' WHERE member_id = {$member_id} AND balance >= {$amt}";
        $dbconn->Execute($sql);
        if ((int)$dbconn->Affected_Rows() !== 1) {
            return false; // số dư không đủ (WHERE không khớp) hoặc lỗi
        }

        // Ghi log giao dịch ví
        $clsWalletLog = new AffiliateWalletLog();
        $clsWalletLog->insert(array(
            'member_id' => $member_id,
            'amount' => -$amount,
            'type' => 'withdrawal',
            'description' => $description,
            'created_at' => $now
        ));
        return true;
    }

    /**
     * Hoàn lại tiền đã trừ (đảo ngược deductFundsAtomic) — dùng khi tạo lệnh rút thất bại.
     */
    function refundFunds($member_id, $amount, $description = '') {
        global $dbconn;
        $member_id = (int)$member_id;
        $amount = (float)$amount;
        if (!$member_id || $amount <= 0) return false;

        $amt = sprintf('%.2f', $amount);
        $now = date('Y-m-d H:i:s');
        $sql = "UPDATE {$this->tbl} SET balance = balance + {$amt}, total_withdrawn = total_withdrawn - {$amt}, updated_at = '{$now}' WHERE member_id = {$member_id}";
        $dbconn->Execute($sql);

        $clsWalletLog = new AffiliateWalletLog();
        $clsWalletLog->insert(array(
            'member_id' => $member_id,
            'amount' => $amount,
            'type' => 'refund',
            'description' => $description,
            'created_at' => $now
        ));
        return true;
    }
}
