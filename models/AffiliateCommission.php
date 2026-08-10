<?php if (!defined('ABSPATH')) exit('No direct script access allowed');

class AffiliateCommission extends DbBasic {
    function __construct(){
        $this->pkey = "id";
        $this->tbl = DB_PREFIX."affiliate_commissions"; 
    }

    /**
     * Tính 2% hoa hồng giới thiệu khi đơn hàng hoàn thành thanh toán.
     */
    function calculateCommission($order_id) {
        if (!$order_id) return false;

        $clsOrder = new Order();
        $order = $clsOrder->getOne($order_id);
        if (empty($order)) return false;

        // Chỉ tính hoa hồng cho đơn hàng ở trạng thái đã thanh toán (status = 1)
        if ((int)$order['status'] !== 1) return false;

        // Tránh tính hoa hồng trùng lặp cho cùng một đơn hàng
        $exists = $this->countItem("order_id = '{$order_id}'");
        if ($exists > 0) return false;

        $buyer_id = $order['profile_id'];
        if (!$buyer_id) return false;

        // Tìm người giới thiệu (F0)
        $clsAffiliateReferral = new AffiliateReferral();
        $referral = $clsAffiliateReferral->getByCond("referred_member_id = '{$buyer_id}' LIMIT 1");
        if (empty($referral)) return false;

        $referrer_id = $referral['referrer_member_id'];

        // Tính tiền commission: 2% của tổng số tiền đơn hàng
        // Lấy số tiền từ vpc_total_money, nếu không có thì trích xuất từ more_information
        $total_amount = 0;
        if (isset($order['vpc_total_money']) && $order['vpc_total_money'] > 0) {
            $total_amount = (float)$order['vpc_total_money'];
        } elseif (!empty($order['more_information'])) {
            $clsISO = new ISO();
            $more_info = $clsISO->to_array_json($order['more_information']);
            if (isset($order['time_package']) && isset($more_info[$order['time_package']])) {
                $total_amount = (float)$more_info[$order['time_package']];
            } elseif (isset($more_info['response']['data']['amount'])) {
                $total_amount = (float)$more_info['response']['data']['amount'];
            } elseif (isset($more_info['amount'])) {
                $total_amount = (float)$more_info['amount'];
            }
        }

        if ($total_amount <= 0) return false;

        $commission_amount = $total_amount * 0.02;

        // Lưu log hoa hồng
        $commissionData = array(
            'member_id' => $referrer_id,
            'referral_id' => $referral['id'],
            'order_id' => $order_id,
            'commission_amount' => $commission_amount,
            'status' => 'paid', // Đánh dấu paid để tương thích với query hiển thị
            'created_at' => date('Y-m-d H:i:s')
        );

        if ($this->insert($commissionData)) {
            // Cập nhật ví và thống kê tài khoản của F0
            $clsAffiliateWallet = new AffiliateWallet();
            $order_code = isset($order['vpc_OrderNo']) && $order['vpc_OrderNo'] ? $order['vpc_OrderNo'] : $order_id;
            $description = "Nhận 2% hoa hồng giới thiệu từ đơn hàng MOC" . $order_code . " của thành viên giới thiệu.";
            $clsAffiliateWallet->addFunds($referrer_id, $commission_amount, $description);

            // Cập nhật tổng hoa hồng trong default_affiliate_accounts
            $clsAffAccount = new AffiliateAccount();
            $referrer_account = $clsAffAccount->getAccount($referrer_id);
            if ($referrer_account) {
                $new_total_commission = (float)$referrer_account['total_commission'] + $commission_amount;
                $clsAffAccount->updateOne($referrer_account['id'], array(
                    'total_commission' => $new_total_commission
                ));
            }
            return true;
        }
        return false;
    }
}
