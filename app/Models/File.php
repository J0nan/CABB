<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class File extends Model
{
    protected $table = 'files';

    protected $fillable = [
        'hash', 'file_name', 'file_size', 'user_id'
    ];

    /**
     * Get the user associated with the File
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get all of the fileOwner for the File
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fileOwner(): HasMany
    {
        return $this->hasMany(FileOwner::class, 'file_id', 'id');
    }

    public function getCurrentOwners()
    {
       return $this->fileOwner->map(function ($fileOwner) { return $fileOwner->owner;})->toArray();
    }
}
