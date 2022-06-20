<?php

namespace App\Concerns\Databases\Contracts\Constants;

/**付款方式
 * Interface PaymentType
 *
 * @package App\Databases\Contracts
 * @Author  : hsin
 * @DateTime: 2019/3/8 下午 6:58
 */
interface PaymentType
{
    //未指定
    const DEFAULT           = 0;
    //貨到付款
    const COD               = 1;
    //智付通-信用卡
    const SPGATEWAY_CREDIT  = 11;
    //智付通-ATM
    const SPGATEWAY_VACC    = 12;
    //智付通-超商代碼
    const SPGATEWAY_CVS     = 13;
    //智付通-信用卡三期
    const SPGATEWAY_INST_3  = 14;
    //智付通-信用卡六期
    const SPGATEWAY_INST_6  = 15;
    //智付通-信用卡十二期
    const SPGATEWAY_INST_12 = 16;
    //綠界-信用卡
    const ECPAY_CREDIT      = 51;
    //綠界-ATM
    const ECPAY_VACC        = 52;
    //綠界-超商代碼
    const ECPAY_CVS         = 53;
    //綠界-信用卡三期
    const ECPAY_INST_3      = 54;
    //綠界-信用卡六期
    const ECPAY_INST_6      = 55;
    //綠界-信用卡十二期
    const ECPAY_INST_12     = 56;
    //綠界-超商取貨付款
    const ECPAY_CVS_COD     = 57;
}