<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class StatisticsService
{
    public function __construct(protected readonly Redis $redis)
    {
    }

    public function incrementSizeForFormat(string $format, int $size): void
    {
        $fieldName = "statistics:max-size:$format";
        $oldSize = Redis::get($fieldName);
        Redis::set($fieldName, $oldSize + $size);
    }
}
