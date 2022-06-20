<?php
namespace App\Concerns\Commons\Abstracts;

use App\Concerns\Databases\Contracts\Request;
use Validator;

/**
 * Class ValidatorAbstracts
 *
 * @package App\Concerns\Commons\Abstracts
 * @Author  : steatng
 * @DateTime: 2020/5/11 16:22
 */
abstract class ValidatorAbstracts
{
    /**
     * @var
     * @Author  : steatng
     * @DateTime: 2020/5/11 16:23
     */
    protected $request;

    /**
     * ValidatorAbstracts constructor.
     *
     * @param Request $request
     *
     * @Author  : steatng
     * @DateTime: 2020/5/11 16:27
     */
    abstract public function __construct(Request $request);

    /**
     * @return array
     * @Author  : steatng
     * @DateTime: 2020/5/11 16:27
     */
    abstract protected function rules() :array ;

    /**
     * @return array
     * @Author  : steatng
     * @DateTime: 2020/5/11 16:26
     */
    protected function messages(): array {
        return [];
    }

    private function getRequest():Request
    {
        return $this->request;
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     * @Author  : steatng
     * @DateTime: 2020/5/11 16:25
     */
    public function validate(): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make(
            $this->getRequest()->toArray(),
            $this->rules(),
            $this->messages()
        );
    }
}
