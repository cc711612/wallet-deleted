<?php

namespace App\Concerns\Databases\Contracts\Services;

/**
 * Interface Service
 *
 * @package App\Databases\Contracts
 * @Author  : daniel
 * @DateTime: 2019-03-13 10:27
 */
interface ServiceV2
{
    public static function getInstance(): self;

    public function setPageCount(int $page_count): self;

    public function getPageCount(): int;
}
