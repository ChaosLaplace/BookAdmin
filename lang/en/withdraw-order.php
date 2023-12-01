<?php 
return [
    'labels' => [
        'WithdrawOrder' => 'WithdrawOrder',
        'withdraw-order' => 'WithdrawOrder',
    ],
    'fields' => [
        'parent_id' => '用戶 ID',
        'bank_id' => '銀行 ID',
        'phone' => '電話',
        'book_point' => '提現點數',
        'payment' => '提現金額',
        'status' => '0=審核中 | 1=已轉帳',
        'user_point_before' => '提現前剩餘點數',
        'user_point_after' => '提現後剩餘點數',
    ],
    'options' => [
    ],
];
