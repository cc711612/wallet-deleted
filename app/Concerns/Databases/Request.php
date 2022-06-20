<?php

namespace App\Concerns\Databases;

use App\Concerns\Databases\Contracts\Request as RequestContract;
use ArrayAccess;
use Arr;
use Illuminate\Support\Collection;

/**
 * Class Request
 *
 * @package App\Elasticsearches
 * @Author  : boday
 * @DateTime: 2018-12-28 13:38
 */
abstract class Request implements ArrayAccess, RequestContract
{
    /**
     * @var array
     * @Author  : boday
     * @DateTime: 2018-12-28 16:00
     */
    protected $attributes;
    /**
     * @var array
     * @Author  : boday
     * @DateTime: 2018-12-28 16:39
     */
    protected $schema;
    /**
     * @var array
     * @Author  : boday
     * @DateTime: 2018-12-28 08:43
     */
    protected $sources;
    /**
     * @var \Illuminate\Support\Collection
     * @Author  : boday
     * @DateTime: 2018-12-28 15:51
     */
    private $collection;

    /**
     * @var bool
     * @Author  : boday
     * @DateTime: 2019-01-14 11:11
     */
    private $is_collection = false;

    /**
     * Request constructor.
     *
     * @param $sources
     *
     * @Author  : boday
     * @DateTime: 2018-12-28 13:38
     */
    public function __construct($sources)
    {
        // 如果來源是
        if ($sources instanceof Collection) {
            $this->collection($sources);
            return;
        }

        $this->sources = $sources;
        $this->initialization();
    }

    /**
     * @param \Illuminate\Support\Collection $collection
     *
     * @Author  : boday
     * @DateTime: 2018-12-28 16:30
     */
    private function collection(Collection $collection)
    {
        $this->is_collection = true;
        $this->collection = $collection->map(function ($item) {
            return new static($item);
        });
    }

    /**
     * @Author  : boday
     * @DateTime: 2018-12-28 16:44
     */
    private function initialization()
    {
        $this->schema = $this->schema();
        foreach ($this->solveAttribute() as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * 定義主要的結構與預設授與的資料
     *
     * @return array
     * @Author  : boday
     * @DateTime: 2018-12-28 13:38
     */
    abstract protected function schema(): array;

    /**
     * @return array
     * @Author  : boday
     * @DateTime: 2018-12-28 19:22
     */
    private function solveAttribute(): array
    {
        return $this->array_merge_default($this->map($this->sources), $this->schema);
    }

    /**
     * @param $row
     *
     * @return array
     * @Author  : boday
     * @DateTime: 2018-12-28 17:13
     */
    abstract protected function map($row): array;

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return array
     */
    public function __set($key, $value): array
    {
        return $this->setAttribute($key, $value);
    }

    /**
     * @param string $key
     *
     * @return mixed
     * @Author  : boday
     * @DateTime: 2018-12-25 15:20
     */
    protected function getAttribute(string $key)
    {
        return Arr::get($this->attributes, $key);
    }

    /**
     * @param string $key
     * @param        $value
     *
     * @return array
     * @Author  : boday
     * @DateTime: 2018-12-25 15:54
     */
    protected function setAttribute(string $key, $value): array
    {
        return Arr::set($this->attributes, $key, $value);
    }

    /**
     * Determine if an attribute or relation exists on the model.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->getAttribute($offset) !== null;
    }

    /**
     * Unset an attribute on the model.
     *
     * @param string $key
     *
     * @return void
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * Unset the value for a given offset.
     *
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    /**
     * @return bool
     * @Author  : boday
     * @DateTime: 2019-01-14 11:11
     */
    public function isCollection(): bool
    {
        return $this->is_collection;
    }

    /**
     * Get the value for a given offset.
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    /**
     * Set the value for a given offset.
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @return array
     */
    public function offsetSet($offset, $value): array
    {
        return $this->setAttribute($offset, $value);
    }

    /**
     * @return array
     * @Author  : boday
     * @DateTime: 2018-12-28 21:18
     */
    public function toArray(): array
    {
        if ($this->collection instanceof Collection) {
            return $this->collection->map(function (RequestContract $Request) {
                return $Request->toArray();
            })->toArray();
        }
        return $this->attributes;
    }

    /**
     * @param $Hml
     *
     * @return string
     * @Author  : daniel
     * @DateTime: 2020-06-01 09:25
     */
    protected function handleTinyMceContent(string $Hml): string
    {
        return preg_replace('/(\.\.\/)+/i', "/", tidy_repair_string($Hml,
            [
                "output-xhtml"     => true,
                "drop-empty-paras" => false,
                "join-classes"     => true,
                "show-body-only"   => true,
            ], "utf8"));

    }
    function array_merge_default(array $sources, array $defaults): array
    {
        return array_replace($defaults, array_intersect_key(
                array_filter($sources, function ($value) {
                    return !is_null($value);
                }),
                $defaults)
        );
    }
}
