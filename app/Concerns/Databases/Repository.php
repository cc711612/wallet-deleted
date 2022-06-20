<?php

namespace App\Concerns\Databases;

use App\Concerns\Databases\Contracts\Relation;
use App\Concerns\Databases\Contracts\Repository as RepositoryContracts;
use Illuminate\Support\Collection;

/**
 * Class Repository
 *
 * @package App\Databases
 * @Author  : daniel
 * @DateTime: 2020-05-08 14:43
 */
abstract class Repository implements RepositoryContracts
{
    /**
     * @var array
     * @Author  : daniel
     * @DateTime: 2020/9/29 11:18 上午
     */
    protected $Request = [];

    /**
     * @var array
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    private $Relation = [];

    /**
     * @var array
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    private $Select = ['*'];

    /**
     * @var
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    protected $entity_class_name;

    /**
     * @var array
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    private $order_by = [];

    /**
     * @var
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    private $order_by_raw;

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    abstract public function getBuilder();

    /**
     * @param array $Request
     *
     * @return $this
     * @Author  : daniel
     * @DateTime: 2020/9/29 11:18 上午
     */
    public function setRequest(array $Request) : RepositoryContracts
    {
        $this->Request = $Request;
        return $this;
    }

    /**
     * @return array
     * @Author  : daniel
     * @DateTime: 2021/5/20 下午3:30
     */
    public function getRequest() : array
    {
        return $this->Request;
    }

    /**
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    public function getOrderByRaw()
    {
        return $this->order_by_raw;
    }

    /**
     * @param string $order_by_raw
     *
     * @return \App\Concerns\Databases\Contracts\Repository
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    public function setOrderByRaw(string $order_by_raw):RepositoryContracts
    {
        $this->order_by_raw = $order_by_raw;
        return $this;
    }

    /**
     * @return array
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    public function getOrderBy() : array
    {
        return $this->order_by;
    }

    /**
     * @param array $order_by
     *
     * @return \App\Concerns\Databases\Contracts\Repository
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    public function setOrderBy(array $order_by):RepositoryContracts
    {
        $this->order_by = $order_by;
        return $this;
    }


    /**
     * @return array
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    public function getRelation():array
    {
        return $this->Relation;
    }

    /**
     * @param array $Relation
     *
     * @return \App\Concerns\Databases\Contracts\Repository
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    public function setRelation(array $Relation):RepositoryContracts
    {
        $this->Relation = $Relation;
        return $this;
    }

    /**
     * @return array
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    public function getSelect(): array
    {
        return $this->Select;
    }


    /**
     * @param array $Select
     *
     * @return \App\Concerns\Databases\Contracts\Repository
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    public function setSelect(array $Select) : RepositoryContracts
    {
        $this->Select = $Select;
        return $this;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @throws \ReflectionException
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    protected function getFinalBuilder()
    {
        //取得 Builder
        $Builder = $this->getEntity()->select($this->getSelect());

//        if ($this instanceof RootStatus) {
//            $Builder = $Builder->RootStatus();
//        }

        if($this->getOrderBy()){
            foreach ($this->getOrderBy() as $filed => $sort){
                $Builder = $Builder->orderBy($filed,$sort);
            }
        }

        if($this->getOrderByRaw()){
            $Builder = $Builder->orderByRaw($this->getOrderByRaw());
        }

        //綁定關聯
        foreach ($this->getRelation() as $relation_class_name => $Closure) {

            $class = new \ReflectionClass($relation_class_name);
            $RelationClass = $class->newInstanceArgs([$Builder,$Closure]);

            if ($RelationClass instanceof Relation) {
                $Builder = $RelationClass->getBuilder();
            }
        }

        return $Builder;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsToMany|mixed
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    public function getEntity()
    {
        if (app()->has($this->entity_class_name) === false) {
            app()->singleton($this->entity_class_name);
        }

        return app($this->entity_class_name);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|mixed|null
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    public function last()
    {
        return $this->get()->last();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed|object|null
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    public function first()
    {
        return $this->getBuilder()->first();
    }

    /**
     * @return \Illuminate\Support\Collection
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    public function get():Collection
    {
        return $this->getBuilder()->get();
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed|null
     * @Author  : daniel
     * @DateTime: 2020-05-08 14:43
     */
    public function find(int $id)
    {
        return $this->getBuilder()->find($id);
    }
}
