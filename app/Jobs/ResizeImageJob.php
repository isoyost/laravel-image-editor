<?php

namespace App\Jobs;

use App\Models\Image;
use App\Services\StatisticsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as InterventionImage;

class ResizeImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly Image $image,
        private readonly int $width,
        private readonly int $height
    ) {
    }

    /**
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     */
    public function handle(StatisticsService $statistics): void
    {
        $image = InterventionImage::make($this->image->getFirstMedia()->getPath())->resize($this->width, $this->height);
        $this->image->addMedia(
            $image->filename
        )->toMediaCollection();
        $statistics->incrementSizeForFormat($image->mime, $image->filesize());
    }
}
