<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Jobs\ConvertImageJob;
use App\Jobs\ResizeImageJob;
use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

final class ImagesController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->response(Image::get());
    }

    public function store(StoreImageRequest $request): JsonResponse
    {
        $image = DB::transaction(function () use ($request) {
            $image = Image::create();
            $image
                ->addMedia($request->file('file'))
                ->toMediaCollection();

            return $image;
        });

        return $this->response($image, 201);
    }

    public function show(Image $image): JsonResponse
    {
        return $this->response($image);
    }

    public function update(UpdateImageRequest $request, Image $image, Bus $bus): JsonResponse
    {
        $jobs = [];
        if ($request->has('resize_to')) {
            $jobs[] = new ResizeImageJob($image, $request->integer('resize_to.width'), $request->integer('resize_to.height'));
        }

        if ($request->has('convert_to')) {
            $jobs[] = new ConvertImageJob($image, $request->input('convert_to'));
        }

        $bus::chain($jobs)->dispatch();

        return $this->response(status: 202);
    }
}
