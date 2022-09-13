<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserFilesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'files'              => $this->resource->files
        ];
    }
}
