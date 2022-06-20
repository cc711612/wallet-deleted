<?php
namespace App\Concerns\Commons\Abstracts;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Class PresenterAbstract
 *
 * @package App\Concerns\Commons\Abstracts
 * @Author  : daniel
 * @DateTime: 2020-05-08 17:41
 */
class PresenterAbstract
{
    /**
     * @var
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:41
     */
    private $_data;
    /**
     * @var
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:41
     */
    private $_resource;
    /**
     * @param $name
     * @param $resource
     *
     * @return $this
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:41
     */
    public function setResource($name, $resource)
    {
        $this->_resource[$name] = $resource;
        return $this;
    }
    /**
     * @param string $name
     *
     * @return  |null
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:41
     */
    protected function getResource(string $name)
    {
        if (isset($this->_resource[$name])) {
            return $this->_resource[$name];
        } else {
            return null;
        }
    }
    /**
     * @param string $name
     * @param        $value
     *
     * @return $this
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:41
     */
    public function put(string $name, $value)
    {
        data_set($this->_data, $name, $value);
        return $this;
    }
    /**
     * @param string $name
     *
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:41
     */
    public function get(string $name)
    {
        return data_get($this->_data, $name);
    }
    /**
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:41
     */
    public function all()
    {
        return json_decode(json_encode($this->_data));
    }
    /**
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:41
     */
    public function toArray()
    {
        return $this->_data;
    }

//    public function handlePageAddOn()
    //    {
    //        $PageAddOn = $this->getResource('PageAddOn');
    //        $UsedAddOn = $this->getResource('UsedAddOn');
    //
    //        if (empty($PageAddOn)) {
    //            return;
    //        }
    //
    //        $result = [];
    //        foreach ($PageAddOn as $inx => $add_on_id_list) {
    //            // 空陣列要保留, 以免報錯
    //            $result[$inx] = [];
    //            foreach ($add_on_id_list as $add_on_id) {
    //                $result[$inx][$add_on_id] = $UsedAddOn[$add_on_id];
    //            }
    //        }
    //        return $result;
    //    }
    /**
     * @param $text
     *
     * @return mixed|string|string[]|null
     * @Author  : daniel
     * @DateTime: 2020-05-08 17:41
     */
    public function handleMetaDescription($text)
    {
        $text = str_replace('&nbsp;', '', $text);
        //移除前後空白字
        $text = trim($text);
        //移除重覆的空白
        $text = preg_replace('/\s/', '', $text);
        //移除非空白的間距變成一般的空白
        $text = preg_replace('/[\n\r\t]/', ' ', $text);
        //移除Unicode全形空白
        $text = preg_replace('/\x{3000}/u', '', $text);

        $text = strip_tags($text, '');
        $text = str_limit($text, 150);
        return $text;
    }

    /**
     * 如果時間是空的回傳 -
     *
     * @param $time
     *
     * @return string
     * @Author  : steatng
     * @DateTime: 2020/6/1 16:29
     */
    public function handleEmptyTime($time)
    {
        return (empty($time)) ? "-" : $time->toDateTimeString();
    }

    public function handleApiPageInfo(LengthAwarePaginator $collection) : array
    {
        $format = [
            'current_page',
            'last_page',
            'per_page',
            'total'
        ];

        return Arr::only($collection->toArray(), $format);
    }

    public function handleApiImage($path = null, $size = null)
    {
        if(empty($path)){
            return getDefaultImage($size);
        }

        return secure_asset($path);
    }
}
