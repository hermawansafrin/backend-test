<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    /**
     * Relasi ke child permission jika ada
     * @return void
     */
    public function child()
    {
        return $this->hasMany(Permission::class, 'parent_id', 'id');
    }
}
