<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class StoreImageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => 'required|image|max:' . config('media-library.max_file_size')
        ];
    }
}
