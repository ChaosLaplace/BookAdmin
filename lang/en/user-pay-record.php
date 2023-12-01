<?php 
return [
    'labels' => [
        'UserPayRecord' => 'UserPayRecord',
        'user-pay-record' => 'UserPayRecord',
    ],
    'fields' => [
        'user_id' => '用戶 ID',
        'user_order_no' => '三方訂單號',
        'user_point' => '購買的點數',
        'user_payment' => '購買的價格',
        'user_payment_firm' => '金流廠商',
        'user_payment_status' => '狀態 0=未付款 | 1=已付款 | 2=失敗',
        'user_point_before' => '付款前點數',
        'user_point_after' => '付款後點數',
    ],
    'options' => [
    ],
];
