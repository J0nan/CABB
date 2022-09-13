<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FileOwner extends Model
{
    use SoftDeletes;

    protected $table = 'files_owners';

    protected $fillable = [
        'file_id', 'owner_id', 'added_by_user', 'deleted_at'
    ];

    /**
     * Get the owner associated with the FileOwner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function owner(): HasOne
    {
        return $this->hasOne(Owner::class, 'id', 'owner_id');
    }

    /**
     * Get the file associated with the FileOwner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function file(): HasOne
    {
        return $this->hasOne(File::class, 'id', 'file_id');
    }

    /**
     * Get the user associated with the FileOwner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'added_by_user');
    }

    public function removeOwner()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::update("update files_owners set deleted_at = '{$now}', updated_at = '{$now}' where file_id = '{$this->file_id}' and owner_id = '{$this->owner_id}'");
    }
}
