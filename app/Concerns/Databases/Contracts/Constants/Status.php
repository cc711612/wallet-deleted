<?php

namespace App\Concerns\Databases\Contracts\Constants;

/**
 * Interface Display
 *
 * @package App\Databases\Contracts
 * @Author  : daniel
 * @DateTime: 2019-02-18 15:52
 */
interface Status
{
    //封存
    const STATUS_ARCHIVE = 101;
    //未啟用
    const STATUS_DISABLE = 201;
    //草稿
    const STATUS_DRAFT   = 202;
    //啟用
    const STATUS_ENABLE  = 301;
    //區間內啟用
    const STATUS_CRON    = 302;
}