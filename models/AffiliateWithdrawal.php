<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

class AffiliateWithdrawal extends DbBasic {
    function __construct(){
        $this->pkey = "id";
        $this->tbl = DB_PREFIX."affiliate_withdrawals"; 
    }

    /**
     * Gửi yêu cầu rút tiền: Kiểm tra số dư, khấu trừ ví và tạo yêu cầu rút tiền ở trạng thái 'pending'
     */
    function requestWithdrawal($member_id, $amount, $bank_name, $account_number, $account_holder) {
        $amount = (float)$amount;
        if (!$member_id || $amount <= 0) return array('status' => 'error', 'message' => 'Số tiền rút không hợp lệ.');

        $clsWallet = new AffiliateWallet();

        // Khấu trừ NGUYÊN TỬ: chỉ thành công nếu số dư hiện tại còn đủ (chống double-spend / race condition).
        $description = "Yêu cầu rút tiền về tài khoản ngân hàng " . $bank_name . " - " . $account_number;
        if (!$clsWallet->deductFundsAtomic($member_id, $amount, $description)) {
            return array('status' => 'error', 'message' => 'Số dư ví không đủ để thực hiện yêu cầu rút tiền.');
        }

        // Lưu yêu cầu rút tiền
        $withdrawalData = array(
            'member_id' => $member_id,
            'amount' => $amount,
            'bank_name' => $bank_name,
            'account_number' => $account_number,
            'account_holder' => $account_holder,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        );

        if ($this->insert($withdrawalData)) {
            // Cập nhật account: tăng total_pending
            $clsAffAccount = new AffiliateAccount();
            $account = $clsAffAccount->getAccount($member_id);
            if ($account) {
                $new_pending = (float)$account['total_pending'] + $amount;
                $clsAffAccount->updateOne($account['id'], array(
                    'total_pending' => $new_pending
                ));
            }
            return array('status' => 'success', 'message' => 'Gửi yêu cầu rút tiền thành công.');
        }

        // Tạo lệnh rút thất bại SAU khi đã trừ tiền → hoàn lại để không mất tiền của user.
        $clsWallet->refundFunds($member_id, $amount, 'Hoàn tiền do tạo yêu cầu rút thất bại');
        return array('status' => 'error', 'message' => 'Có lỗi xảy ra trong quá trình xử lý yêu cầu rút tiền.');
    }

    /**
     * Phê duyệt yêu cầu rút tiền: Cập nhật tài khoản (giảm total_pending, tăng total_paid)
     */
    function approveWithdrawal($withdrawal_id, $note = '') {
        $withdrawal = $this->getOne($withdrawal_id);
        if (empty($withdrawal) || $withdrawal['status'] !== 'pending') return false;

        $member_id = $withdrawal['member_id'];
        $amount = (float)$withdrawal['amount'];

        $updateData = array(
            'status' => 'approved',
            'note' => $note,
            'processed_at' => date('Y-m-d H:i:s')
        );

        if ($this->updateOne($withdrawal_id, $updateData)) {
            // Cập nhật account: giảm total_pending, tăng total_paid
            $clsAffAccount = new AffiliateAccount();
            $account = $clsAffAccount->getAccount($member_id);
            if ($account) {
                $new_pending = max(0, (float)$account['total_pending'] - $amount);
                $new_paid = (float)$account['total_paid'] + $amount;
                $clsAffAccount->updateOne($account['id'], array(
                    'total_pending' => $new_pending,
                    'total_paid' => $new_paid
                ));
            }
            return true;
        }
        return false;
    }

    /**
     * Từ chối yêu cầu rút tiền: Hoàn lại tiền vào ví khả dụng và cập nhật tài khoản
     */
    function rejectWithdrawal($withdrawal_id, $note = '') {
        $withdrawal = $this->getOne($withdrawal_id);
        if (empty($withdrawal) || $withdrawal['status'] !== 'pending') return false;

        $member_id = $withdrawal['member_id'];
        $amount = (float)$withdrawal['amount'];

        $updateData = array(
            'status' => 'rejected',
            'note' => $note,
            'processed_at' => date('Y-m-d H:i:s')
        );

        if ($this->updateOne($withdrawal_id, $updateData)) {
            // Hoàn trả lại số dư ví khả dụng
            $clsWallet = new AffiliateWallet();
            $wallet = $clsWallet->getWallet($member_id);
            if (!empty($wallet)) {
                $new_balance = (float)$wallet['balance'] + $amount;
                $new_withdrawn = max(0, (float)$wallet['total_withdrawn'] - $amount);

                $clsWallet->updateOne($wallet['id'], array(
                    'balance' => $new_balance,
                    'total_withdrawn' => $new_withdrawn,
                    'updated_at' => date('Y-m-d H:i:s')
                ));

                // Log hoàn tiền vào ví
                $clsWalletLog = new AffiliateWalletLog();
                $logData = array(
                    'member_id' => $member_id,
                    'amount' => $amount,
                    'type' => 'refund',
                    'description' => "Hoàn trả tiền từ yêu cầu rút tiền bị từ chối: " . $note,
                    'created_at' => date('Y-m-d H:i:s')
                );
                $clsWalletLog->insert($logData);
            }

            // Cập nhật account: giảm total_pending
            $clsAffAccount = new AffiliateAccount();
            $account = $clsAffAccount->getAccount($member_id);
            if ($account) {
                $new_pending = max(0, (float)$account['total_pending'] - $amount);
                $clsAffAccount->updateOne($account['id'], array(
                    'total_pending' => $new_pending
                ));
            }
            return true;
        }
        return false;
    }
}
