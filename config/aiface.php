<?php

return [

    /**
     * 订单价格
     */
    'order_price' => 2,

    /**
     * 分享佣金返利金额
     */
    'share_commission_price' => [
        'default' => 1,

        'level' => [
            1 => 0.2,
            2 => 0.4,
            3 => 0.6,
            4 => 0.8,
            5 => 1.0,
        ],
    ],

    /**
     * 允许api调用失败的最大次数
     */
    'max_api_error_count' => 15,



];



























