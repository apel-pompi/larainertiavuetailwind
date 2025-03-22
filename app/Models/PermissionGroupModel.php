<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermissionGroupModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];


    /**
     * Get all of the permissions for the PermissionGroupModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class, 'group_id', 'id');
    }


    // Relationship with Permission
    public function permission(): HasMany
    {
        return $this->hasMany(Permission::class, 'group_id');
    }
}
