<?php 
return [
    'labels' => [
        'WithdrawOrder'  => 'WithdrawOrder',
        'withdraw-order' => 'WithdrawOrder',
    ],
    'fields' => [
        'parent_id'            => '用戶 ID',
        'bank_id'              => '銀行 ID',
        'phone'                => '電話',
        'book_point'           => '提現點數',
        'payment'              => '提現金額',
        'status'               => '0=審核中 | 1=已轉帳',
        'user_point_before'    => '提現前剩餘點數',
        'user_point_after'     => '提現後剩餘點數',
        // bank_infos 表
        'bankInfo' => [
            'bank_country'     => '國家',
            'bank_swift'       => 'SWIFT BIC 代碼',
            'bank_name'        => '銀行名稱',
            'bank_code'        => '銀行代碼',
            'bank_branch_code' => '銀行分行代碼',
            'bank_branch_addr' => '銀行分行地址',
            'bank_account'     => '銀行賬戶名稱',
            'bank_number'      => '銀行賬戶號碼',
        ],
    ],
    'options' => [
    ],
];
