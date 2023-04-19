<?php

namespace Tests\Feature;

use App\Jobs\ResizeImageJob;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

final class ImageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex(): void
    {
        $imageCount = 3;
        Image::factory($imageCount)->create();

        $response = $this->get('/api/images');

        $response
            ->assertJsonCount($imageCount)
            ->assertOk();
    }

    public function testStore(): void
    {
        $file = UploadedFile::fake()->image('image.png');

        $response = $this->post('/api/images', [
            'file' => $file
        ]);

        $response->assertCreated();
        $this->assertDatabaseCount('images', 1);
        $this->assertDatabaseCount('media', 1);
    }

    public function testShow(): void
    {
        $image = Image::factory()->create();

        $response = $this->get("/api/images/$image->id");

        $response
            ->assertJson($image->toArray())
            ->assertOk();
    }

    public function testUpdate(): void
    {
        $this->withoutExceptionHandling();
        Bus::fake();
        $image = Image::factory()->create();
        $image->addMedia(UploadedFile::fake()->image('image.png'))->toMediaCollection();

        $response = $this->patch("/api/images/$image->id", [
            'resize_to' => [
                'width' => 10,
                'height' => 10,
            ]
        ]);

        $response->assertStatus(202);
        Bus::assertChained([
            new ResizeImageJob($image, 10, 10),
        ]);
    }
}
