<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'fileHash'              => $this->resource->hash,
            'fileName'              => $this->resource->file_name,
            'fileSize'              => $this->resource->file_size,
            'autheticator'          => $this->resource->user->name,
            'currentOwners'         => $this->resource->getCurrentOwners(),
            'createdAt'             => $this->resource->created_at
        ];
    }
}
