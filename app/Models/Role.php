<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public const ADMINSTRATOR = 1;
    public const STAFF = 2;
}
