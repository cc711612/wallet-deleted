<?php
/**
 * @Author  : steatng
 * @DateTime: 2020/5/13 17:26
 */

namespace App\Concerns\Commons\Abstracts;

use App\Concerns\Databases\Contracts\Constants\Status;
use Illuminate\Support\Arr;

/**
 * Class SupportAbstracts
 *
 * @package App\Concerns\Commons\Abstracts
 * @Author  : steatng
 * @DateTime: 2020/5/13 17:47
 */
abstract class SupportAbstracts
{

    /**
     * @return array|array[]
     * @Author  : daniel
     * @DateTime: 2020/7/16 11:09 上午
     */
    private static function statusRadio(): array
    {
        return [
            null                   => [
                "name"         => "status",
                "value"        => null,
                "text"         => "不指定",
                "status_text"  => "不指定",
                "contact_text" => "不指定",
                "span_class"   => "",
                "font_class"   => "",
                "checked"      => "",
                "selected"     => "",
                "icon_class"   => "",
                "button_class" => "",
            ],
            Status::STATUS_ENABLE  => [
                "name"         => "status",
                "value"        => Status::STATUS_ENABLE,
                "text"         => "上架",
                "status_text"  => "上架",
                "contact_text" => "已處理",
                "span_class"   => "label-success",
                "font_class"   => "font-green",
                "checked"      => "",
                "selected"     => "",
                "icon_class"   => "icon-check",
                "button_class" => "green",
            ],
            Status::STATUS_DISABLE => [
                "name"         => "status",
                "value"        => Status::STATUS_DISABLE,
                "text"         => "下架",
                "status_text"  => "下架",
                "contact_text" => "未處理",
                "span_class"   => "label-warning",
                "font_class"   => "font-red",
                "checked"      => "",
                "selected"     => "",
                "icon_class"   => "icon-close",
                "button_class" => "red",
            ],
            Status::STATUS_DRAFT   => [
                "name"         => "status",
                "value"        => Status::STATUS_DRAFT,
                "text"         => "處理中",
                "status_text"  => "處理中",
                "contact_text" => "處理中",
                "span_class"   => "label-info",
                "font_class"   => "font-yellow",
                "checked"      => "",
                "selected"     => "",
                "icon_class"   => "icon-call-out",
                "button_class" => "yellow",
            ],
//            Status::STATUS_CRON => [
//                "name"       => "status",
//                "value"      => Status::STATUS_CRON,
//                "text"       => "期間限定",
//                "span_class" => "label-info",
//                "font_class" => "font-red",
//                "checked"    => "",
//                "icon_class" => "icon-calendar",
//            ],
        ];
    }

    /**
     * @return array
     * @Author  : steatng
     * @DateTime: 2020/5/13 17:47
     */
    private static function enableDisableRadio(): array
    {
        $Result = self::statusRadio();

        unset($Result[Status::STATUS_CRON], $Result[null], $Result[Status::STATUS_DRAFT]);

        return $Result;
    }

    /**
     * @return array[]
     * @Author: Roy
     * @DateTime: 2021/5/27 上午 11:52
     */
    private static function enableDisableDraftTRadio(): array
    {
        $Result = self::statusRadio();
        unset($Result[Status::STATUS_CRON], $Result[null]);

        return $Result;
    }
    /**
     * @param  int  $status
     *
     * @return array
     * @Author  : steatng
     * @DateTime: 2020/5/13 17:47
     */
    public static function getEnableDisableRadio($status = Status::STATUS_DISABLE): array
    {
        $radio = self::enableDisableRadio();

        if (empty($radio[$status])) {
            return $radio;
        }

        Arr::set($radio,
            str_replace("{status}", $status, "{status}.checked"),
            "checked"
        );
        return $radio;
    }
    public static function getEnableDisableDraftRadio($status = Status::STATUS_DISABLE): array
    {
        $radio = self::enableDisableDraftTRadio();

        if (empty($radio[$status])) {
            return $radio;
        }

        Arr::set($radio,
            str_replace("{status}", $status, "{status}.checked"),
            "checked"
        );
        return $radio;
    }

    /**
     * @param  int  $status
     *
     * @return array
     * @Author  : steatng
     * @DateTime: 2020/5/13 17:47
     */
    public static function getEnableDisableSelect(int $status = null): array
    {
        $radio = self::statusRadio();
        unset($radio[Status::STATUS_CRON], $radio[Status::STATUS_DRAFT]);

        if (empty($radio[$status])) {
            return $radio;
        }

        Arr::set($radio,
            str_replace("{status}", $status, "{status}.selected"),
            "selected"
        );
        return $radio;
    }

    /**
     * @param  int  $status
     *
     * @return array
     * @Author: Roy
     * @DateTime: 2021/6/17 下午 03:29
     */
    public static function getEnableDisableChecked(int $status): array
    {
        $radio =self::enableDisableRadio();

        if($status == Status::STATUS_ENABLE){
            Arr::set($radio,
                str_replace("{status}", $status, "{status}.checked"),
                "checked"
            );
            Arr::set($radio,
                str_replace("{status}", $status, "{status}.selected"),
                "selected"
            );
        }

        return Arr::get($radio,$status);
    }

    /**
     * @param  int  $status
     *
     * @return array
     * @Author  : steatng
     * @DateTime: 2020/5/13 17:47
     */
    public static function getStatusRadio(int $status = null): array
    {
        $radio = self::statusRadio();

        if (empty($radio[$status])) {
            return $radio;
        }

        Arr::set($radio,
            str_replace("{status}", $status, "{status}.checked"),
            "checked"
        );
        Arr::set($radio,
            str_replace("{status}", $status, "{status}.selected"),
            "selected"
        );
        return $radio;
    }

    /**
     * @param  int  $status
     *
     * @return array
     * @Author  : daniel
     * @DateTime: 2020-05-27 14:46
     */
    public static function getStatusRadioChecked(int $status): array
    {
        return Arr::get(self::statusRadio(), $status);
    }

    /**
     * @param  array  $Selected
     * @param  array  $SelectOption
     * @param  array|null  $Option
     *
     * @return array
     * @Author  : daniel
     * @DateTime: 2020-05-19 16:59
     */
    public static function getMultipleSelected(array $Selected, array $SelectOption, array $Option = null)
    {
        $SelectValue = '';
        $Text = '';
        //額外參數設定
        if (isset($Option)) {
            foreach ($Option as $Key => $Value) {
                switch ($Key) {
                    //設定Select的Value欄位
                    case 'select_value':
                        $SelectValue = $Value;
                        break;
                    case 'text':
                        $Text = $Value;
                        break;
                }
            }
        }

        foreach ($SelectOption as $Key => $Rows) {
            $SelectOption[$Key]['value'] = ($SelectValue) ? $Rows[$SelectValue] : $Key;
            $SelectOption[$Key]['text'] = ($Text) ? $Rows[$Text] : $Key;
            $SelectOption[$Key]['selected'] = in_array($SelectOption[$Key]['value'], $Selected) ? 'selected' : '';
            $SelectOption[$Key]['checked'] = in_array($SelectOption[$Key]['value'], $Selected) ? 'checked' : '';
        }

        return $SelectOption;
    }

    /**
     * @param        $seconds
     * @param  string  $format
     *
     * @return bool|string
     * @Author  : daniel
     * @DateTime: 2020/7/16 11:09 上午
     */
    public static function getMinutesSecond($seconds, $format = '%02d:%02d')
    {
        if (empty($seconds) || !is_numeric($seconds)) {
            return '0 mins';
        }

        $minutes = floor($seconds / 60);
        $second = ($seconds % 60);

        return sprintf($format, $minutes, $second);
    }

    /**
     * @param        $seconds
     * @param string $format
     * @param bool   $keep_format
     *
     * @return string
     * @Author  : daniel
     * @DateTime: 2021/6/16 上午9:34
     */
    public static function getHourMinutesSecond($seconds, $format = '%02d:%02d:%02d',$keep_format = false)
    {
        if (empty($seconds) || !is_numeric($seconds)) {
            if ($keep_format === true) {
                return sprintf($format, 0, 0, 0);
            }
            if ($format == '%02d:%02d:%02d') {
                return '--';
            }
            return '0 mins';
        }

        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        $second = ($seconds % 60);
        $minutes = ($minutes % 60);

        return sprintf($format, $hours, $minutes, $second);
    }
}
