<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FileUploadedSuccessfulResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'fileHash'      => $this->resource->hash,
            'fileName'      => $this->resource->file_name,
            'fileSize'      => $this->resource->file_size,
            'currentOwners' => $this->resource->fileOwner->count(),
            'links'         => $this->links()
        ];
    }

    private function links()
    {
        return [
            'self' => [
                'id'    => $this->resource->id,
                'uri'   => route('file.getFile', [$this->resource->id])
            ],
            'successful' => [
                'id'    => null,
                'uri'   => route('uploadFileSuccessful', [$this->resource->id])
            ]
        ];
    }
}
