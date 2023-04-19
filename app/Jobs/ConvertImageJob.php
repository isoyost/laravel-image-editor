<?php

namespace App\Jobs;

use App\Models\Image;
use App\Services\StatisticsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image as InterventionImage;

class ConvertImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly Image $image,
        private readonly string $format
    ) {
    }

    public function handle(StatisticsService $statistics): void
    {
        $image = InterventionImage::make($this->image->getFirstMedia()->getPath())->encode($this->format);
        $this->image->addMedia(
            $image->filename
        )->toMediaCollection();
        $statistics->incrementSizeForFormat($image->mime, $image->filesize());
    }
}
