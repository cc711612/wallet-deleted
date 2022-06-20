<?php

namespace App\Concerns\Databases\Contracts\Constants;

/**
 * Interface Display
 *
 * @package App\Databases\Contracts
 * @Author  : daniel
 * @DateTime: 2019-02-18 15:52
 */
interface Chart
{
    //說書
    const CHART_BOOK = 'book';
    //課程
    const CHART_COURSE = 'course';
    //持續力
    const CHART_CONTINUE   = 'continue';
    //完成度
    const CHART_COMPLETE   = 'complete';
    //知識分布
    const CHART_KNOWLEDGE   = 'knowledge';
    //觀看時間
    const CHART_VIEW   = 'view';
    //打卡次數
    const CHART_LOGIN   = 'login';
    //觀看狀況
    const CHART_STATUS   = 'status';
    //觀看進度
    const CHART_PROGRESS   = 'progress';
    //部門
    const CHART_DEPARTMENT   = 'department';
    //會員
    const CHART_MEMBER   = 'member';
}
