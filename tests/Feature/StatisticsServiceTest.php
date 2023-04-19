<?php

namespace Tests\Feature;

use App\Services\StatisticsService;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

final class StatisticsServiceTest extends TestCase
{
    /**
     * @dataProvider incrementSizeForFormat
     */
    public function testIncrementSizeForFormat(string $format, int $oldSize, int $added): void
    {
        $this->withoutExceptionHandling();
        $key = "statistics:size:$format";
        Redis::partialMock()->shouldReceive('get')->with($key)->andReturn($oldSize)->once();
        Redis::partialMock()->shouldReceive('set')->with($key, $added)->once();

        $service = $this->app->make(StatisticsService::class);

        $service->incrementSizeForFormat($format, $added);
    }

    public function incrementSizeForFormat(): array
    {
        return [
            [
                'format' => 'image/png',
                'oldSize' => 100,
                'added' => 20,
            ],
            [
                'format' => 'image/jpeg',
                'oldSize' => 1200,
                'added' => 80,
            ],
        ];
    }
}
