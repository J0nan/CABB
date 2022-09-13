<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Owner extends Model
{
    protected $table = 'owners';

    protected $fillable = [
        'id', 'name', 'surname'
    ];

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    /**
     * Get all of the fileOwner for the Owner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function filesOwner(): HasMany
    {
        return $this->hasMany(FileOwner::class, 'owner_id', 'id');
    }

    public function createIfNotExists(string $name, string $surname, string $id) {
        $owner = self::where(['id' => $id])->first();
        if($owner){
            return $owner;
        } else {
            $owner = new Owner();
            $owner->id = $id;
            $owner->surname = $surname;
            $owner->name = $name;
            $owner->save();
            return $owner;
        }
    }

    public function getId()
    {
        return $this->id;
    }
}
